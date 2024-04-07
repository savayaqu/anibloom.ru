<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    //Отношение с таблицей orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
