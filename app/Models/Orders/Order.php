<?php

namespace App\Models\Orders;


use App\Models\Products\Product;
use App\Models\User;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Orders\Order
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $payment_id
 * @property int $order_status_id
 * @property string $uuid
 * @property array $products
 * @property array $address
 * @property string|null $delivery_fee
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Orders\Payment|null $payment
 * @property-read \App\Models\Orders\OrderStatus $orderStatus
 * @property-read User $user
 * @method static \Database\Factories\Orders\OrderFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereProducts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUuid($value)
 * @mixin \Eloquent
 * @property string|null $shipped_at
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippedAt($value)
 * @property-read float $total_price
 */
class Order extends Model
{
    use HasFactory;
    use HasUuid;

    protected $table = 'orders';
    protected $guarded = [];

    protected $casts = [
        'products' => 'array',
        'address' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderStatus(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function getTotalPriceAttribute(): float
    {
        return array_sum(array_map(static function ($product) {
                return Product::whereUuid($product['product'])->firstOrFail()->price * $product['quantity'];
            }, $this->products));
    }

}
