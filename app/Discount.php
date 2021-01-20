<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use phpDocumentor\Reflection\Types\This;

class Discount extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function discountUsage()
    {
        return $this->hasMany(DiscountUsage::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public static function createNew(Request $request)
    {

        Discount::query()->create([
            'shop_id' => auth()->id(),
            'code' => self::generateRandomString(),
            'title' => $request->get('title'),
            'type' => $request->get('type'),
            'percent' => $request->get('percent'),
            'max_discount_price' => $request->get('max_discount_price'),
            'number_of_usage' => $request->get('number_of_usage'),
            'number_of_usage_for_user' => $request->get('number_of_usage_for_user'),
            'number_of_user' => $request->get('number_of_user'),
            'expire' => $request->get('expire'),
            'products' => implode(',', $request->get('products', [])),
            'except_products' => implode(',', $request->get('except_products', [])),
            'categories' => implode(',', $request->get('categories', [])),
            'except_categories' => implode(',', $request->get('except_categories', [])),
        ]);
    }

    public function updateExpire($expire)
    {
        $this->expire = $expire;
        $this->save();
    }

    public static function generateRandomString($length = 20)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $charactersLength = strlen($characters);

        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $discount_code = Discount::query()->where("code", $randomString)->first();

        if (!$discount_code) {
            return $randomString;
        } else {
            return self::generateRandomString(6);
        }
    }

    public function updateDiscount($request)
    {
        $this->update(array_merge([
            'products' => implode(',', $request->get('products', [])),
            'except_products' => implode(',', $request->get('except_products', [])),
            'categories' => implode(',', $request->get('categories', [])),
            'except_categories' => implode(',', $request->get('except_categories', [])),
        ], $request->only([
            'title',
            'type',
            'percent',
            'max_discount_price',
            'number_of_usage',
            'number_of_usage_for_user',
            'number_of_user',
        ])));
    }

  public static function scopeSearch(Builder $query, $shopId, $data)
    {
        return $query->where('shop_id', $shopId)->where(function ($query) use ($data) {
           return $query->Where('title', 'like', '%' . $data . '%')
                ->orWhere('type', 'like', '%' . $data . '%')
                ->orWhere('percent', 'like', '%' . $data . '%');
        });
    }

}

