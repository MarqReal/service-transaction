<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;

use App\Models\User;
use App\Models\PhysicalPerson;
use App\Models\Shopkeeper;
use App\Models\Wallet;

abstract class TestCase extends BaseTestCase
{
    const FAKE_BIG_MONEY_VALUE = 20000.00;
    const FAKE_MONEY_VALUE = 500.00;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }


    /**
     * Created at - 28/04/2021
     * @return mixed
     */
    public function generatePhysicalPerson()
    {
        $user = User::factory()->create();
        $physicalPerson = PhysicalPerson::factory()->make();
        $wallet = Wallet::factory()->make();
        $physicalPerson->userPhysicalPerson()->associate($user)->save();
        $wallet->userWallet()->associate($user)->save();
        return $user;
    }

    /**
     * Created at - 28/04/2021
     * @return mixed
     */
    public function generateShopkeeper()
    {
        $user = User::factory()->create();
        $shopkeeper = Shopkeeper::factory()->make();
        $wallet = Wallet::factory()->make();
        $shopkeeper->userShopkeeper()->associate($user)->save();
        $wallet->userWallet()->associate($user)->save();
        return $user;
    }

}
