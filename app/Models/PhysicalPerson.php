<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysicalPerson extends Model {
    use HasFactory;

    protected $fillable = [
        'cpf'
    ];

    public function userPhysicalPerson()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}
