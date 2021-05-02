<?php


namespace App\Services;


use App\Http\UrlManager;
use App\Repositories\UserRepository;

class UserService
{
    const USER_CANNOT_TRANSFER = 'You are not able to transfer to another user';
    const USERS_INVALID_TRANSFER = 'Invalid users for transaction';

    const CODE_USERS_INVALID = 'CODE_USERS_INVALID';
    const CODE_CANNOT_TRANSFER = 'CODE_CANNOT_TRANSFER';

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    /**
     * This function aims to validate that the user is not a shopkeeper,
     * so he can transfer
     * @param $payerId
     * @return bool
     */
    public function payerCanTransfer($payerId)
    {
        $user = $this->userRepository->getUser($payerId);
        return $user && !$user->shopKeeper;
    }

    /**
     * This function aims to validate whether the users that are part of the
     * transaction are valid
     * @param $payerId
     * @param $payeeId
     * @return bool
     */
    public function hasValidUserTransaction($payerId, $payeeId)
    {
        $payer = $this->userRepository->getUser($payerId);
        $payee = $this->userRepository->getUser($payeeId);
        return $payer && $payee && ($payeeId !== $payerId);
    }

    /**
     * This function aims to make the call to the repository layer to obtain
     * a user registered in the database.
     * @param $userId
     * @return mixed
     */
    public function getUser($userId)
    {
        return $this->userRepository->getUser($userId);
    }
}
