<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            สวัสดีคุณ {{Auth::User()->name}}
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
                <div class="col-md-8">
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
                        <div class="card-header text-center text-primary">ตารางรายการข้อมูลสินค้า</div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-striped table-bordered text-center " style="width:100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">ลำดับ</th>
                                                <th style="width:15%">รหัสสินค้า</th>
                                                <th scope="col">ชื่อหัวข้อสินค้า</th>
                                                <th scope="col">รายละเอียดสินค้า</th>
                                                <th scope="col">รูปปกสินค้า</th>
                                                <th scope="col">ผู้ปรับปรุงข้อมูล</th>
                                                <th scope="col">สถานะแสดงผล</th>
                                                <th scope="col">การดำเนินการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($productlist as $row)
                                                    <tr>
                                                        <th scope="row">{{$productlist->firstItem()+$loop->index}}</th>
                                                        <td><a href="{{url('/albumproducts/album/'.$row->id)}}">{{substr($row->product_no,0,8)}} ... {{substr($row->product_no,15,8)}}</a></td>
                                                        <td>{{substr($row->product_title,0,30)}}</td>
                                                        <td>{{substr($row->product_description,0,50)}}</td>
                                                        <td><img src="/products_img/{{$row->productcover_folder}}/{{$row->productcover_img}}" alt="" width="100px"></td>
                                                        <td>{{$row->user->name}}</td>
                                                        @if($row->publish_status == 0)
                                                            <td class="text-danger text-center">
                                                                <div class="form-check">
                                                                    <label class="form-check-label" for="checkDisplay[]">
                                                                        OFF <input class="form-check-input" type="checkbox" value="" id="checkDisplay[]" hidden>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                        @else
                                                            <td class="text-success text-center">
                                                                <div class="form-check">
                                                                    <label class="form-check-label" for="checkedDisplay[]">
                                                                        ON <input class="form-check-input" type="checkbox" value="" id="checkedDisplay[]" hidden>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                        @endif
                                                        <td class="text-nowrap text-center" >
                                                            <a href="{{url('/products/edit/'.$row->id)}}" class="btn btn-sm btn-warning" >จัดการแสดงผล</a>
                                                            <a href="{{url('/products/del/'.$row->id)}}" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการลบข้อมูลนี้!!')">ลบ</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                        </tbody>
                                    </table>
                                <br />
                                    <div>
                                        {{$productlist->onEachSide(1)->links()}} <!-- คำสั่งแสดงปุ่นกดไปแต่ละหน้า โดยหากมีจำนวนหน้ามากจะแบ่งย่อให้ดูง่ายขึ้น -->
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header text-center">แบบฟอร์มเพิ่มข้อมูลสินค้า</div>
                        <div class="card-body">
                            <form action="{{route('addProduct')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="product_title">ชื่อหัวข้อสินค้า: </label><span class="text-warning"> (ต้องระบุ)</span>
                                    <input type="text" class="form-control" name="product_title" >
                                </div>
                                @error('product_title')
                                    <span class="text-danger py-2">{{$message}}</span>
                                @enderror
                                <div class="form-group">
                                    <label for="product_unit">หน่วยนับสินค้า:</label><span class="text-warning"> (ต้องระบุ)</span>
                                    <input type="text" class="form-control" name="product_unit" >
                                </div>
                                @error('product_unit')
                                    <span class="text-danger py-2">{{$message}}</span>
                                @enderror
                                <div class="form-group">
                                    <label for="product_price">ราคาสินค้า:</label><span class="text-warning"> (ต้องระบุ)</span>
                                    <input type="number" class="form-control" name="product_price" min="0.0001" step="0.0001"><!-- กำหนดให้กรอกได้เฉพาะตัวเลขจำนวนเต็ม หรือทศนิยม -->
                                </div>
                                @error('product_price')
                                    <span class="text-danger py-2">{{$message}}</span>
                                @enderror
                                <div class="form-group">
                                    <label for="product_description">รายละเอียดสินค้า:</label><span class="text-warning"> (ต้องระบุ)</span>
                                    <textarea class="form-control" name="product_description" ></textarea>
                                </div>
                                @error('product_description')
                                    <span class="text-danger py-2">{{$message}}</span>
                                @enderror
                                <div class="form-group">
                                    <label for="productcover_img">รูปปกสินค้า:</label><span class="text-warning"> (เฉพาะไฟล์นามสกุล jpg,jpeg,png เท่านั้น และขนาดไฟล์ไม่เกิน 2 MB.) </span>
                                    <input type="file" class="form-control" id="productcover_img" name="productcover_img" accept="image/jpeg,image/jpg,image/png">
                                    <img width="50%" id="previewImg" alt="" class="mt-2">
                                </div>
                                @error('productcover_img')
                                    <span class="text-danger py-2">{{$message}}</span>
                                @enderror
                                <input type="hidden" name="user_id" value="{{Auth::User()->id}}">
                                <br/>
                                <input type="submit" class="btn btn-primary" value="บันทึก">
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
