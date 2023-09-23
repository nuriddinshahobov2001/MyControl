<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $guarded = false;


    public function users()
    {
        return $this->belongsToMany(User::class, 'user_stores', 'store_id', 'user_id');
    }
}
