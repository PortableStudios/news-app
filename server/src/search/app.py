import json
import os
import boto3
from common import response

dynamodb = boto3.client('dynamodb')
lam = boto3.client('lambda')

def lambda_handler(event, context):
    # Main handler for the lambda function,
    # checks params, invokes search, and returns response
    if ('pathParameters' not in event 
            or event['httpMethod'] != 'GET'):
        return response(code=400, body=json.dumps({'msg': 'Bad Request'}))

    # TODO: need to ensure event query parameter is available and valid 
    query = event['pathParameters']['query']
    items = search(query).get('Item')
    print (items)

    return response(items)
        

def search(query):
    # Lets find some articles
    table_name = os.environ.get('CACHE_TABLE', 'articleCache')

    table = dynamodb.Table(table_name)
    return table.query(
            FilterExpression=Attr('title').contains(query)
        )
