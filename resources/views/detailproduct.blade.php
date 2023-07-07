
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TAGFA-TH') }}</title>

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
            max-width:1028px;
            margin:0 auto;
        }
        .products-con{
            display:grid;
            grid-template-columns: repeat(auto-fill,minmax(180px,1fr));
            grid-gap:0.5rem;
        }
        .products-item {
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
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
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Back-End</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <a href="{{route('welcome')}}">
                <div class="flex justify-center">
                    <img src="/logo-nueng1.png" alt="logo" height="100px" width="100px">
                </div>
            </a>

                <!-- start ส่วนเพิ่มเอง-->
                <div class="py-12">
                <div class="container my-2">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card">
                                <div class="card-header text-center text-primary"><strong>ข้อมูลสินค้า</strong></div>
                                <div class="card-body">
                                    <div class="row">
                                        <input type="hidden" name="product_id" value="{{$productDetail->id}}">
                                        <input type="hidden" name="productcover_folder" value="{{$productDetail->productcover_folder}}">
                                        <input type="hidden" name="product_no" value="{{$productDetail->product_no}}">
                                        <input type="hidden" name="user_id" value="{{Auth::User()->id}}">
                                        <label class="text-success d-flex">รหัสสินค้า :  </label><span class="text-primary"> {{$productDetail->product_no}}</span><br/>
                                        <label class="text-success d-flex">ชื่อสินค้า :  </label><span class="text-primary"> {{$productDetail->product_title}}</span><br/>
                                        <label class="text-danger" >รูปปกสินค้า:</label>
                                        <img src="/products_img/{{$productDetail->productcover_folder}}/{{$productDetail->productcover_img}}" id="previewCoverImg" alt="" class="p-2" style="width: 400px;">
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <label class="text-danger p-2" >รูปภาพรายละเอียดสินค้า: </label>
                                    </div>
                                    <div class="container-album">
                                        <div class="products-con">
                                            @foreach($album_data as $key => $value)
                                                <div class="products-item">
                                                    <div class="products-img">
                                                        <a class="image"
                                                        data-fancybox="gallery"
                                                        data-src="/products_img/{{$productDetail->productcover_folder}}/{{$value->album_no}}/{{$value->img_name}}"
                                                    >
                                                        <img src="/products_img/{{$productDetail->productcover_folder}}/{{$value->album_no}}/{{$value->img_name}}" alt="{{$value->img_name}}" />
                                                    </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container ">

                    </div>
            </div>
                <!-- End ส่วนเพิ่มเอง-->
                <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
                </div>
            </div>
        </div>
    <!-- script of fancybox -->
    <script>
      Fancybox.bind('[data-fancybox="gallery"]', {
        //
      });
    </script>
    </body>
</html>

