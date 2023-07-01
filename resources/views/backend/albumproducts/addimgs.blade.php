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
                        <div class="card-header text-center text-primary">แบบฟอร์มเพิ่มรูปภาพสินค้า</div>
                        <div class="card-body">
                            <form action="{{route('albumAddImg')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" name="product_id" value="{{$product_album->id}}">
                                    <input type="hidden" name="productcover_folder" value="{{$product_album->productcover_folder}}">
                                    <input type="hidden" name="product_no" value="{{$product_album->product_no}}">
                                    <input type="hidden" name="user_id" value="{{Auth::User()->id}}">
                                    <label class="text-success">รหัสสินค้า :  </label><span class="text-primary"> {{$product_album->product_no}}</span><br/>
                                    <label class="text-success">ชื่อสินค้า :  </label><span class="text-primary"> {{$product_album->product_title}}</span><br/>
                                    <label class="text-danger" >รูปปกสินค้า:</label>
                                    <img src="/products_img/{{$product_album->productcover_folder}}/{{$product_album->productcover_img}}" id="previewCoverImg" alt="" class="mt-2" width="300px">
                                    <input type="file" class="form-control my-2" name="album_img[]" accept="image/jpeg,image/jpg,image/png" multiple="multiple">
                                    <span class="text-warning"> (รองรับเฉพาะไฟล์นามสกุล jpg,jpeg,png เท่านั้น และไฟล์ขนาดไม่เกินไฟล์ละ 2 MB) </span>
                                </div>
                                @error('album_img')
                                    <span class="text-danger py-2">{{$message}}</span>
                                @enderror
                                <br/>
                                <div class="text-center">
                                    <input type="submit" class="btn btn-primary" value="อัพโหลด">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<!-- ชุดคำสั่งสำหรับ preview ภาพที่ผู้ใช้ได้เลือก และเปลี่ยนรูปภาพที่เลือกใหม่ -->
<script>
    let imageInput = document.getElementById('productcover_img');
    let previewImg = document.getElementById('previewImg');
    imageInput.onchange = evt =>{
        const [file] = imageInput.files;
        if(file){
            previewImg.src = URL.createObjectURL(file);
        }
    }
</script>
