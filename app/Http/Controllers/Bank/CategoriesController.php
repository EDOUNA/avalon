<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Bank\Categories;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Categories::all();
        return view('bank.categories.index', ['categories' => $categories]);
    }

    /**
     * @return array
     */
    public function listCategories(): object
    {
        return Categories::where('active', 1)->get();
    }
}
