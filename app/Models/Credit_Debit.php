<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit_Debit extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'store_id', 'author_id', 'date', 'summa', 'description'];
}
