<?php

namespace App\Data\Responses\Auth;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserResponse implements \JsonSerializable
{
    private User $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize():JsonResponse
    {
        return response()->json([
            'uuid' => $this->user->uuid,
            'First Name' => $this->user->first_name,
            'Last Name' => $this->user->last_name,
            'Email' => $this->user->email,
            'Email Verified At' => $this->user->email_verified_at,
            'Address' => $this->user->address,
            'Phone' => $this->user->phone_number,
            'Avatar' => $this->user->avatar,
            'Is Marketing' => $this->user->is_marketing,
            'Is Admin' => $this->user->is_admin,
            'Last Login At' => $this->user->last_login_at,

                                ]);
    }
}
