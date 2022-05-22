import os
import boto3
import json
from common import response
from boto3.dynamodb.conditions import Attr

dynamodb = boto3.resource('dynamodb')

def lambda_handler(event, context):
    # Main handler for the lambda function,
    # checks params, invokes search, and returns response
    if ('queryStringParameters' not in event
            or 'httpMethod' not in event
            or 'query' not in event['queryStringParameters']
            or event['httpMethod'] != 'GET'):
        return response(code=400)

    query = event['queryStringParameters']['query']
    items = search(query)
    sort_items = sorted(items, key=lambda x: x['section'])
    return response(body=json.dumps(sort_items))


def search(query):
    table_name = os.environ.get('CACHE_TABLE', 'articleCache')
    table = dynamodb.Table(table_name)

    # Lets find some articles
    # @todo this isn't great, scan will get slower over time due to size of the table
    # @todo review the use of elastic search thru dynamodb streams
    response = table.scan(
        FilterExpression=Attr('title').contains(str(query))
    )

    return response.get('Items')