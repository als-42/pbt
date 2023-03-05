curl --header "Content-Type: application/json" \
  --request POST \
  --data '{"uuid":"d511e5a4-541d-33b0-b866-2a385f7342f9","clientId":"1","firstname":"Carolina","lastname":"Berge","birthday":"2023-03-04","mail":"htrantow@heller.biz","address":"2722 Judy Heights\nEmardshire, NJ 07749","salary":64991,"currency":"2","requestedCreditLimit":510281679.22692704, "phone":"+380671112233"}' \
  http://localhost/creditRateLimit > resp.json