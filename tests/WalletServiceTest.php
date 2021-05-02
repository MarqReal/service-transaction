<?php
use App\Services\WalletService;
use App\Services\TransactionService;

class WalletServiceTest extends TestCase
{
    /**
     * This test aims to verify the function of the  payerHasBalance function with params failed
     */
    public function testPayerHasBalanceFailed()
    {
        $walletService = new WalletService();
        $this->assertFalse($walletService->payerHasBalance($this->generatePhysicalPerson(), parent::FAKE_BIG_MONEY_VALUE));
    }

    /**
     * This test aims to verify the function of the  payerHasBalance function with params success
     */
    public function testPayerHasBalanceSuccess()
    {
        $walletService = new WalletService();
        $this->assertTrue($walletService->payerHasBalance($this->generatePhysicalPerson(), parent::FAKE_MONEY_VALUE));
    }

    /**
     * This test aims to verify the function of the  makeTransfer function with params success
     */
    public function testMakeTransferSuccess()
    {
        $person = $this->generatePhysicalPerson();
        $shopkeeper = $this->generateShopkeeper();
        $transactionService = new TransactionService($person, $shopkeeper, parent::FAKE_MONEY_VALUE);
        $walletService = new WalletService();
        $this->assertTrue($walletService->makeTransfer($person, $shopkeeper, ['payer' => $transactionService->getNewBalancePayer(), 'payee' => $transactionService->getNewBalancePayee()]));
    }
}
