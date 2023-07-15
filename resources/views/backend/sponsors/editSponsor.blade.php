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
                    <div class="card-header text-center text-success"><strong>แบบฟอร์มปรับปรุงข้อมูลผู้สนับสนุน</strong></div>
                    <div class="card-body">
                        <form action="{{url('/sponsors/update/'.$sponsors->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" value="{{Auth::User()->id}}">
                            <input type="hidden" name="Old_img" value="{{$sponsors->sponsor_img}}">
                            <div class="form-group py-2">
                                <label for="sponsor_name" class="text-primary">ชื่อผู้สนับสนุน: </label><span class="text-warning"> (ต้องระบุ)</span>
                                <input type="text" class="form-control" name="sponsor_name" value="{{$sponsors->sponsor_name}}" >
                            </div>
                            @error('sponsor_name')
                                <span class="text-danger py-2">{{$message}}</span>
                            @enderror
                            <div class="form-group py-2">
                                <label for="sponsor_level" class="text-primary">ระดับของผู้สนับสนุน:</label><span class="text-warning"> (ต้องระบุ)</span>
                                <select class="form-control" name="sponsor_level" id="sponsor_level">
                                    <option value="" selected>โปรดเลือก</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                            </div>
                            @error('sponsor_level')
                                <span class="text-danger py-2">{{$message}}</span>
                            @enderror
                            <div class="form-group py-2">
                                <label for="sponsor_link" class="text-primary">URL ของผู้สนับสนุน:</label><span class="text-secondary"> (โปรดระบุหากมีข้อมูล URL )</span>
                                <input type="text" class="form-control" name="sponsor_link" value="{{$sponsors->sponsor_link}}">
                            </div>
                            @error('sponsor_link')
                                <span class="text-danger py-2">{{$message}}</span>
                            @enderror
                            <div class="form-group py-2">
                                <label for="sponsor_img" class="text-primary">รูปโลโก้ผู้สนับสนุน:</label><span class="text-warning"> (เฉพาะไฟล์นามสกุล jpg,jpeg,png เท่านั้น และขนาดไฟล์ไม่เกิน 2 MB.) </span>
                                <input type="file" class="form-control" id="sponsor_img" name="sponsor_img" accept="image/jpeg,image/jpg,image/png">
                                <img src="/sponsors_img/{{$sponsors->sponsor_img}}" id="previewImg" alt="" class="mt-2" width="300px">
                            </div>
                            @error('sponsor_img')
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
