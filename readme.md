# Hello whoami!

Thank you for your time to review sources,
any commentaries and questions very welcome...

## app struct: 
- topic about flat structure vs micro-apps/services - is away out of test task
- now sources has next design "xCom\\": "src/xCom" we have global company space - xCom
- this test task is like a microservice - and I'm put it into  src/xCom/my-service-name
- also has two folder that extends xcom layer: contracts and libraries -> but maybe need it into independent packages

### remark:
- this test in many points is not finished, possible get done with few months on real project...

run a.sh
```json

{
    "http_status_code":422,
    "http_status_message":"Unprocessable Content",
    "errors":["Param clientId is required","Param birthday is required","Param requestedCreditLimit is required"],
    "response":[]
}
```

run b.sh
```json
{
  "http_status_code":201,
  "http_status_message":"Created",
  "errors":[],
  "response":{"ref":"b6765cc3-e99a-3854-9ee4-c2c430de12d4"}
}
```

other notes you can find in source...
