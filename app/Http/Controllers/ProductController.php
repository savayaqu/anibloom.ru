<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //Метод просмотра товаров конкретной категории
    public function showMany(int $id) {
        $products = Product::where('category_id', $id)->get();
        if($products->isEmpty()) {
            throw new ApiException(404, 'Товары не найдены');
        }
        return response([
            'data' => $products
        ]);
    }
    //Метод просмотра всех товаров
    public function index() {
        $products = Product::all();
        if($products->isEmpty()) {
            throw new ApiException(404, 'Товары не найдены');
        } else {
            return response([
                'data' => $products,
            ]);
        }
    }
    //Метод просмотра конкретного товара
    public function show(int $id) {
        $product = Product::where('id', $id)->first();
        if(!$product) throw new ApiException(404, 'Товар не найден');
        return response([
            'data' => $product
        ]);
    }
}
