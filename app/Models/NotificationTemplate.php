<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'key',
        'mail_subject',
        'mail_body',
        'sms_text',
        'push_title',
        'push_body',
    ];
}
