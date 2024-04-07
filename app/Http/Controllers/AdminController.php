<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Requests\ReviewUpdateRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    //Метод создания категории
    public function createCategory(CategoryCreateRequest $request)
    {
        // Проверяем, есть ли категория с таким именем уже в базе данных
        $existingCategory = Category::where('name', $request->input('name'))->first();
        if ($existingCategory) {
            throw new ApiException(422, 'Категория с таким именем уже существует');
        }

        // Создаем новую категорию
        $category = new Category([
            'name' => $request->input('name'),
        ]);

        // Сохраняем категорию в базе данных
        $category->save();

        // Возвращаем успешный ответ
        return response()->json(['message' => 'Категория успешно создана'], 201);
    }
    //Метод создания товара
    public function createProduct(ProductCreateRequest $request)
    {
        // Проверяем, есть ли продукт с таким именем уже в базе данных
        $existingProduct = Product::where('name', $request->input('name'))->first();
        if ($existingProduct) {
            throw new ApiException(422, 'Продукт с таким именем уже существует');
        }

        // Создаем новый продукт
        $product = new Product($request->all());

        // Сохраняем продукт в базе данных
        $product->save();

        // Получаем ID только что созданного продукта
        $productId = $product->id;

        // Проверяем, загружен ли файл
        if ($request->hasFile('photo')) {
            // Получаем файл из запроса
            $file = $request->file('photo');
            // Определяем путь для сохранения файла
            $filePath = 'uploads/' . $request->input('category_id');
            // Переименовываем файл
            $fileName = $productId . '.' . $file->getClientOriginalExtension(); // Получение расширения оригинального файла
            // Сохраняем файл на сервере
            $filePathToPlace = $file->storeAs($filePath, $fileName);

            // Проверяем успешность сохранения файла
            if ($fileName) {
                // Файл успешно сохранен, продолжаем сохранение продукта с указанием имени файла
                $product->photo = $filePathToPlace; // Сохраняем путь до файла

                Storage::putFileAs('public/' . $filePath, $file, $fileName);


                $product->save();

                return response()->json(['message' => 'Продукт успешно создан'], 201);
            } else {
                // Если возникла ошибка при сохранении файла
                throw new ApiException(500, 'Ошибка сохранить файл');
            }
        } else {
            // Если файл не был загружен, просто возвращаем ответ об успешном создании продукта
            return response()->json(['message' => 'Продукт успешно создан'], 201);
        }
    }
    //Метод обновления категории
    public function updateCategory(CategoryUpdateRequest $request, $id)
    { // Проверяем, есть ли категория с таким именем уже в базе данных
        $existingCategory = Category::where('name', $request->input('name'))->first();
        if ($existingCategory) {
            throw new ApiException(422, 'Категория с таким именем уже существует');
        }
        //Проверка существования
        $category = Category::find($id);
        if (!$category) {
            throw new ApiException(404, 'Категория не найдена');
        }
        $category->name = $request->input('name');
        $category->save();
        return response()->json(['message' => 'Категория успешно обновлена'], 200);

    }
    //Метод обновления товара
    public function updateProduct(ProductUpdateRequest $request, $id)
    {
        //Проверка существования
        $product = Product::find($id);
        if (!$product) {
            throw new ApiException(404, 'Товар не найден');
        }
        // Проверяем, есть ли продукт с таким именем уже в базе данных
        $existingProduct = Product::where('name', $request->input('name'))->first();
        if ($existingProduct) {
            throw new ApiException(422, 'Продукт с таким именем уже существует');
        }
        // Обновление изображения товара, если новое изображение предоставлено
        // Проверяем, загружен ли файл
        if ($request->hasFile('photo')) {
            // Получаем файл из запроса
            $file = $request->file('photo');
            // Получаем текущую категорию или новую
            $category_id = $request->input('category_id') ? $request->input('category_id') : $product->category_id;
            // Определяем путь для сохранения файла
            $filePath = 'uploads/' . $category_id;
            //переименовываем файл
            $fileName = $product->id . '.' . $file->getClientOriginalExtension(); // Получение расширения оригинального файла
            //удаление файла на сервере
            if($product->photo != NULL)Storage::delete($product->photo);
            // Сохраняем файл на сервере
            Storage::putFileAs('public/' . $filePath, $file, $fileName);


            $filePathToPlace = $file->storeAs($filePath, $fileName);
            $product->photo = $filePathToPlace; // Сохраняем путь до файла
        }
        // Сохранение остальных данных товара
        $product->fill($request->except('photo')); // Обновляем все остальные поля товара, кроме изображения
        // Сохранение изменений
        $product->save();
        return response()->json(['message' => 'Товар успешно обновлен'], 200);
    }
    //Метод удаления категории
    public function deleteCategory($id)
    {
        $category = Category::find($id);

        if (!$category) {
            throw new ApiException(404, 'Категория не найдена');
        }

        $category->delete();

        return response()->json(['message' => 'Категория успешно удалена'], 200);
    }
    //Метод удаления товара
    public function deleteProduct($id)
    {
        $product = Product::find($id);

        if (!$product) {
            throw new ApiException(404, 'Продукт не найден');

        }
        $product->delete();
        return response()->json(['message' => 'Продукт успешно удален'], 200);
    }
}
