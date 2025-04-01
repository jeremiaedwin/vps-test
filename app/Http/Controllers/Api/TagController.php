<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function index() {
        $tags = Tag::latest()->paginate(10);

        return new TagResource(true, 'List Data Tags', $tags);
    }

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name'      => 'required'
            ]);
            
            if($validator->fails()) {
                return response->json($validator->errors(), 422);
            }
            
            $tag = Tag::create([
                'name'      => $request->name
            ]);

            return new TagResource(true, 'Category Berhasil Ditambahkan', $tag);
        } catch (Exception $e) {
            return response->json($e->getMessage(), 500);
        }
    }
}
