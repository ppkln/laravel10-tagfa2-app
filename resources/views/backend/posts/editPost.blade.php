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
                        <div class="card-header text-center text-primary">แบบฟอร์มปรับปรุงข้อมูลโพส</div>
                        <div class="card-body">
                            <form action="{{url('/posts/update/'.$postEdit->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="post_title">ชื่อหัวข้อโพส: </label><span class="text-warning"> (ต้องระบุ)</span>
                                    <input type="text" class="form-control" name="post_title" value="{{$postEdit->post_title}}">
                                </div>
                                @error('post_title')
                                    <span class="text-danger py-2">{{$message}}</span>
                                @enderror
                                <div class="form-group">
                                    <label for="post_description">รายละเอียดโพส:</label><span class="text-warning"> (ต้องระบุ)</span>
                                    <textarea class="form-control" name="post_description" >{{$postEdit->post_description}}</textarea>
                                </div>
                                @error('post_description')
                                    <span class="text-danger py-2">{{$message}}</span>
                                @enderror
                                <div class="form-group">
                                    <input type="hidden" name="Old_img" value="{{$postEdit->postcover_img}}">
                                    <input type="hidden" name="postcover_folder" value="{{$postEdit->postcover_folder}}">
                                    <label for="postcover_img">แก้ไขรูปปกโพส:</label><span class="text-warning"> (เฉพาะไฟล์นามสกุล jpg,jpeg,png เท่านั้น และขนาดไฟล์ไม่เกิน 2 MB.) </span>
                                    <input type="file" class="form-control" id="postcover_img" name="postcover_img" accept="image/jpeg,image/jpg,image/png">
                                    <img src="/posts_img/{{$postEdit->postcover_folder}}/{{$postEdit->postcover_img}}" id="previewImg" alt="" class="mt-2" width="300px">
                                </div>
                                @error('postcover_img')
                                    <span class="text-danger py-2">{{$message}}</span>
                                @enderror
                                <input type="hidden" name="user_id" value="{{Auth::User()->id}}">
                                <br/>
                                <div>
                                    <span class="text-primary py-2">สถานะแสดงผล: </span>
                                    @if($postEdit->publish_status == 1)
                                        <label for="publish_status" class="text-success">แสดงผล  <input class="form-check-input" type="checkbox" name="publish_status" checked></label>
                                    @else
                                    <label for="publish_status"  class="text-danger">ยังไม่แสดงผล  <input class="form-check-input" type="checkbox" name="publish_status"></label>
                                    @endif
                                </div>
                                <div class="text-center">
                                    <input type="submit" class="btn btn-primary" value="ปรับปรุงข้อมูล">
                                </div>
                                <br />
                                <a href="{{url('/albumposts/album/'.$postEdit->id)}}" class="btn btn-success" >จัดการรูปภาพรายละเอียดโพส</a>

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
    let imageInput = document.getElementById('postcover_img');
    let previewImg = document.getElementById('previewImg');
    imageInput.onchange = evt =>{
        const [file] = imageInput.files;
        if(file){
            previewImg.src = URL.createObjectURL(file);
        }
    }
</script>
