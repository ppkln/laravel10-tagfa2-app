<x-app-layout>
    <x-slot name="header">
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
                        <div class="card-header text-center text-primary">แบบฟอร์มปรับปรุงข้อมูลสินค้า</div>
                        <div class="card-body">
                            <form action="{{url('/products/update/'.$productEdit->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="product_title">ชื่อหัวข้อสินค้า: </label><span class="text-warning"> (ต้องระบุ)</span>
                                    <input type="text" class="form-control" name="product_title" value="{{$productEdit->product_title}}">
                                </div>
                                @error('product_title')
                                    <span class="text-danger py-2">{{$message}}</span>
                                @enderror
                                <div class="form-group">
                                    <label for="product_unit">หน่วยนับสินค้า:</label><span class="text-warning"> (ต้องระบุ)</span>
                                    <input type="text" class="form-control" name="product_unit" value="{{$productEdit->product_unit}}">
                                </div>
                                @error('product_unit')
                                    <span class="text-danger py-2">{{$message}}</span>
                                @enderror
                                <div class="form-group">
                                    <label for="product_price">ราคาสินค้า:</label><span class="text-warning"> (ต้องระบุ)</span>
                                    <input type="number" class="form-control" name="product_price" min="0.0001" step="0.0001" value="{{$productEdit->product_price}}"><!-- กำหนดให้กรอกได้เฉพาะตัวเลขจำนวนเต็ม หรือทศนิยม -->
                                </div>
                                @error('product_price')
                                    <span class="text-danger py-2">{{$message}}</span>
                                @enderror
                                <div class="form-group">
                                    <label for="product_description">รายละเอียดสินค้า:</label><span class="text-warning"> (ต้องระบุ)</span>
                                    <textarea class="form-control" name="product_description" >{{$productEdit->product_description}}</textarea>
                                </div>
                                @error('product_description')
                                    <span class="text-danger py-2">{{$message}}</span>
                                @enderror
                                <div class="form-group">
                                    <input type="hidden" name="Old_img" value="{{$productEdit->productcover_img}}">
                                    <input type="hidden" name="productcover_folder" value="{{$productEdit->productcover_folder}}">
                                    <label for="productcover_img">แก้ไขรูปปกสินค้า:</label><span class="text-warning"> (เฉพาะไฟล์นามสกุล jpg,jpeg,png เท่านั้น และขนาดไฟล์ไม่เกิน 2 MB.) </span>
                                    <input type="file" class="form-control" id="productcover_img" name="productcover_img" accept="image/jpeg,image/jpg,image/png">
                                    <img src="/products_img/{{$productEdit->productcover_folder}}/{{$productEdit->productcover_img}}" id="previewImg" alt="" class="mt-2" width="300px">
                                </div>
                                @error('productcover_img')
                                    <span class="text-danger py-2">{{$message}}</span>
                                @enderror
                                <input type="hidden" name="user_id" value="{{Auth::User()->id}}">
                                <br/>
                                <div>
                                    <span class="text-primary py-2">สถานะแสดงผล: </span>
                                    @if($productEdit->publish_status == 1)
                                        <label for="publish_status" class="text-success">แสดงผล  <input class="form-check-input" type="checkbox" name="publish_status" checked></label>
                                    @else
                                    <label for="publish_status"  class="text-danger">ยังไม่แสดงผล  <input class="form-check-input" type="checkbox" name="publish_status"></label>
                                    @endif
                                </div>
                                <div class="text-center">
                                    <input type="submit" class="btn btn-primary" value="ปรับปรุงข้อมูล">
                                </div>
                                <br />
                                <a href="{{url('/albumproducts/album/'.$productEdit->id)}}" class="btn btn-success" >จัดการรูปภาพรายละเอียดสินค้า</a>

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
