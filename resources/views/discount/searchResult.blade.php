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
