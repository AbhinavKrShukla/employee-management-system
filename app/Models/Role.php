<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    public function permission()
    {
        return $this->hasOne(Permission::class);
    }
}
