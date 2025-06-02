<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //
    protected $table = 'status';

    protected $fillable = [
        'code',
        'name'
    ];

    const ACTIVE = 1;
    const INACTIVE = 2;

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
