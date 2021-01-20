@extends('layouts.admin')

@section('title')
    لیست کدهای تخفیف
@endsection

@section('users')
    active
@endsection

@section('content')
    <div class="row wrapper border white-bg page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('dashboard')}}">خانه</a>
                </li>
                <li>
                    <a href="{{route('discount.index')}}">کدهای تخفیف</a>
                </li>
                <li class="active">
                    <strong>لیست کدهای تخفیف</strong>
                </li>
            </ol>
        </div>

    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-11">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5> لیست کدهای تخفیف</h5>
                    </div>
                    <div class="searchListDiv">
                        <input onkeyup="Search()" class="form-control searchListInput" id="searchInput" type="text"
                               placeholder=" جستجو بر اساس  عنوان ،نوع و درصد" name="data">

                    </div>
                    <div  class="ibox-content table-responsive">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان</th>
                                <th>تاریخ اعتبار</th>
                                <th>درصد تخفیف</th>
                            </tr>
                            </thead>
                            <tbody id="myTable">
                            @foreach($discounts as $discount)
                                <tr>
                                    <td>{{$discount->id}}</td>
                                    <td>{{$discount->title}}</td>
                                    <td>{{$discount->expire}}</td>
                                    <td>{{$discount->percent}}</td>

                                    <td>
                                        <a class="btn btn-sm btn-primary"
                                           href="{{ route('discount.show',$discount) }}">جزییات</a>
                                    </td>

                                    <td>
                                        <a class="btn btn-sm btn-info"
                                           href="{{ route('discount.edit',$discount) }}">ویرایش</a>
                                    </td>

                                    <td>
                                        <form class="deleteForm" method="post"
                                              action="{{ route('discount.destroy',$discount) }}">
                                            {{csrf_field()}}
                                            {{method_field('DELETE')}}
                                            <button class="btn btn-sm btn-danger" type="submit">حذف
                                            </button>
                                        </form>
                                    </td>


                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="text-center">
                            {{$discounts->appends(Request::all())->links()}}
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
        $.get(`/discount-search`, {data: value}, function (result) {
            $('#myTable').html(result)
        });
    }
</script>
@endsection
