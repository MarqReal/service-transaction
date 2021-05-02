<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction
{
    use HasFactory;
    protected $fillable = [
        'payer_id', 'payee_id', 'value_transfer'
    ];

//    public function userPayer()
//    {
//        return $this->belongsTo('App\Models\User', 'payer_id');
//    }
//
//    public function userPayee()
//    {
//        return $this->belongsTo('App\Models\User', 'payee_id');
//    }
}
