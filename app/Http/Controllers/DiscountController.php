<?php

namespace App\Http\Controllers;

use App\Discount;
use App\Http\Requests\DiscountRequest;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {

        $discounts = Discount::query()->where('shop_id', auth()->id())->latest()->paginate(20);

        return view('discount.index', compact('discounts'));
    }

    public function search(Request $request)
    {
        $discounts = Discount::search(auth()->id(), $request->data)->latest()->paginate(20);

        return view('discount.searchResult', compact('discounts'));
    }

    public function create()
    {
        $shop = auth()->user();

        $products = $shop->products;

        $categories = $shop->shopCategories;

        return view('discount.create', compact('products', 'categories'));

    }

    public function store(DiscountRequest $request)
    {
        Discount::createNew($request);

        return redirect(route('discount.index'));
    }

    public function show(Discount $discount)
    {
        $products = explode(',', $discount->products);
        $categories = explode(',', $discount->categories);
        $exceptProducts = explode(',', $discount->except_products);
        $exceptCategories = explode(',', $discount->except_categories);

        return view('discount.show',
            compact(
                'discount',
                'products',
                'exceptProducts',
                'categories',
                'exceptCategories'
            ));
    }

    public function edit(Discount $discount)
    {
        $shop = auth()->user();

        $discountProducts = explode(',', $discount->products);
        $discountCategories = explode(',', $discount->categories);
        $discountExceptProducts = explode(',', $discount->except_products);
        $discountExceptCategories = explode(',', $discount->except_categories);

        $products = $shop->products;

        $categories = $shop->shopCategories;

        return view('discount.edit',
            compact('discount',
                'products',
                'categories',
                'discountProducts',
                'discountExceptProducts',
                'discountCategories',
                'discountExceptCategories'
            ));
    }

    public function update(DiscountRequest $request, Discount $discount)
    {
        $discount->updateDiscount($request);

        if ($request->expire) {
            $discount->updateExpire($request->get('expire'));
        }
        flash('success');
        return back();
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();

        return redirect(route('discount.index'));
    }

}
