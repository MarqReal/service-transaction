<?php
use App\Http\UrlHelper;
use App\Services\TransactionService;

class TransactionServiceTest extends TestCase
{
    /**
     * This test aims to verify the function of the  getHasAutorization function with params failed
     */
    public function testGetHasAutorizationFailed()
    {
        UrlHelper::$externalAuthorizingUrl = "https://run.mocky.io/v3/8fafdd68-a090-496f-";
        $transactionService = new TransactionService($this->generatePhysicalPerson(), $this->generateShopkeeper(), parent::FAKE_MONEY_VALUE);
        $this->assertFalse($transactionService->getHasAutorization());
        UrlHelper::$externalAuthorizingUrl = "https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6";
    }

    /**
     * This test aims to verify the function of the  getHasAutorization function with params success
     */
    public function testGetHasAutorizationSuccess()
    {
        $transactionService = new TransactionService($this->generatePhysicalPerson(), $this->generateShopkeeper(), parent::FAKE_MONEY_VALUE);
        $this->assertTrue($transactionService->getHasAutorization());
    }

    /**
     * This test aims to verify the function of the  sendNotificationReceivement function with params failed
     */
    public function testSendNotificationReceivementFailed()
    {
        UrlHelper::$externalServiceEmailUrl = "https://run.mocky.io/v3/b19f7b9f-9cbfx";
        $transactionService = new TransactionService($this->generatePhysicalPerson(), $this->generateShopkeeper(), parent::FAKE_MONEY_VALUE);
        $this->assertFalse($transactionService->sendNotificationReceivement(parent::FAKE_MONEY_VALUE));
        UrlHelper::$externalServiceEmailUrl = "https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04";
    }

    /**
     * This test aims to verify the function of the  sendNotificationReceivement function with params success
     */
    public function testSendNotificationReceivementSuccess()
    {
        $transactionService = new TransactionService($this->generatePhysicalPerson(), $this->generateShopkeeper(), parent::FAKE_MONEY_VALUE);
        $this->assertTrue($transactionService->sendNotificationReceivement(parent::FAKE_MONEY_VALUE));
    }
}
