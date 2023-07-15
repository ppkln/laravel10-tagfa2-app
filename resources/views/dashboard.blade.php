<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        สวัสดีคุณ {{Auth::User()->name}}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="container my-2">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">ตารางข้อมูลผู้ใช้</div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">ลำดับ</th>
                                        <th scope="col" class="text-center">ชื่อผู้ใช้งาน</th>
                                        <th scope="col" class="text-center">E-mail</th>
                                        <th scope="col" class="text-center">ระดับการทำงาน</th>
                                        @if (Auth::User()->lv_working >= 3) <!-- ตรวจสอบว่าผู้ที่ login มีสิทธิ์ใช้งานระดับไหน -->
                                            <th scope="col" class="text-center">การดำเนินการ</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $row)
                                        <tr>
                                            <th scope="row" class="text-center">{{$users->firstItem()+$loop->index}}</th>
                                            <td class="text-center">{{$row->name}}</td>
                                            <td class="text-center">{{$row->email}}</td>
                                            <td class="text-center">{{$row->lv_working}}</td>
                                            @if (Auth::User()->lv_working >= 3 ) <!-- ตรวจสอบว่าผู้ที่ login มีสิทธิ์ใช้งานระดับไหน -->
                                                <td class="text-center">
                                                    @if($row->lv_working > 5)
                                                        <label class="text-primary ">-</label>
                                                    @else
                                                    <a href="{{url('manageUser/edit/'.$row->id)}}" class="btn btn-sm btn-warning" >ปรับระดับการทำงาน</a>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    <tr>
                                </tbody>
                            </table>
                            {{$users->links()}}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
