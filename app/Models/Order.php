<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['address', 'dateOrder', 'payment_id', 'user_id', 'status_id'];
    //Отношение с таблицей users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //Отношение с таблицей payments
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
    //Отношение с таблицей statuses
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
    //Отношение с таблицей products
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }
    //Отношение с таблицей compounds
    public function compound()
    {
        return $this->hasMany(Compound::class);
    }
}
