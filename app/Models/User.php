<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'surname', 'patronymic', 'login', 'password', 'birth', 'email', 'telephone', 'api_token', 'role_id'
    ];
    //Отношение с таблицей roles
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    //Отношение с таблицей orders
    public function order()
    {
        return $this->hasMany(Order::class);
    }
    //Отношение с таблицей carts
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
    //Метод проверки ролей из списка ролей
    public function hasRole(array $role) {
        return in_array($this->role->id, $role);
    }
}
