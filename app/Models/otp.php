<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'exp_time',
        'phone_number',
        'sms_response',
        'sms_id',
        'project',
        'desc',
        'auth_status',
        'otp'
    ];
}
