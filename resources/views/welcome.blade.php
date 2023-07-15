<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TAGFA-TH') }}</title>
        <link rel="icon" type="image/jpg" sizes="20x20" href="/TAGFA_icon.jpg"/>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts CDN Bootstrap 5 -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <!-- Scripts CDN Bootstrap 5 -->

        <!-- fancybox Gallery-->
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"
        />
        <!-- End fancybox Gallery-->

        <!-- Styles -->
        @livewireStyles
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <!-- Gallery script -->
        <style >
        *{
            margin:0;
            padding:0;
            box-sizing: border-box;
        }
        .container-album {
            max-width:1190px;
            margin:0 auto;
        }
        .container-albumSponsor{
            max-width:500px;
            margin:0 auto;
        }
        .products-con{
            display:grid;
            grid-template-columns: repeat(auto-fill,minmax(200px,1fr));
            grid-gap:0.5rem;
        }
        .sponsor-con{
            display:grid;
            grid-template-columns: repeat(auto-fill,minmax(100px,1fr));
            grid-gap:0.5rem;
        }
        .products-item {
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
            transition:0.3s;
        }
        .sponsor-item {
            transition:0.3s;
        }
        .products-item:hover {
            border: 1px solid orange;
        }
        .products-detail {
            padding:0.5rem;
        }
        .products-img img{
            width:100%;
        }
        .sponsor-img{
            width:80%;
        }
        .products-price {
            padding: 1rem;
            align-items: center;
            justify-content: space-between;
        }
        .products-left span{
            font-size: 6px;
        }
        .products-right span{
            font-size: 6px;
        }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 selection:bg-red-500 selection:text-white">
        @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">BackEnd</a>
                    @endauth
                </div>
            @endif

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <a href="{{route('welcome')}}">
                <div class="flex justify-center">
                    <img src="/TAGFA-LOGO.png" alt="logo" height="100%" width="1190px">
                </div>
            </a>
                <!-- start ส่วนเพิ่มเอง-->
                <!-- start Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{route('welcome')}}">หน้าแรก</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="#">เกี่ยวกับสมาคม</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="{{url('https://web.facebook.com/thetagfa?_rdc=1&_rdr')}}">ติดต่อสมาคม</a>
                            </li>
                        </ul>
                        </div>
                    </div>
                    </nav>
                <!-- end Navbar -->
                    <div class="container mt-4">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header text-center text-primary"><strong>ตารางรายการข้อมูลสินค้า</strong></div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                        <!-- แบบ Gallery -->
                                        <div class="container-album">
                                            <div class="products-con">
                                                @foreach($productlist as $key => $value)
                                                    <div class="products-item">
                                                        <div class="products-img p-2">
                                                            <a class="image"
                                                            data-fancybox="gallery"
                                                            data-src="/products_img/{{$value->productcover_folder}}/{{$value->productcover_img}}"
                                                            href="{{url('/detailsProduct/'.$value->id)}}"
                                                            >
                                                            <img src="/products_img/{{$value->productcover_folder}}/{{$value->productcover_img}}" alt="" />
                                                        </a>
                                                        </div>
                                                        <div class="products-price">
                                                            <div class="products-left text-primary">
                                                                ชื่อ: {{$value->product_title}} <br/>
                                                            </div >
                                                            <div class="products-right text-warning">
                                                                ราคา: {{number_format($value->product_price,2,'.',',')}} บาท
                                                            </div >
                                                        </div >
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <!-- end แบบ Gallerry -->
                                            <br />
                                                <div>
                                                    {{$productlist->onEachSide(1)->links()}} <!-- คำสั่งแสดงปุ่นกดไปแต่ละหน้า โดยหากมีจำนวนหน้ามากจะแบ่งย่อให้ดูง่ายขึ้น -->
                                                </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex bordered">
                                <div class="card">
                                    <div class="card-header text-center text-primary"><strong>ผู้สนับสนุน</strong></div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                        <!-- แบบ Gallery sponsors -->
                                        <div class="container-albumSponsor">
                                            <div class="sponsor-con">
                                                @foreach($sponsors as $key => $value)
                                                    <div class="sponsor-item">
                                                        <div class="products-img p-2">
                                                            <a class="image"
                                                            data-fancybox="gallery"
                                                            data-src="/products_img/{{$value->sponsor_img}}"
                                                            href="{{$value->sponsor_link}}"
                                                            >
                                                            <img src="/sponsors_img/{{$value->sponsor_img}}" alt="" />
                                                        </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <!-- end แบบ Gallerry sponsors -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                <!-- End ส่วนเพิ่มเอง-->
                <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
                </div>
            </div>
        </div>
    </body>
</html>
