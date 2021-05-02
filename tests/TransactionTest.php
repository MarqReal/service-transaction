<?php

use App\Http\UrlHelper;
use App\Services\UserService;
use App\Services\WalletService;
use App\Services\TransactionService;

class TransactionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    const FAKE_INVALID_ID_USER = 0;
    const NO_VALUE_TRANSACTION = "";

    /**
     * This test aims to verify the integration functioning between the functions solved
     * for the transaction process, with process resulting in success
     */
    public function testTransactionFulfilledSuccess()
    {
        $physicalPerson = $this->generatePhysicalPerson();
        $shopkeeper = $this->generateShopkeeper();
        $this->post("/transaction", $this->mountBodyRequest($physicalPerson, $shopkeeper, parent::FAKE_MONEY_VALUE))
            ->seeStatusCode(200)
            ->seeJson([
                'success' => true,
                'message' => TransactionService::TRANSACTION_FULFILLED,
                'code' => TransactionService::CODE_TRANSACTION_FULFILLED
            ]);
    }

    /**
     * This test aims to verify the integration functioning between the functions solved
     * for the transaction process, with process resulting in failed because
     * no valeu for Transaction
     *
     */
    public function testTransactionNoValue()
    {
        $physicalPerson = $this->generatePhysicalPerson();
        $shopkeeper = $this->generateShopkeeper();
        $this->post("/transaction", $this->mountBodyRequest($physicalPerson, $shopkeeper, self::NO_VALUE_TRANSACTION))
            ->seeStatusCode(400)
            ->seeJson([
                'success' => false,
                'message' => TransactionService::UNABLE_COMPLETE_TRANSACTION,
                'code' => TransactionService::CODE_UNABLE_COMPLETE_TRANSACTION
            ]);
    }

    /**
     * This test aims to verify the integration functioning between the functions solved
     * for the transaction process, with process resulting in failed because
     * Users invalids for Transaction
     */
    public function testTransactionUsersInvalidsFailed()
    {
        $physicalPerson = $this->generatePhysicalPerson();
        $shopkeeper = $this->generateShopkeeper();
        $body = $this->mountBodyRequest($physicalPerson, $shopkeeper, parent::FAKE_MONEY_VALUE);
        $body['payer'] = self::FAKE_INVALID_ID_USER;
        $this->post("/transaction", $body)
            ->seeStatusCode(400)
            ->seeJson([
                'success' => false,
                'message' => UserService::USERS_INVALID_TRANSFER,
                'code' => UserService::CODE_USERS_INVALID
            ]);
    }

    /**
     * This test aims to verify the integration functioning between the functions solved
     * for the transaction process, with process resulting in failed because
     * Shopkeeper User cant transfer money for others users
     */
    public function testTransactionShopkeeperFailed()
    {
        $physicalPerson = $this->generatePhysicalPerson();
        $shopkeeper = $this->generateShopkeeper();
        $this->post("/transaction", $this->mountBodyRequest($shopkeeper, $physicalPerson, parent::FAKE_MONEY_VALUE))
            ->seeStatusCode(400)
            ->seeJson([
                'success' => false,
                'message' => UserService::USER_CANNOT_TRANSFER,
                'code' => UserService::CODE_CANNOT_TRANSFER
            ]);
    }

    /**
     * This test aims to verify the integration functioning between the functions solved
     * for the transaction process, with process resulting in failed because
     * The Payer havent balance
     */
    public function testTransactionUserNoBalanceFailed()
    {
        $physicalPerson = $this->generatePhysicalPerson();
        $shopkeeper = $this->generateShopkeeper();
        $physicalPerson->wallet->balance = 0;
        $physicalPerson->wallet->update();
        $this->post("/transaction", $this->mountBodyRequest($physicalPerson, $shopkeeper, parent::FAKE_MONEY_VALUE))
            ->seeStatusCode(400)
            ->seeJson([
                'success' => false,
                'message' => WalletService::WALLET_INVALID_BALANCE,
                'code' => WalletService::CODE_WALLET_INVALID_BALANCE
            ]);
    }

    /**
     * This test aims to verify the integration functioning between the functions solved
     * for the transaction process, with process resulting in failed because
     * The Payer havent balance
     */
    public function testTransactionAmountGreaterThanTransactionFailed()
    {
        $physicalPerson = $this->generatePhysicalPerson();
        $shopkeeper = $this->generateShopkeeper();
        $this->post("/transaction", $this->mountBodyRequest($physicalPerson, $shopkeeper, parent::FAKE_BIG_MONEY_VALUE))
            ->seeStatusCode(400)
            ->seeJson([
                'success' => false,
                'message' => WalletService::WALLET_INVALID_BALANCE,
                'code' => WalletService::CODE_WALLET_INVALID_BALANCE
            ]);
    }

    /**
     * This test aims to verify the integration functioning between the functions solved
     * for the transaction process, with process resulting in failed because
     * The transaction not autorized by authorizing service external
     */
    public function testTransactionNotAutorizedFailed()
    {
        $physicalPerson = $this->generatePhysicalPerson();
        $shopkeeper = $this->generateShopkeeper();
        UrlHelper::$externalAuthorizingUrl = "https://run.mocky.io/v3/8fafdd68-a090-496f-";
        $this->post("/transaction", $this->mountBodyRequest($physicalPerson, $shopkeeper, parent::FAKE_MONEY_VALUE))
            ->seeStatusCode(401)
            ->seeJson([
                'success' => false,
                'message' => TransactionService::UNAUTHORIZED_BY_EXTERNAL_SERVICE,
                'code' => TransactionService::CODE_UNAUTHORIZED_EXTERNAL_SERVICE
            ]);
        UrlHelper::$externalAuthorizingUrl = "https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6";
    }

    /**
     * This test aims to verify the integration functioning between the functions solved
     * for the transaction process, with process resulting in failed because
     * The transaction not get send email notification for user
     */
    public function testTransactionUnableCompleteFailed()
    {
        $physicalPerson = $this->generatePhysicalPerson();
        $shopkeeper = $this->generateShopkeeper();
        UrlHelper::$externalServiceEmailUrl = "https://run.mocky.io/v3/b19f7b9f-9cbfx";
        $this->post("/transaction", $this->mountBodyRequest($physicalPerson, $shopkeeper, parent::FAKE_MONEY_VALUE))
            ->seeStatusCode(503)
            ->seeJson([
                'success' => false,
                'message' => TransactionService::UNABLE_COMPLETE_TRANSACTION,
                'code' => TransactionService::CODE_UNABLE_COMPLETE_TRANSACTION
            ]);
        UrlHelper::$externalServiceEmailUrl = "https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04";
    }

    /**
     * This function mount default body for tests in api
     * @param $payeer
     * @param $payee
     * @param $value
     * @return array
     */
    public function mountBodyRequest($payeer, $payee, $value)
    {
        return [
            'value' => $value,
            'payer' => $payeer->id,
            'payee' => $payee->id
        ];
    }

}
