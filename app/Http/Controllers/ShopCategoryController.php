<?php

namespace App\Http\Controllers;


use App\Http\Requests\ShopCategoryRequest;
use App\ShopCategory;
use Illuminate\Http\Request;

class ShopCategoryController extends Controller
{
    public function index()
    {
        $shopCategories = ShopCategory::query()
            ->where('shop_id', auth()->id())
            ->where('status', 'on')
            ->latest()
            ->paginate(20);

        return view('shopCategory.index', compact('shopCategories'));
    }

    public function search(Request $request)
    {
        $shopCategories = ShopCategory::search(auth()->id(), $request->data)->where('status','on')->latest()->get();

        return view('shopCategory.searchResult', compact('shopCategories'));
    }

    public function create()
    {
        return view('shopCategory.create');
    }

    public function store(ShopCategoryRequest $request)
    {
        ShopCategory::createNew($request);

        return redirect(route('shopCategory.index'));
    }

    public function edit(ShopCategory $shopCategory)
    {
        return view('shopCategory.edit', compact('shopCategory'));
    }

    public function update(ShopCategoryRequest $request, ShopCategory $shopCategory)
    {
        $shopCategory->update($request->validated());

        return redirect(route('shopCategory.index'));
    }

    public function destroy(ShopCategory $shopCategory)
    {
        $shopCategory->delete();

        return redirect(route('shopCategory.index'));
    }

}
