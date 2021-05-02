<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Services\UserService;

class UserServiceTest extends TestCase
{
    /**
     * This test aims to verify the function of the  hasValidUserTransaction function with params failed
     */
    public function testHasValidUserTransactionFailed()
    {
        $this->assertFalse((new UserService())->hasValidUserTransaction(0,0));
    }

    /**
     * This test aims to verify the function of the  hasValidUserTransaction function with params success
     */
    public function testHasValidUserTransactionSuccess()
    {
        $physicalPerson = $this->generatePhysicalPerson();
        $shopkeeper = $this->generateShopkeeper();
        $this->assertTrue((new UserService())->hasValidUserTransaction($physicalPerson->id, $shopkeeper->id));
    }

    /**
     * This test aims to verify the function of the  payerCanTransfer function with params failed
     */
    public function testPayerCanTransferFailed()
    {
        $shopkeeper = $this->generateShopkeeper();
        $this->assertFalse((new UserService())->payerCanTransfer($shopkeeper->id));
    }

    /**
     * This test aims to verify the function of the  payerCanTransfer function with params success
     */
    public function testPayerCanTransferSuccess()
    {
        $physicalPerson = $this->generatePhysicalPerson();
        $this->assertTrue((new UserService())->payerCanTransfer($physicalPerson->id));
    }
}
