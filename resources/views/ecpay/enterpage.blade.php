@extends('ecpay.demopage')

@section('content')
    <div class="ecpay_enterpage_main mt-2"
        style="display: flex; flex-direction: column; justify-content: center; align-items: center; flex-grow:1">

        <form class="col-md-4" action="topay" method="POST">
            {{ csrf_field() }}
            <div class="mb-3">
                <label for="name" class="form-label">名字</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email信箱</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="product_name" class="form-label">品項名稱</label>
                <input type="text" class="form-control" id="product_name" name="product_name">
            </div>
            <div class="mb-3">
                <label for="product_price" class="form-label">價格</label>
                <input type="text" class="form-control" id="product_price" name="product_price">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

    </div>
@endsection
