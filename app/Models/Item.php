<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_id',
        'subject',
        'unisender_send_date_at',
        'created_at',
        'updated_at'
    ];
}
