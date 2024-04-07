<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'price', 'quantity', 'photo', 'category_id'];
    //Отношение с таблицей categories
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    //Отношение с таблицей carts
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
    //Отношение с таблицей reviews
    public function review()
    {
        return $this->hasMany(Review::class);
    }
    //Отношение с таблицей orders
    public function order()
    {
        return $this->belongsToMany(Order::class, 'compound');
    }
}
