<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
        ]);

        $c = Category::create($request->except('_token'));

        return redirect()->route('categories.index');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$id,
        ]);

        Category::find($id)->update($request->only('name'));

        return redirect()->route('categories.index');
    }

    /**
     *
     * @param $id
     * @return mixed
     */
    public function stocksByCategory($id)
    {
        return Category::with(['stocks' => function($q) {
            $q->with('category');
        }])->where('id', $id)->first()->stocks;
    }
}
