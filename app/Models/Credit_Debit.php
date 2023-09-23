<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit_Debit extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'store_id', 'author_id', 'date', 'summa', 'description', 'type'];



    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function store() {
        return $this->belongsTo(Store::class);
    }


}