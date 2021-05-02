<?php


namespace App\Repositories;


use App\Models\User;

class WalletRepository
{
     public function __construct()
     {

     }

    /**
     * This function aims to update the balance of the user's wallet
     * @param User $user
     * @param $newBalance
     */
     public function updateBalanceWallet(User $user, $newBalance)
     {
         $user->wallet->balance = $newBalance;
         $user->wallet->update();
     }

}
