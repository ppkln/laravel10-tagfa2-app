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
                        <div class="card-header text-center"><span class="text-success"><strong>ตารางรายชื่อผู้สนับสนุน</strong></span></div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-striped table-bordered text-center " style="width:100%">
                                        <thead>
                                            <tr class="text-primary">
                                                <th scope="col">ลำดับ</th>
                                                <th style="width:15%">รหัสผู้สนับสนุน</th>
                                                <th scope="col">ชื่อผู้สนับสนุน</th>
                                                <th scope="col">โลโก้ผู้สนับสนุน</th>
                                                <th scope="col">ระดับผู้สนับสนุน</th>
                                                <th scope="col">ผู้ปรับปรุงข้อมูล</th>
                                                <th scope="col">การดำเนินการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($sponsors as $row)
                                                    <tr>
                                                        <th scope="row">{{$sponsors->firstItem()+$loop->index}}</th>
                                                        <td>{{substr($row->sponsor_id,0,8)}} ... {{substr($row->sponsor_id,15,8)}}</td>
                                                        <td>{{substr($row->sponsor_name,0,30)}}</td>
                                                        <td><a href="{{$row->sponsor_link}}"><img src="/sponsors_img/{{$row->sponsor_img}}" alt="" width="100px"></a></td>
                                                        <td>{{$row->sponsor_level}}</td>
                                                        <td>{{$row->user->name}}</td>
                                                        <td class="text-nowrap text-center" >
                                                            <a href="{{url('/sponsors/edit/'.$row->id)}}" class="btn btn-sm btn-warning" >ปรับปรุง</a>
                                                            <a href="{{url('/sponsors/del/'.$row->id)}}" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการลบข้อมูลนี้!!')">ลบ</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                        </tbody>
                                    </table>
                                <br />
                                    <div>
                                        {{$sponsors->onEachSide(1)->links()}} <!-- คำสั่งแสดงปุ่นกดไปแต่ละหน้า โดยหากมีจำนวนหน้ามากจะแบ่งย่อให้ดูง่ายขึ้น -->
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header text-center text-success"><strong>แบบฟอร์มเพิ่มข้อมูลผู้สนับสนุน</strong></div>
                        <div class="card-body">
                            <form action="{{route('addSponsor')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group py-2">
                                    <label for="sponsor_name" class="text-primary">ชื่อผู้สนับสนุน: </label><span class="text-warning"> (ต้องระบุ)</span>
                                    <input type="text" class="form-control" name="sponsor_name" >
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
                                    <input type="text" class="form-control" name="sponsor_link" >
                                </div>
                                @error('sponsor_link')
                                    <span class="text-danger py-2">{{$message}}</span>
                                @enderror
                                <div class="form-group py-2">
                                    <label for="sponsor_img" class="text-primary">รูปโลโก้ผู้สนับสนุน:</label><span class="text-warning"> (เฉพาะไฟล์นามสกุล jpg,jpeg,png เท่านั้น และขนาดไฟล์ไม่เกิน 2 MB.) </span>
                                    <input type="file" class="form-control" id="sponsor_img" name="sponsor_img" accept="image/jpeg,image/jpg,image/png">
                                    <img width="50%" id="previewImg" alt="" class="mt-2">
                                </div>
                                @error('sponsor_img')
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
    let imageInput = document.getElementById('sponsor_img');
    let previewImg = document.getElementById('previewImg');
    imageInput.onchange = evt =>{
        const [file] = imageInput.files;
        if(file){
            previewImg.src = URL.createObjectURL(file);
        }
    }
</script>
