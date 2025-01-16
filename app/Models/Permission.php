<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = [];

    protected $casts = [
        'name' => 'array'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

}
