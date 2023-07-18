<style>
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
<x-app-layout>
    <x-slot name="header">
        <button onclick="topFunction()" id="myBtn" title="Go to top">Top</button><!-- ปุ่ม top up-->
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            สวัสดีคุณ {{Auth::User()->name}} ระดับการทำงาน: {{Auth::User()->lv_working}}
            @if (Auth::User()->lv_working < 3) <!-- ตรวจสอบว่าผู้ที่ login มีสิทธิ์ใช้งานระดับไหน -->
                ระดับการทำงาน: {{Auth::User()->lv_working}}
                {{header( "location: /dashboard" );}}
                {{exit(0);}}
            @endif
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="container my-2">
            <div class="row">
                <div class="col-lg-10">
                @if($message = Session::get('success'))
                    <div class="alert alert-success">
                        <span class="text-success py-2">{{$message}}</span>
                    </div>
                @elseif($message = Session::get('unsuccess'))
                    <div class="alert alert-danger">
                        <span class=" py-2">{{$message}}</span>
                    </div>
                @endif
                    <div class="card">
                        <div class="card-header text-center text-primary"><strong>แบบฟอร์มจัดการรูปภาพโพส</strong></div>
                        <div class="card-body">
                            <form action="{{route('albumPostAddImg')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                <div class=" text-left p-1">
                                    <a href="{{url('/posts/editNo/'.$post_album->post_no)}}" class="btn btn-sm navbar-btn btn-warning" ><< ย้อนกลับปรับปรุงข้อมูลโพส</a>
                                </div>
                                    <input type="hidden" name="post_id" value="{{$post_album->id}}">
                                    <input type="hidden" name="postcover_folder" value="{{$post_album->postcover_folder}}">
                                    <input type="hidden" name="post_no" value="{{$post_album->post_no}}">
                                    <input type="hidden" name="user_id" value="{{Auth::User()->id}}">
                                    <label class="text-success">รหัสโพส :  </label><span class="text-primary"> {{$post_album->post_no}}</span><br/>
                                    <label class="text-success">ชื่อโพส :  </label><span class="text-primary"> {{$post_album->post_title}}</span><br/>
                                    <label class="text-danger" >รูปปกโพส:</label>
                                    <img src="/posts_img/{{$post_album->postcover_folder}}/{{$post_album->postcover_img}}" id="previewCoverImg" alt="" class="p-2" style="width: 400px;">
                                    <div class="row">
                                        <div class="mt-2 p-2">
                                            <label class="mt-2 text-primary">เพิ่มรูป: <span class="text-warning"> (รองรับเฉพาะไฟล์นามสกุล jpg,jpeg,png เท่านั้น และไฟล์ขนาดไม่เกินไฟล์ละ 2 MB) </span>
                                                <input type="file" class="form-control my-2" name="album_img[]" accept="image/jpeg,image/jpg,image/png" multiple="multiple">
                                            </label>
                                        </div>
                                        @error('album_img')
                                            <span class="text-danger py-2">{{$message}}</span>
                                        @enderror
                                        <br/>
                                    </div>
                                </div>
                                <div class=" text-center p-3 my-3">
                                    <input type="submit" class="btn btn-primary navbar-btn" value="อัพโหลด">
                                </div>
                            </form>
                            <hr>
                            <div class="row">
                                <label class="text-danger p-2" >รูปภาพรายละเอียดโพส: </label>
                            </div>
                            <div class="container-album">
                                <div class="posts-con">
                                    @foreach($album_data as $key => $value)
                                        <div class="posts-item p-2">
                                            <div class="posts-img">
                                                <a class="image"
                                                data-fancybox="gallery"
                                                data-src="/posts_img/{{$post_album->postcover_folder}}/{{$value->album_no}}/{{$value->img_name}}"
                                            >
                                                <img src="/posts_img/{{$post_album->postcover_folder}}/{{$value->album_no}}/{{$value->img_name}}" alt="{{$value->img_name}}"/>
                                            </a>
                                            </div>

                                            <div class="posts-price d-flex">
                                                <div class="posts-left text-primary">
                                                    วันที่บันทึก:
                                                </div >
                                                <div class="posts-right">
                                                    {{$value->created_at}}
                                                </div >
                                            </div >
                                            <div class="posts-detail">
                                                <a href="{{url('/albumposts/deleteImg/'.$value->id)}}" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการลบข้อมูลนี้!!')">ลบ</a>
                                            </div >
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
    <!-- script of fancybox -->
    <script>
      Fancybox.bind('[data-fancybox="gallery"]', {
        //
      });
    </script>

    <!-- ส่วนของปุ่ม on top -->
    <script>
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
</x-app-layout>

