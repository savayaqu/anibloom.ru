<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //Метод просмотра корзины текущего пользователя
    public function index()
    {
        // Получение текущего пользователя
        $user = auth()->user();
        // Получаем корзину текущего пользователя
        $cartItems = $user->cart;
        // Вычисляем общую стоимость всех товаров в корзине
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->product->price * $item->quantity;
        }
        // Форматирование общей цены
        $formattedTotalPrice = number_format($totalPrice, 2, '.', '');
        // Возвращаем JSON-ответ с содержимым корзины и общей стоимостью
        return response()->json([
            'cart_items' => $cartItems,
            'total' => $formattedTotalPrice
        ]);
    }
    //Метод редактирования корзины
    public function update(Request $request)
    {
        // Получаем текущего пользователя
        $user = auth()->user();
        // Получаем product_id и новое количество товара из запроса
        $productId = $request->input('product_id');
        $newQuantity = $request->input('quantity');
        // Находим товар в корзине текущего пользователя по product_id
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();
        // Проверка на существование товара
        $product = Product::find($productId);
        // Получение доступного количества товара из базы данных
        $availableQuantity = $product->quantity;
        // Проверяем, найден ли товар в корзине
        if (!$cartItem) {
            throw new ApiException(404, 'Товар не найден в вашей корзине');
        }
        // Проверяем, существует ли уже запись для этого товара в корзине пользователя
        $existingCartItem = $user->cart()->where('product_id', $product->id)->first();
        // Проверяем, чтобы новое количество было положительным числом и не было больше того, что в хранится в БД
        if ($newQuantity > 0 && $newQuantity <= $availableQuantity) {
            // Обновляем количество товара
            $existingCartItem->quantity = $newQuantity;
            $existingCartItem->price = $product->price * $newQuantity;
            $existingCartItem->save();
            // Возвращаем ответ с сообщением об успешном обновлении корзины
            return response()->json(['message' => 'Количество товара в корзине успешно обновлено'], 200);
        } else {
            throw new ApiException(400, 'Количество товара должно быть положительным числом');
        }
    }
    //Метод добавления товара в корзину
    public function addToCart(Request $request, $id) {
        // Получение текущего пользователя
        $user = auth()->user();
        // Проверка существует ли пользователь
        if (!$user) {
            return response()->json(['error' => 'Пользователь не авторизирован'], 401);
        }
        $product = Product::find($id);
        // Проверка на существование товара
        if(!$product) {
            throw new ApiException(404, 'Товар не найден');
        }
        // Получение доступного количества товара из базы данных
        $availableQuantity = $product->quantity;
        // Получение количества товара из запроса (добавляем значение по умолчанию = 1)
        $quantity = $request->input('quantity', 1);
        // Проверка, что количество товара больше 0
        if ($quantity <= 0) {
            return response()->json(['error' => 'Количество товара должно быть больше 0'], 400);
        }
        // Проверка, что запрошенное количество товара не превышает доступное количество
        if ($quantity > $availableQuantity) {
            return response()->json(['error' => 'Недостаточное количество товара в наличии'], 400);
        }
        // Проверяем, существует ли уже запись для этого товара в корзине пользователя
        $existingCartItem = $user->cart()->where('product_id', $product->id)->first();
        if ($existingCartItem) {
            // Если запись уже существует, обновляем количество товара
            $existingCartItem->quantity = $quantity;
            $existingCartItem->price = $product->price * $quantity;
            $existingCartItem->save();
        } else {
            // Создание нового элемента корзины и связывание с пользователем
            $cartItem = new Cart([
                'quantity' => $quantity,
                'price' => $product->price * $quantity,
                'product_id' => $product->id,
            ]);
            $user->cart()->save($cartItem);
        }
        return response()->json(['message' => 'Продукт добавлен в корзину']);
    }
    //Метод удаления товара из корзины
    public function delete(Request $request, int $id)
    {
        // Получаем текущего пользователя
        $user = auth()->user();
        // Находим товар в корзине текущего пользователя по id
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $id)
            ->first();
        // Проверяем, найден ли товар в корзине
        if (!$cartItem) {
            throw new ApiException(404, 'Товар не найден в вашей корзине');
        }
        // Удаляем товар из корзины
        $cartItem->delete();
        // Возвращаем ответ с сообщением об успешном удалении товара из корзины
        return response()->json(['message' => 'Товар успешно удален из корзины'], 200);
    }
}
