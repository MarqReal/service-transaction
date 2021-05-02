<?php


namespace App\Services;


use App\Models\User;
use App\Repositories\WalletRepository;

class WalletService
{
    const WALLET_INVALID_BALANCE = 'You do not have enough balance in your wallet to make this transfer';
    const CODE_WALLET_INVALID_BALANCE = 'CODE_WALLET_INVALID_BALANCE';

    private $walletRepository;

    public function __construct()
    {
        $this->walletRepository = new WalletRepository();

    }

    /**
     * This function aims to validate if the user has enough balance
     * to transfer
     * @param $payer
     * @param $valueTransaction
     * @return bool
     */
    public function payerHasBalance($payer, $valueTransaction)
    {
        return $payer->wallet->balance > 0 && $payer->wallet->balance > $valueTransaction;
    }

    /**
     * This function aims to transfer / update values for users wallets
     * @param User $payer
     * @param User $payee
     * @param array $valuesTransaction
     * @return bool
     */
    public function makeTransfer(User $payer, User $payee, array $valuesTransaction)
    {
        try {
            $this->updateBalanceWallet($payer, $valuesTransaction['payer']);
            $this->updateBalanceWallet($payee, $valuesTransaction['payee']);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * This function aims to make a call to the repository layer
     * to update a user's wallet
     * @param User $user
     * @param $balance
     */
    public function updateBalanceWallet(User $user, $balance)
    {
        $this->walletRepository->updateBalanceWallet($user, $balance);
    }

}
