@extends('layouts.admin')

@section('title')
    لیست سفارشات
@endsection

@section('users')
    active
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>لیست سفارش</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('dashboard')}}">خانه</a>
                </li>
                <li>
                    {{--                    <a href="{{route('order.index')}}">سفارشات</a>--}}
                </li>
                <li class="active">
                    <strong>لیست سفارشات</strong>
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
                        <h5> لیست سفارشات</h5>
                    </div>
                    <div class="searchListDiv">
                        <input onkeyup="search({{$statusId}})" class="form-control searchListInput" id="searchInput"
                               type="text"
                               placeholder=" جستجو بر اساس  نام کاربری ،آدرس و قیمت" name="data">
                    </div>
                    <div  class="ibox-content table-responsive">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>نام کاربری</th>
                                <th>قیمت کل</th>
                                <th>آدرس</th>
                                <th>زمان ارسال</th>
                                <th>تاریخ ارسال</th>
                                <th>فاکتور ارسال شود</th>
                                <th>وضعیت سفارش</th>
                            </tr>
                            </thead>
                            <tbody id="myTable">
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{$order->id}}</td>
                                    <td>{{$order->user->name}}</td>
                                    <td>{{$order->total_price}}</td>
                                    <td>{{$order->address}}</td>
                                    <td>{{$order->send_time}}</td>
                                    <td>{{$order->send_date}}</td>
                                    <td>{{$order->facture_falg == true ? 'بله' : 'خیر'}}</td>
                                    <td>{{orderStatus($order->order_status)}}</td>

                                    <td>
                                        <a class="btn btn-sm btn-primary"
                                           href="{{ route('order.show',$order) }}">جزییات</a>
                                    </td>

                                    <td>
                                        <a class="btn btn-sm btn-info"
                                           href="{{ route('order.edit',$order) }}">ویرایش</a>
                                    </td>


                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="text-center">
                            {{$orders->appends(Request::all())->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        function search($statusId) {
            var value = $('#searchInput').val();
            $.get(`/order-search`, {data: value,status_id:$statusId}, function (result) {
                $('#myTable').html(result)
            });
        }
    </script>

@endsection
