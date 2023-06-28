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
                                        <th scope="col">ลำดับ</th>
                                        <th scope="col">ชื่อผู้ใช้งาน</th>
                                        <th scope="col">อีเมล</th>
                                        <th scope="col">Level Working</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $row)
                                        <tr>
                                            <th scope="row">{{$users->firstItem()+$loop->index}}</th>
                                            <td>{{$row->name}}</td>
                                            <td>{{$row->email}}</td>
                                            <td>{{$row->lv_working}}</td>
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
