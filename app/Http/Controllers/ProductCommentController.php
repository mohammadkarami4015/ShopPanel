<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentReplyRequest;
use App\Product;
use App\ProductComment;

class ProductCommentController extends Controller
{
    public function index(Product $product)
    {
        $productComments = ProductComment::query()
            ->where('shop_id', auth()->id())
            ->where('product_id', $product->id)
            ->where('replay_flag', '0')
            ->with('child')
            ->latest()->paginate(20);

        return view('productComment.index', compact('productComments', 'product'));
    }

    public function create(Product $product, ProductComment $productComment)
    {
        if ($productComment->replay_flag == 0 && !$productComment->child)
            return view('productComment.create', compact('productComment', 'product'));
        else
            return back()->withErrors('امکانپذیر نیست');
    }

    public function reply(CommentReplyRequest $request, Product $product, ProductComment $productComment)
    {
        if ($productComment->replay_flag == 0 && !$productComment->child) {
            ProductComment::createNew($productComment, $request->get('message'));
        } else {
            return back()->withErrors('امکانپذیر نیست');
        }

        return redirect(route('productComment.index', $product));
    }

    public function destroy(Product $product, ProductComment $productComment)
    {
        if ($productComment->replay_flag != 0) {
            $productComment->delete();
            return back();
        }else
        {
            flash('error');
            return back();
        }
    }

    public function verify($product, $id, $value)
    {
        $comment = ProductComment::query()->find($id);

        $comment->update(['admin_verification' => $value]);
    }
}
