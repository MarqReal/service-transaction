<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shopkeeper extends Model
{

    use HasFactory;

    protected $fillable = [
        'cnpj'
    ];

    public function userShopkeeper()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}
