<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = ['fio', 'limit', 'amount', 'address', 'description', 'phone'];


    public function history() {
        return $this->hasMany(Credit_Debit::class, 'client_id');
    }
}
