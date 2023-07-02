<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            สวัสดีคุณ {{Auth::User()->name}} ระดับการทำงาน: {{Auth::User()->lv_working}}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="container my-2">
            <div class="row">
                <div class="col-md-10">
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
                        <div class="card-header text-center text-primary"><strong>แบบฟอร์มจัดการรูปภาพสินค้า</strong></div>
                        <div class="card-body">
                            <form action="{{route('albumAddImg')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="product_id" value="{{$product_album->id}}">
                                    <input type="hidden" name="productcover_folder" value="{{$product_album->productcover_folder}}">
                                    <input type="hidden" name="product_no" value="{{$product_album->product_no}}">
                                    <input type="hidden" name="user_id" value="{{Auth::User()->id}}">
                                    <label class="text-success">รหัสสินค้า :  </label><span class="text-primary"> {{$product_album->product_no}}</span><br/>
                                    <label class="text-success">ชื่อสินค้า :  </label><span class="text-primary"> {{$product_album->product_title}}</span><br/>
                                    <label class="text-danger" >รูปปกสินค้า:</label>
                                    <img src="/products_img/{{$product_album->productcover_folder}}/{{$product_album->productcover_img}}" id="previewCoverImg" alt="" class="p-2" style="width: 400px;">
                                    <hr>
                                </div>
                                <div class="row">
                                    <label class="text-danger p-2" >รูปภาพรายละเอียดสินค้า:</label><br/>
                                        @foreach($album_data as $key => $value)<!-- แสดงรูปภาพแต่ละภาพ -->
                                            <!-- Gallery-->
                                            <div class="img p-2"><!-- class img นี้ได้เขียนคำสั่ง script ไว้ที่หน้า app.blade.php -->
                                                <a target="_blank" href="/products_img/{{$product_album->productcover_folder}}/{{$value->album_no}}/{{$value->img_name}}">
                                                    <img src="/products_img/{{$product_album->productcover_folder}}/{{$value->album_no}}/{{$value->img_name}}" alt="#"  >
                                                </a>
                                                <div class="desc">เพิ่มรูปเมื่อ: {{$value->created_at}}</div><!-- class desc นี้ได้เขียนคำสั่ง script ไว้ที่หน้า app.blade.php -->
                                            </div>
                                            <!-- End Gallery-->
                                        @endforeach
                                </div>
                                <br/>
                                <hr>
                                <div class="row">
                                    <div class="mt-2 p-2">
                                        <label class="mt-2 text-primary">เพิ่มรูป:<span class="text-warning"> (รองรับเฉพาะไฟล์นามสกุล jpg,jpeg,png เท่านั้น และไฟล์ขนาดไม่เกินไฟล์ละ 2 MB) </span>
                                            <input type="file" class="form-control my-2" name="album_img[]" accept="image/jpeg,image/jpg,image/png" multiple="multiple">
                                        </label>

                                    </div>
                                    @error('album_img')
                                        <span class="text-danger py-2">{{$message}}</span>
                                    @enderror
                                    <br/>
                                </div>
                                <br/>
                                <div class=" text-center p-3 my-3">
                                    <input type="submit" class="btn btn-primary navbar-btn" value="อัพโหลด">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

