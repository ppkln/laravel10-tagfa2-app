<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sponsors;
use DateTime;
use Illuminate\Auth\Events\Validated;

class sponsorscontroller extends Controller
{
    //
    public function index(){
        $sponsors = sponsors::orderBy('sponsor_level','desc')->paginate(4);
        if($sponsors){
            return view('backend.sponsors.index',compact('sponsors'));
        }else{
            return redirect()->back();
        }
    }

    public function insertSponsor(Request $request){
        $request->validate([
            'sponsor_name' =>'required|max:255',
            'sponsor_level'=>'required',
            'sponsor_img'=>'required',
        ],[
            'sponsor_name.required' =>'กรุณากรอกชื่อผู้สนับสนุน',
            'sponsor_name.max'=> 'กรอกข้อมูลได้ไม่เกิน 255 ตัวอักษร',
            'sponsor_level.required'=> 'กรุณาระบุระดับผู้สนับสนุน',
            'sponsor_img.required' => 'กรุณาระบุรูปโลโก้ผู้สนับสนุน',
        ]);
        //จัดการเกี่ยวกับชื่อไฟล์รูปภาพ
        $fileSizeupload = $request->file('sponsor_img')->getSize();//เก็บค่าขนาดของไฟล์ที่อัพโหลด
        if($fileSizeupload <= 2*1024*1024 && $fileSizeupload > 0){ //ตรวจสอบขนาดไฟล์ที่อัพโหลดต้องไม่่เกิน 2 MB.
            $logo_img = $request->file('sponsor_img');//นำค่าไฟล์ที่ส่งผ่านด้วย method:post มาเก็บในตัวแปล
            $img_ext = strtolower($logo_img->getClientOriginalExtension()); //ทำการดึงชื่อนามสกุลของไฟล์พร้อมกับแปลงเป็นอักษรพิมพ์เล็ก
            $checkExt = array('jpg','jpeg','png','gif','svg','ico');
            if(in_array($img_ext,$checkExt)){
                $newFilenameupload = "LogoSp".mktime(date('H'),date('i'),date('s'),date('d'),date('m'),date('Y')).".".$img_ext; // ตั้งชื่อใหม่ให้ไฟล์ภาพที่เราอัพโหลด ด้วยการประยุกต์ใช้รูปแบบของวันเวลาปัจจุบันมากำหนดเป็นชื่อ
                //สร้าง folder เพื่อทำเป็น path เป้าหมายปลายทางที่ใช้จัดเก็บไฟล์รูปภาพ
                $Pathfolderupload="../public/sponsors_img/";//โฟลเดอร์เริ่มต้นที่ public
                //upload ไฟล์ไปที่ server
                if($logo_img->move($Pathfolderupload,$newFilenameupload)){
                    //บันทีกข้อมูลลงตารางข้อมูล เมื่อตรวจสอบพบว่ารูปภาพถูกอัพโหลดไปที่ server สำเร็จจริง
                    $random_sponsorID = "SPID_".hexdec(uniqid());//สร้างรหัสผู้สนับสนุนด้วยวิธีการสุ่มตั้งชื่อด้วยเลขฐาน 16 เพื่อเอาไว้ใช้ตอนบันทึกลงตารางฐานข้อมูล

                    $sponsordata = new sponsors;
                    $sponsordata->sponsor_id = $random_sponsorID;
                    $sponsordata->sponsor_name = $request->sponsor_name;
                    $sponsordata->sponsor_link = $request->sponsor_link;
                    $sponsordata->sponsor_img = $newFilenameupload;//ชื่อใหม่ของรูปภาพปกสินค้าที่ server สร้างขึ้นใหม่
                    $sponsordata->sponsor_level = $request->sponsor_level;
                    $sponsordata->user_id = $request->user_id;
                    $sponsordata->save();

                    return redirect()->back()->with('success','บันทึกข้อมูลเรียบร้อย');
                };
            }else{
                return redirect()->back()->with('unsuccess','บันทึกข้อมูลไม่สำเร็จ เพราะชนิดของไฟล์ไม่ถูกต้อง');
            }

        }else{
            return redirect()->back()->with('unsuccess','บันทึกข้อมูลไม่สำเร็จ เพราะขนาดของไฟล์มากกว่า 2 MB หรือไม่มีการอัพโหลดไฟล์');
        };
    }

    public function editSponsor($id){
        $sponsors = sponsors::find($id);
        if($sponsors){
            return view('backend.sponsors.editSponsor',compact('sponsors'));
        }else{
            return redirect()->back();
        }
    }

    public function updateSponsor(Request $request,$id){
        $request->validate([
            'sponsor_name' =>'required|max:255',
            'sponsor_level'=>'required',
        ],[
            'sponsor_name.required' =>'กรุณากรอกชื่อผู้สนับสนุน',
            'sponsor_name.max'=> 'กรอกข้อมูลได้ไม่เกิน 255 ตัวอักษร',
            'sponsor_level.required'=> 'กรุณาระบุระดับผู้สนับสนุน',
        ]);
        if($id){
            $date_update = new DateTime();
            $old_img = $request->Old_img;//ชื่อไฟล์รูปภาพเดิม มาจาก component hidden
            //จัดการเกี่ยวกับชื่อไฟล์รูปภาพ
            $sponsor_img = $request->file('sponsor_img');
            if($sponsor_img){ //กรณีมีการเลือกไฟล์ใหม่เพิ่อแทนที่ไฟล์เดิม
                $fileSizeupload = $request->file('sponsor_img')->getSize();
                if($fileSizeupload <= 2*1024*1024 && $fileSizeupload > 0){
                    $img_ext = strtolower($sponsor_img->getClientOriginalExtension());
                    $checkExt = array('jpg','jpeg','png','gif','svg','ico');
                    if(in_array($img_ext,$checkExt)){
                        $newFilenameupload = "LogoSp".mktime(date('H'),date('i'),date('s'),date('d'),date('m'),date('Y')).".".$img_ext; // ตั้งชื่อใหม่ให้ไฟล์ภาพที่เราอัพโหลด ด้วยการประยุกต์ใช้รูปแบบของวันเวลาปัจจุบันมากำหนดเป็นชื่อ
                        $Pathfolderupload="../public/sponsors_img/";//โฟลเดอร์เริ่มต้นที่ public
                        if($sponsor_img->move($Pathfolderupload,$newFilenameupload)){
                            sponsors::find($id)->update([
                                'sponsor_name' => $request->sponsor_name,
                                'sponsor_link' => $request->sponsor_link,
                                'sponsor_img'=>$newFilenameupload,
                                'sponsor_level' => $request->sponsor_level,
                                'user_id' => $request->user_id,
                                'updated_at' => $date_update
                            ]);
                            unlink($Pathfolderupload.$old_img); // ลบภาพเดิมที่ถูกแก้ไขแทนที่
                            return redirect()->back()->with('success','แก้ไขข้อมูลสำเร็จแล้ว');
                        }else{
                            return redirect()->back()->with('unsuccess','แก้ไขข้อมูลไม่สำเร็จ');
                        }
                    }
                } else{
                    return redirect()->back()->with('unsuccess','บันทึกข้อมูลไม่สำเร็จ เพราะขนาดของไฟล์มากกว่า 2 MB หรือไม่มีการอัพโหลดไฟล์');
                }
            } else {
                sponsors::find($id)->update([
                    'sponsor_name' => $request->sponsor_name,
                    'sponsor_link' => $request->sponsor_link,
                    'sponsor_img'=>$old_img,
                    'sponsor_level' => $request->sponsor_level,
                    'user_id' => $request->user_id,
                    'updated_at' => $date_update
                ]);
                return redirect()->back()->with('success','แก้ไขข้อมูลสำเร็จแล้ว');
            }
        }
    }

    public function deleteSponsor($id){
        if($id){
            $sponsorDel = sponsors::find($id)->first();
            if($sponsorDel){
                $PathfolderDelete="../public/sponsors_img/".$sponsorDel->sponsor_img;//โฟลเดอร์เริ่มต้นที่ public
                if(file_exists($PathfolderDelete)){
                    if(unlink($PathfolderDelete)){
                        $delSponsor = sponsors::find($id)->delete();
                        return redirect()->back()->with('success','ลบข้อมูลสำเร็จแล้ว');
                    }else{
                        return redirect()->back()->with('unsuccess','ลบข้อมูลไม่สำเร็จ!!');
                    }
                }else{
                    $delSponsor = sponsors::find($id)->delete();
                    return redirect()->back()->with('success','ลบข้อมูลสำเร็จแล้ว');
                }
            }else{
                return redirect()->back()->with('unsuccess','ลบข้อมูลไม่สำเร็จ ไม่พบข้อมูลนี้ในฐานข้อมูล!!');
            }

        }else {
            return redirect()->back()->with('unsuccess','ลบข้อมูลไม่สำเร็จ!!');
        }
    }
}
