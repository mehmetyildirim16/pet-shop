<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserToken
 *
 * @property int $id
 * @property string|null $unique_id
 * @property string $user_id
 * @property string $token_title
 * @property mixed|null $restrictions
 * @property mixed|null $permissions
 * @property string|null $expires_at
 * @property string|null $refreshed_at
 * @property string|null $last_used_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserToken whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserToken wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserToken whereRefreshedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserToken whereRestrictions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserToken whereTokenTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserToken whereUniqueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserToken whereUserId($value)
 * @mixin \Eloquent
 */
class UserToken extends Model
{
    protected $guarded = [];
    protected $table = 'jwt_tokens';

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
