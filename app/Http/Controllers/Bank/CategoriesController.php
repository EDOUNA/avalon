<?php

namespace App\Http\Controllers\Bank;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Categories::all();
        return view('bank.categories.index', ['categories' => $categories]);
    }
}
