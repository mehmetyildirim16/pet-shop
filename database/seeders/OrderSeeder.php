<?php

namespace Database\Seeders;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class OrderSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'open',
            'pending payment',
            'paid',
            'shipped',
            'cancelled',
        ];
        foreach ($statuses as $status) {
            OrderStatus::create([
                                    'title' => $status,
                                ]);
        }
        $users = User::all();
        foreach ($users as $user) {
            Auth::login($user);
            Order::factory(5)
                ->create([
                             'user_id' => $user->id,
                         ]);
        }
    }
}
