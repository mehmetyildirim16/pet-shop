<?php

namespace Database\Factories\Orders;

use App\Models\Orders\Payment;
use App\Models\Orders\OrderStatus;
use App\Models\Products\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition()
    {
        $user = Auth::user();
        assert($user instanceof User);
        $types = [
            'credit_card',
            'cash_on_delivery',
            'bank_transfer',
        ];
        $type = $types[random_int(0, 2)];
        $details = match ($type){
            'credit_card' => [
                'holder_name' => $this->faker->name,
                'number' => $this->faker->name,
                'cvv' => $this->faker->randomNumber(3),
                'expire_date' => $this->faker->creditCardExpirationDateString,
            ],
            'cash_on_delivery' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'address' => $user->address,
            ],
            'bank_transfer' => [
                'swift' => $this->faker->swiftBicNumber,
                'iban' => $this->faker->iban,
                'name' => $user->first_name . ' ' . $user->last_name,
            ],
        };
        return [
            'type' => $type,
            'details' => $details,
        ];
    }
}
