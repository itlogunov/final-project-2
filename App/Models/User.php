<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    public function images()
    {
        return $this->hasMany('App\Models\File', 'user_id', 'id');
    }
}
