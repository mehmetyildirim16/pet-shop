<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $guarded = [];
    protected $table = 'password_resets';


    protected $primaryKey = 'email';

}
