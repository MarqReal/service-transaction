<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PhysicalPerson;
use App\Models\Shopkeeper;
use App\Models\Wallet;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userPrimary = User::factory()->create();
        (PhysicalPerson::factory()->make())->userPhysicalPerson()->associate($userPrimary)->save();
        (Wallet::factory()->make())->userWallet()->associate($userPrimary)->save();

        $userSecond = User::factory()->create();
        (Shopkeeper::factory()->make())->userShopkeeper()->associate($userSecond)->save();
        (Wallet::factory()->make())->userWallet()->associate($userSecond)->save();
    }
}
