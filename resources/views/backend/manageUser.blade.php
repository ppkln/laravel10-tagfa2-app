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
                    <div class="card-header text-center text-success"><strong>ปรับระดับการทำงานของผู้ใช้งาน</strong></div>
                    <div class="card-body">
                        <form action="{{url('/manageUser/update/'.$userdata->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group py-2">
                                <label for="user_name" class="text-primary">ชื่อ - สกุล: </label><span class="text-success"> {{$userdata->name}}</span>
                            </div>
                            <div class="form-group py-2">
                                <label for="user_email" class="text-primary">E-mail: </label><span class="text-success"> {{$userdata->email}}</span>
                            </div>
                            <div class="form-group py-2">
                                <label for="old_lv" class="text-primary">ระดับการทำงานเดิม: </label><span class="text-success"> {{$userdata->lv_working}}</span>
                            </div>
                            <div class="form-group py-2">
                                <label for="sponsor_level" class="text-primary">ระดับการทำงานใหม่:</label><span class="text-warning"> (ต้องระบุ)</span>
                                <select class="form-control" name="lv_working" id="lv_working">
                                    <option value="" selected>โปรดเลือก</option>
                                    <option value=1>1</option>
                                    <option value=2>2</option>
                                    <option value=3>3</option>
                                    <option value=4>4</option>
                                    <option value=5>5</option>
                                </select>
                            </div>
                            @error('sponsor_level')
                                <span class="text-danger py-2">{{$message}}</span>
                            @enderror

                            <br/>
                            <input type="submit" class="btn btn-primary" value="ปรับปรุง">
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
    let imageInput = document.getElementById('sponsor_img');
    let previewImg = document.getElementById('previewImg');
    imageInput.onchange = evt =>{
        const [file] = imageInput.files;
        if(file){
            previewImg.src = URL.createObjectURL(file);
        }
    }
</script>
