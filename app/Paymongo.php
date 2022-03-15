<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paymongo extends Model
{
    protected $table = 'paymongo';
    protected $guarded = [];
    protected $casts = [
        'body' => 'array',
    ];
}
