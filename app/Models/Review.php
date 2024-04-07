<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['rating', 'textReview', 'user_id', 'product_id'];
    //Отношение с таблицей users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //Отношение с таблицей products
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
