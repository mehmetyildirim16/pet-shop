<?php

namespace Database\Seeders;

use App\Models\Orders\OrderStatus;
use Illuminate\Database\Seeder;

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
            'cancelled'
        ];
        foreach ($statuses as $status) {
            OrderStatus::create([
                'title' => $status,
            ]);
        }
    }
}
