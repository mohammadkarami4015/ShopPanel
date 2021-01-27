<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderUpdateRequest;
use App\Order;
use App\Product;
use App\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $statusId = $request->get('status');

        $orders = Order::query()->where('shop_id', auth()->id())->where('order_status', $statusId)->latest()->paginate();

        return view('order.index', compact('orders', 'statusId'));
    }


    public function search(Request $request)
    {
        $orders = Order::search($request->status_id, auth()->id(), $request->data)->latest()->paginate(20);

        $statusId = $request->status_id;

        return view('order.searchResult', compact('orders', 'statusId'));
    }

    public function show(Order $order)
    {
        $productsId = [];
        $productsCount = [];

        $items = explode(";", $order->products);

        foreach ($items as $item) {
            $products = explode(",", $item);

            array_push($productsId, $products[0]);

            array_push($productsCount, $products[1]);
        }

        $orderProducts = Product::query()->whereIn('id', $productsId)->get();

        foreach ($orderProducts as $key => $value) {
            $value->count = $productsCount[$key];
        }

        return view('order.show', compact('order', 'orderProducts'));
    }

    public function edit(Order $order)
    {
        return view('order.edit', compact('order'));
    }

    public function update(OrderUpdateRequest $request, Order $order)
    {
        $order->update($request->only(['order_status', 'send_price', 'address']));

        $order->payed_price = $order->total_price + $order->send_price;

        $order->save();

        $user = User::query()->where('id', $order->user_id)->first();

        if ($user && $user->notification_token !== null) {
            $keys_values = [
                "title" => "الفباکالا",
                "body" => getNotificationBody($request->get('order_status')),
            ];
            sendFCM('الفباکالا', $keys_values, $user->notification_token);
        }

        flash('success', 'انجام شد');

        return back();
    }
}
