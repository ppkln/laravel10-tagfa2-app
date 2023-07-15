<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use DateTime;

class ManageUserController extends Controller
{
    //
    public function editPriority($id){
        if($id){
            $userdata = User::find($id);
            if($userdata){
                return view('backend.manageUser',compact('userdata'));
            }
        }else {
            return redirect()->back();
        }
    }

    public function updatePriority(Request $request,$id){
        $request->validate([
            'lv_working' => 'required',
        ],[
            'lv_working.required'=>"กรุณาระบุระดับการทำงาน"
        ]);
        $date_update = new DateTime();
        if($id){
            User::find($id)->update([
                'lv_working' =>$request->lv_working,
                'updated_at'=>$date_update
            ]);
            return redirect()->back()->with('success','แก้ไขข้อมูลระดับการทำงาน สำเร็จแล้ว');
        }
    }
}
