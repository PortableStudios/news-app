import json
import requests
import boto3
import os
import logging
import hashlib
import datetime
from datetime import timezone
from common import response

dynamodb = boto3.client('dynamodb')
logger = logging.getLogger('boto3')
logger.setLevel(logging.INFO)

def lambda_handler(event, context):
    ##
    # This is the main entry to feed the cache, since we want to have multiple feeds from
    # various places this should call kick off all the feeds (Guardian, etc) via SNS topic, that
    # all feeders are subscribed to.
    # But, due to time limitation, this feed for now directly calls GuardianAPI
    ##
    try:
        articles = fetchFromGuardian()
        print (articles)
    except requests.exceptions.RequestException as e:
        logging.critical(e, exc_info=True)
        return response(code=500, body="Failed getting response from Guardian API")

    if 'response' not in articles:
        return response(code=400, body="Could not get proper response from Reel API")

    for data in articles['response']['results']:
        store(data)

    return response()

def store(data):
    ##
    # Lets store some data
    # Note: this will hash the id from the feed, and use that to dedupe, this is here since we need a way
    # to incrementally update the cache, segmented by dates, or pagination or..
    # @todo this could be also abstracted out, with a use of a data formatter specifically for each feed
    ##
    table_name = os.environ.get('CACHE_TABLE', 'articleCache')
    params = {
        'id'                : { 'S' : str(abs(hash(data['id']))) },
        'title'             : { 'S' : data['webTitle'] },
        'publisher'         : { 'S' : "guardian" },
        'publication_date'  : { 'S' : str( data['webPublicationDate'] ) },
        # @todo format to be consistent with publication date
        'import_date'       : { 'S' : str( datetime.datetime.now(timezone.utc) ) },
        'url'               : { 'S' : str( data['webUrl'] ) },
    }

    return dynamodb.put_item(
        TableName=table_name,
        Item=params
    )


def fetchFromGuardian():
    ##
    # Lets grab some data from Guardian
    ##
    api_endpoint = os.environ.get('API_ENDPOINT')
    api_token = os.environ.get('API_TOKEN')

    request = requests.get(api_endpoint, params={'api-key':api_token})
    return request.json()