<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function search(Request $request){
        $categories = Category::where('name','like', '%' . $request->q . '%')->get();
        return response()->json([
            'result' => $categories
        ]);
    }
}
