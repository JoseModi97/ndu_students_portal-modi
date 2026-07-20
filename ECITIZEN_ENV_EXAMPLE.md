# eCitizen `.env` Example

The eCitizen configuration is read through PHP environment variables using
`getenv(...)`. If your server loads a `.env` file, it can look like this:

```dotenv
# eCitizen API credentials
ECITIZEN_API_CLIENT_ID=your-api-client-id
ECITIZEN_API_KEY=your-api-key
ECITIZEN_SECRET=your-secret

# eCitizen service details
ECITIZEN_SERVICE_ID=2798167
ECITIZEN_PAYMENT_URL=https://payments.ecitizen.go.ke/PaymentAPI/iframev2.1.php
ECITIZEN_STATUS_URL=https://payments.ecitizen.go.ke/api/invoice/payment/status

# Payment defaults
ECITIZEN_CURRENCY=KES
ECITIZEN_BANK_ACCOUNT_ID=51
ECITIZEN_SEND_STK=false

# Optional display and certificate settings
ECITIZEN_PICTURE_URL=
ECITIZEN_CA_BUNDLE_PATH=

# Optional external credentials file
# ECITIZEN_CREDENTIALS_FILE=/absolute/path/to/ecitizen/credentials.php
```

Do not commit real production credentials. Use the actual values only on the
server or in a private deployment environment.
