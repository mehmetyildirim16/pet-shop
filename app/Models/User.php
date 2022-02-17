<?php

namespace App\Models;

use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Lcobucci\JWT\Token\Plain;

/**
 * App\Models\User
 *
 * @OA\Schema (
 *      type="object",
 *      description="User model",
 *      title="User",
 *     @OA\Xml(name="User"),
 * @OA\Property (
 *      property="uuid",
 *      description="User uid",
 *      title="User id",
 *      type="string",
 *      format="uuid"
 * ),
 *      @OA\Property(
 *      property="first_name",
 *      description="User first name",
 *      title="User first name",
 *      type="string"
 * ),
 *      @OA\Property(
 *      property="last_name",
 *      description="User last name",
 *      title="User last name",
 *      type="string"
 * ),
 *       @OA\Property(
 *      property="is_admin",
 *      description="User is admin if 1 else 0",
 *      title="User is admin",
 *      type="boolean"
 *  ),
 *      @OA\Property(
 *      property="email",
 *      description="User email",
 *      title="User email",
 *      type="string"
 * ),
 *      @OA\Property(
 *      property="email_verified_at",
 *      description="User email verified at",
 *      title="User email verified at",
 *      type="string"
 * ),
 *      @OA\Property(
 *      property="avatar",
 *      description="User avatar",
 *      title="User avatar",
 *      type="string"
 *    ),
 *      @OA\Property(
 *      property="address",
 *      description="User address",
 *      title="User address",
 *      type="string"
 *   ),
 *      @OA\Property(
 *      property="phone_number",
 *      description="User phone",
 *      title="User phone",
 *      type="string"
 *  ),
 *      @OA\Property(
 *      property="is_marketing",
 *      description="User is marketing if 1 else 0",
 *      title="User is marketing",
 *      type="boolean"
 * ),
 *      @OA\Property(
 *      property="last_login_at",
 *      description="User last login at",
 *      title="User last login at",
 *      type="string"
 *  ),
 *      @OA\Property(
 *      property="created_at",
 *      description="User created at",
 *      title="User created at",
 *      type="string"
 * ),
 *      @OA\Property(
 *      property="updated_at",
 *      description="User updated at",
 *      title="User updated at",
 *      type="string"
 * )
 *
 * )
 * @property string $uuid
 * @property string $first_name
 * @property string $last_name
 * @property int $is_admin
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $avatar
 * @property string|null $address
 * @property string|null  $phone_number
 * @property string $is_marketing
 * @property string|null $last_login_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null  $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[]  $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsMarketing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUuid($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{

    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $primaryKey = 'uuid';
    public $incrementing = false;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    /**
     * Get the route key for the model.
     *
     * @param string|null $bearerToken
     * @return User|null
     */
    public static function getUserByToken(?string $bearerToken): User|null
    {
        $token = UserToken::where('unique_id', $bearerToken)->firstOrFail();
        if ($token->expires_at < now()) {
            return null;
        }
        return $token->user;
    }

    /**
     * Get the route key for the model.
     *
     * @param Plain $token
     * @return void
     */
    public function addToken(Plain $token): void
    {
        UserToken::create([
                              'user_id' => $this->uuid,
                              'unique_id' => $token->claims()->toString(),
                              'token_title' => 'auth token',
                              'expires_at' => Carbon::now()->addMinutes(60),
                              'last_used_at' => Carbon::now(),
                              'refreshed_at' => Carbon::now(),
                          ]);
    }

    /**
     * Get the route key for the model.
     *
     * @return int
     */
    public function isAdmin(): int
    {
        return $this->is_admin;
    }


    /**
     * Get the valid token for the model.
     *
     * @return UserToken|null
     */
    public function getValidToken(): UserToken | null
    {
        return UserToken::where('user_id', $this->uuid)->where('expires_at', '>', now())->latest()->first();
    }

}
