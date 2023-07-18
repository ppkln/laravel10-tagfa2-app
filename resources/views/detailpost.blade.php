
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
            max-width:1028px;
            margin:0 auto;
        }
        .posts-con{
            display:grid;
            grid-template-columns: repeat(auto-fill,minmax(180px,1fr));
            grid-gap:0.5rem;
        }
        .posts-item {
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
            transition:0.3s;
        }
        .posts-item:hover {
            border: 1px solid orange;
        }
        .posts-detail {
            padding:0.5rem;
        }
        .posts-img img{
            width:100%;
        }
        .posts-price {
            padding: 1rem;
            align-items: center;
            justify-content: space-between;
        }
        .posts-left span{
            font-size: 6px;
        }
        .posts-right span{
            font-size: 6px;
        }
        .img_container img{
            height: auto;
            margin: auto auto;
            display: block;
            }

            #myBtn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 30px;
            z-index: 99;
            font-size: 18px;
            border: none;
            outline: none;
            background-color: navy;
            color: white;
            cursor: pointer;
            padding: 15px;
            border-radius: 4px;
            }
            #myBtn:hover {
            background-color: #345;
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
            <a href="{{route('home')}}">
                <div class="flex justify-center">
                    <img src="/TAGFA-LOGO.png" alt="logo" height="100%" width="100%">
                </div>
            </a>

                <!-- start ส่วนเพิ่มเอง-->
                <button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>
                <!-- start Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{route('home')}}">หน้าแรก</a>
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
                <div class="py-12">
                <div class="container my-2">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card">
                                <div class="card-header text-center text-primary"><strong>ข้อมูลโพส</strong></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="img_container">
                                            <img src="/posts_img/{{$postDetail->postcover_folder}}/{{$postDetail->postcover_img}}" id="previewCoverImg" alt="" class="p-2 item-center" style="width: 400px;">
                                        </div>
                                        <div class="d-flex">
                                            <label class="text-success d-flex p-2 float-right"><strong>ชื่อ: </strong> </label><span class="text-primary p-2"> {{$postDetail->post_title}}</span>
                                            <label class="text-success d-flex p-2 float-right"><strong>วันที่โพส: </strong> </label><span class="text-primary p-2"> {{$postDetail->created_at}}</span>
                                        </div>
                                        <label class="text-success p-2 "><strong>รายละเอียดโพส :  </strong></label><span class="text-primary p-2">{{$postDetail->post_description}}</span><br/>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <label class="text-danger p-2" >รูปภาพรายละเอียดโพส: </label>
                                    </div>
                                    <div class="container-album">
                                        <div class="posts-con">
                                            @foreach($album_data as $key => $value)
                                                <div class="posts-item">
                                                    <div class="posts-img">
                                                        <a class="image"
                                                        data-fancybox="gallery"
                                                        data-src="/posts_img/{{$postDetail->postcover_folder}}/{{$value->album_no}}/{{$value->img_name}}"
                                                    >
                                                        <img src="/posts_img/{{$postDetail->postcover_folder}}/{{$value->album_no}}/{{$value->img_name}}" alt="{{$value->img_name}}" />
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

    <script>
        // ปุ่น on top
        let mybutton = document.getElementById("myBtn");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {scrollFunction()};

        function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
        }
    </script>
    </body>
</html>

