<?php

namespace App\Http\Controllers;


use App\City;
use App\Country;
use App\Group;
use App\Http\Requests\MessageRequest;
use App\Http\Requests\ProductDeletePhotoRequest;
use App\Http\Requests\ShopAddLogoRequest;
use App\Http\Requests\ShopAddPhotoRequest;
use App\Http\Requests\ShopCreateSendPriceRequest;
use App\Http\Requests\ShopDeletePhotoRequest;
use App\Http\Requests\ShopDeleteSendPriceRequest;
use App\Http\Requests\ShopUpdateRequest;
use App\Http\Requests\ShopWorkingTimeRequest;
use App\Message;
use App\Shop;
use App\Subgroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ShopController extends Controller
{

    public function details()
    {
        $shop = auth()->user();

        if ($shop->send_prices == null)
            $sendPrices = [];
        else
            $sendPrices = explode(';', $shop->send_prices);

        return view('shop.details', compact('shop', 'sendPrices'));
    }

    public function edit(Shop $shop)
    {
        $countries = Country::all();
        $cities = City::all();
        $groups = Group::all();
        $subGroups = Subgroup::all();
        $photos = explode(';', $shop->photos);

        if ($shop->send_prices == null)
            $sendPrices = [];
        else
            $sendPrices = explode(';', $shop->send_prices);

        return view('shop.edit', compact('shop', 'countries', 'cities', 'groups', 'subGroups', 'photos', 'sendPrices'));
    }

    public function update(ShopUpdateRequest $request, Shop $shop)
    {
        $shop->updateShop($request);

        return redirect(route('shop.details'));
    }

    public function deletePhoto(ShopDeletePhotoRequest $request, Shop $shop)
    {
        if ($request->type == "photo") {
            deletePhoto($shop, $request);
        }
        if ($request->type == "logo") {
            File::delete($shop->logo);
            $shop->logo = null;
            $shop->save();
        }

        return back();
    }

    public function messages()
    {
        $messages = Message::query()->where('shop_id', auth()->id())->latest()->paginate();

        return view('shop.messages', compact('messages'));
    }

    public function createMessage()
    {
        return view('shop.createMessage');
    }

    public function storeMessage(MessageRequest $request)
    {
        Message::createNew($request->title, $request->message, auth()->id(), null, "shop");

        flash('انجام شد', 'پیام شما با موفقیت ثبت شد');

        return redirect(route('message.index'));
    }

    public function createSendPrice(ShopCreateSendPriceRequest $request, Shop $shop)
    {
        $shop->addSendPrice($request);

        flash('موفق', 'هزینه ارسال جدید با موفقیت ثبت شد');

        return back();
    }

    public function deleteSendPrice(ShopDeleteSendPriceRequest $request, Shop $shop)
    {
        deleteSendPrice($shop, $request);

        return back();
    }

    public function addPhoto(ShopAddPhotoRequest $request, Shop $shop)
    {
        $shop->createPhoto($request);

        flash('موفق', 'عکس جدید با موفقیت اضافه شد');

        return back();

    }

    public function addLogo(ShopAddLogoRequest $request, Shop $shop)
    {

        $shop->logo = $shop->addLogo($request->file("logo"));

        $shop->save();

        flash('موفق', 'لوگوی جدید با موفقیت جایگزین شد');

        return back();
    }

    public function workingHour(ShopWorkingTimeRequest $request, Shop $shop)
    {
        $shop->updateWorkingTime($request);

        flash('موفق', 'ساعت کاری با موفقیت ویرایش شد');

        return back();
    }

    public function updateLatLang(Shop $shop, Request $request)
    {
        $shop->lat = $request->get('lat');
        $shop->lng = $request->get('lng');
        $shop->save();
        flash('موفق', 'مخصتات مورد نظر با موفقیت ذخیره شد');

        return back();

    }

}
