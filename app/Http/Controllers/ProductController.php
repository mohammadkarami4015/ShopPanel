<?php

namespace App\Http\Controllers;


use App\Http\Requests\ProductAddFeaturesRequest;
use App\Http\Requests\ProductAddPhotoRequest;
use App\Http\Requests\ProductDeleteFeatureRequest;
use App\Http\Requests\ProductDeletePhotoRequest;
use App\Http\Requests\ProductRequest;
use App\Product;
use App\Shop;
use App\ShopCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $shop = auth()->user();

        $products = $shop->products()->latest()->paginate(20);

        return view('product.index', compact('products'));
    }

    public function search(Request $request)
    {

        $products = Product::search(auth()->id(), $request->data)->latest()->get();

        return view('product.searchResult', compact('products'));
    }

    public function show(Product $product)
    {
        if ($product->features == null)
            $features = [];
        else
            $features = explode(';', $product->features);

        return view('product.show', compact('product', 'features'));
    }

    public function create()
    {
        $shopCategories = ShopCategory::query()->where('shop_id', auth()->id())->get();
        return view('product.create', compact('shopCategories'));
    }

    public function store(ProductRequest $request)
    {
     
        $shop_products = Product::query()->where('shop_id', auth()->id())->get();

        if (count($shop_products) >= 50) {
            return back()->withErrors('تعداد محصولات شما بیشتر از 50 می باشد');
        }
       
            
        $product = Product::createNew($request);


        if ($request->file("photos"))
            $product->createPhoto($request->file("photos"));

        return redirect(route('product.index'));
    }

    public function edit(Product $product)
    {
        $photos = explode(';', $product->photos);

        $shopCategories = ShopCategory::query()->where('shop_id', auth()->id())->get();

        if ($product->features == null)
            $features = [];
        else
            $features = explode(';', $product->features);

        return view('product.edit', compact('product', 'shopCategories', 'photos', 'features'));

    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->except(['photos', 'admin_verification']));

        return redirect(route('product.index'));
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect(route('product.index'));
    }

    public function deletePhoto(ProductDeletePhotoRequest $request, Product $product)
    {
        deletePhoto($product, $request);

        return back();
    }

    public function addFeature(ProductAddFeaturesRequest $request, Product $product)
    {
        $product->addFeatures($request);

        flash('موفق', 'ویژگی جدید با موفقیت ثبت شد');

        return back();
    }

    public function deleteFeature(ProductDeleteFeatureRequest $request, Product $product)
    {
        $product->deleteFeatures($request);

        return back();
    }

    public function addPhoto(ProductAddPhotoRequest $request,Product $product )
    {

        $product->createPhoto($request->file('photos'));

        flash('موفق', 'با موفقیت انجام شد');
        return back();
    }
}
