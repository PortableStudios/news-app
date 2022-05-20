
def response(code=200, headers={"content-type":"application/json"}, body='Ok'):
    return {
        'statusCode': code,
        'headers' : headers,
        'body': body
    }