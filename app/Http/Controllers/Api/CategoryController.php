<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::latest()->paginate(10);

        return new CategoryResource(true, 'List Data Categories', $categories);
    }

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name'      => 'required'
            ]);
            
            if($validator->fails()) {
                return response->json($validator->errors(), 422);
            }
            
            $category = Category::create([
                'name'      => $request->name
            ]);

            return new CategoryResource(true, 'Category Berhasil Ditambahkan', $category);
        } catch (Exception $e) {
            return response->json($e->getMessage(), 500);
        }
    }
}
