@extends('layouts.admin')

@section('title')
    لیست محصولات
@endsection

@section('users')
    active
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>لیست محصول</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('dashboard')}}">خانه</a>
                </li>
                <li>
                    <a>محصولات</a>
                </li>
                <li class="active">
                    <strong>لیست محصولات</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-11">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5> لیست محصولات</h5>
                    </div>

                    <div class="searchListDiv">
                        <input onkeyup="Search()" class="form-control searchListInput" id="searchInput" type="text"
                               placeholder=" جستجو بر اساس  عنوان ،توضیحات و قیمت" name="data">

                    </div>

                    <div class="ibox-content table-responsive">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان</th>
                                <th>زیر دسته</th>
                                <th>موجودی</th>
                                <th>قیمت</th>
                                <th>وضعیت</th>
                                 <th>وضعیت تایید</th>
                            </tr>
                            </thead>
                            <tbody id="myTable">
                            @foreach($products as $product)
                                <tr>
                                    <td>{{$product->id}}</td>
                                    <td>{{$product->title}}</td>
                                    <td>{{optional($product->shopCategory)->title}}</td>
                                    <td>{{$product->inventory}}</td>
                                    <td>{{$product->price}}</td>
                                    <td>{{$product->status=='on' ? 'غیر فعال' : ' فعال'}}</td>
                                     <td>{{$product->admin_verification=='on' ? 'هنور تایید نشده' : ' تایید شده'}}</td>
                                    <td>
                                        <form class="deleteForm" method="post"
                                              action="{{ route('product.destroy',$product) }}">
                                            {{csrf_field()}}
                                            {{method_field('DELETE')}}
                                            <button class="btn btn-sm btn-danger" type="submit">حذف
                                            </button>
                                        </form>
                                    </td>


                                    <td>
                                        <a class="btn btn-sm btn-info" href="{{ route('product.edit',$product) }}">ویرایش</a>
                                    </td>

                                    <td>
                                        <a class="btn btn-sm btn-primary"
                                           href="{{ route('productComment.index',$product) }}">کامنت ها</a>
                                    </td>

                                    <td>
                                        <a class="btn btn-sm btn-info"
                                           href="{{ route('product.show',$product) }}"> جزئیات</a>
                                    </td>


                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="text-center">
                            {{$products->appends(Request::all())->links()}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>

        function Search() {
            var value = $('#searchInput').val();
            $.get(`/product-search`, {data: value}, function (result) {
                $('#myTable').html(result)
            });
        }
    </script>

@endsection
