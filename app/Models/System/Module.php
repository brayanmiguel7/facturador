<?php

namespace App\Models\System;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{

    protected $fillable = [
        'description',
    ];

}