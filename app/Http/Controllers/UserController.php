<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Compound;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //Метод регистрации
    public function create(UserCreateRequest $request) {
        $user = new User($request->all());
        $user->role_id = 1; // Устанавливаем значение role_id(User)
        $user->save();
        return response([
            'message' => 'Регистрация прошла успешно',
        ], 201);
    }
    //Метод просмотра текущего пользователя
    public function this() {
        $user = auth()->user();

        return response()->json([
            'data' => $user
        ]);
    }
    //Метод обновления профиля
    public function updateProfile(UserUpdateRequest $request) {
        // Получаем текущего аутентифицированного пользователя
        $user = auth()->user();
        //Обновляем данные
        $user->fill($request->all());
        // Сохраняем обновленные данные профиля пользователя
        $user->save();
        return response()->json(['message' => 'Профиль успешно обновлен'], 200);
    }
    //Метод для просмотра всех купленных товаров и другой соответствующей информации
    public function compound()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)->get();
        $result = [];
        foreach ($orders as $order) {
            $compound = Compound::where('order_id', $order->id)->get();
            $products = [];
            foreach ($compound as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $products[] = $product;
                }
            }
            // Получаем соответствующий платеж
            $payment = Payment::find($order->payment_id);
            // Получаем соответствующий статус
            $status = Status::find($order->status_id);
            $result[] = [
                'order' => $order,
                'compound' => $compound,
                'products' => $products,
                'paymentName' => $payment ? $payment->name : 'Неизвестный способ оплаты',
                'statusName' => $status ? $status->name : 'Неизвестный статус',
            ];
        }
        return response($result);
    }
}
