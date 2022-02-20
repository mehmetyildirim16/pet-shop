<?php

namespace App\Models\Orders;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Orders\Payment
 *
 * @property int                                $id
 * @property string                             $uuid
 * @property string                             $type
 * @property array                              $details
 * @property \Illuminate\Support\Carbon|null    $created_at
 * @property \Illuminate\Support\Carbon|null    $updated_at
 * @property-read \App\Models\Orders\Order|null $order
 * @method static \Database\Factories\Orders\PaymentFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUuid($value)
 * @mixin \Eloquent
 */
class Payment extends Model
{

    use HasFactory;
    use HasUuid;

    protected $table = 'payments';
    protected $guarded = [];

    protected $casts = [
        'details' => 'array',
    ];

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    public static function rules(): array
    {
        return [
            'type' => 'required|string',
            'details' => 'required|array',
            'details.holder_name' => 'required_if:type,credit_card|string',
            'details.number' => 'required_if:type,credit_card|string',
            'details.expire_date' => 'required_if:type,credit_card|string',
            'details.cvv' => 'required_if:type,credit_card|integer',
            'details.first_name' => 'required_if:type,cash_on_delivery|string',
            'details.last_name' => 'required_if:type,cash_on_delivery|string',
            'details.address' => 'required_if:type,cash_on_delivery|email',
            'details.swift' => 'required_if:type,bank_transfer|string',
            'details.iban' => 'required_if:type,bank_transfer|string',
            'details.name' => 'required_if:type,bank_transfer|string',
        ];
    }
}
