<?php


namespace App\Repositories;



use App\Models\User;

class UserRepository
{
    public function __construct()
    {
    }

    /**
     * This function aims to obtain a database user record by ID
     * @param $userId
     * @return mixed
     */
    public function getUser($userId)
    {
        return User::find($userId);
    }

}
