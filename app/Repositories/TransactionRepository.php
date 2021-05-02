<?php


namespace App\Repositories;


use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionRepository
{
    public function __construct()
    {
    }

    /**
     * This function is intended to record data from the transaction that was carried out
     * @param User $payer
     * @param User $payee
     * @param $value
     */
    public function recordsTransaction(User $payer, User $payee, $value)
    {
        DB::table('transactions')->insert([
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'value_transfer' => $value
        ]);
    }
}
