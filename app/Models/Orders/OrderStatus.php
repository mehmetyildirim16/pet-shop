<?php

namespace App\Models\Orders;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Orders\OrderStatus
 *
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Orders\Order[] $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereUuid($value)
 * @mixin \Eloquent
 * @method static \Database\Factories\Orders\OrderStatusFactory factory(...$parameters)
 */
class OrderStatus extends Model
{
    use HasUuid;
    use HasFactory;

    public const STATUS_OPEN = 'open';
    public const STATUS_PENDING = 'pending payment';
    public const STATUS_PAID = 'paid';
    public const SHIPPED = 'shipped';
    public const CANCELLED = 'cancelled';

    protected $table = 'order_statuses';
    protected $guarded = [];

    public static function getDefaultStatus():self
    {
        return self::whereTitle(self::STATUS_OPEN)->firstOrFail();
    }

    public function orders():HasMany
    {
        return $this->hasMany(Order::class);
    }
}
