<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compound extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'product_id', 'quantity', 'total'];
    //Отношение с таблицей compounds
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    //Отношение с таблицей products
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
