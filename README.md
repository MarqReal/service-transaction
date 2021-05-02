# REST API Micro Transaction Service

This project is a micro transaction service, used to process money transfer between users' wallets.The entire application is contained in the app folder.

This application was developed using the Lumen Framework, due to its high performance for applications of this type

## Install
Create a file in the project's root folder called `.env`, copy the contents of the .env.example file to this new `.env` file

Run the following command inside the micro-service-transaction folder

`docker-compose up --build -d`

Run the following command to create the application tables, the command must be executed inside the php container (`docker-compose exec php bash`)

`php artisan migrate:install`

`php artisan migrate`

In this application, the registration of users and their respective forms was not prioritized, the transaction process was prioritized, so to populate the bank with users and their respective portfolios, run the following command

`php artisan db:seed`

Notation: This command will generate two users for the application, one user of the Physical Person type and the other of the Shopkeeper type. User ids will be respectively `1` for Individuals and` 2` for Shopkeeper

## Run the app
`docker-compose up -d`

## Run the tests
To run the tests it is necessary to enter the php container (`docker-compose exec php bash`), go to the `/var/www/html` directory and run the following command

`vendor/bin/phpunit`

# REST API
The REST API for the Micro transaction service application is described below.

## Performs the process of transferring money between portfolios

### Request

`POST /transaction/`

    curl -i -H 'Accept: application/json' -d 'payer=1&payee=2&value=100' http://localhost/transaction

### Response

    HTTP/1.1 200 OK
    Server: nginx/1.19.2
    Content-Type: application/json
    Transfer-Encoding: chunked
    Connection: keep-alive
    X-Powered-By: PHP/7.4.16
    Cache-Control: no-cache, private
    Date: Fri, 30 Apr 2021 19:08:15 GMT

    {"success":true,"message":"Your transaction was successful","code":"CODE_TRANSACTION_FULFILLED"}
