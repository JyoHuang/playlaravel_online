<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">


    <link href="{{ asset('css/ecpay_demopage.css') }}" rel="stylesheet">
    <title>玩轉ecpay!</title>
</head>

<body>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous">
    </script>

    <div class="demopage_flex">
        

        <div class="demopage_topbar py-2 px-2 bg-light shadow">
            <img src="/img/ecpay.png" alt="" height="50">
        </div>

        <div class="demopage_belowtopbar">
            <div class="demopage_sidebar px-3 py-3 shadow-sm col-md-2">
                <button type="button" class="btn btn-success demopage_sidebar_button" onclick="javascript:location='enterpage';">前往付款</button>
                <button type="button" class="btn btn-success demopage_sidebar_button mt-3" onclick="javascript:location='billlistpage';">帳單明細</button>
            </div>
            <div class="demopage_main mt-2 col-md-10" style="display:flex">
                @yield('content')
            </div>
        </div>
        
    </div>
</body>

</html>
