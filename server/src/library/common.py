
def response(code=200, headers={
            "content-type":"application/json",
            "Access-Control-Allow-Headers" : "Content-Type",
            "Access-Control-Allow-Origin": "*",
            "Access-Control-Allow-Methods": "GET"}, body='Ok'):
    return {
        'statusCode': code,
        'headers' : headers,
        'body': body
    }