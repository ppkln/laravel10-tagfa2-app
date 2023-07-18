<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\posts;
use App\Models\sponsors;
use App\Models\User;
use App\Models\albumposts;
use DateTime;

class PostsController extends Controller
{
    //
    public function home(){// สำหรับหน้าเพจที่ไม่มีการ login ก็ใช้งานได้
        $postlist = posts::where('publish_status',1)->latest()->paginate(6); //กำหนดขนาดจำนวนรายการข้อมูลที่แสดงต่อ 1 หน้าเพจ และเป็นเรียงจากข้อมูลล่าสุดขึ้นก่อน
        $sponsors = sponsors::orderBy('sponsor_level','desc')->get();
        if($postlist && $sponsors){
            return view('home',compact('postlist','sponsors'));
        }
    }

    public function index(){ // สำหรับผู้ที่ login ผ่านเท่านั้นจะเข้าใช้งานได้
        $posts = posts::all();
        if($posts){
            $postlist = posts::latest()->paginate(4); //กำหนดขนาดจำนวนรายการข้อมูลที่แสดงต่อ 1 หน้าเพจ และเป็นเรียงจากข้อมูลล่าสุดขึ้นก่อน
            if($postlist){
                return view('backend.posts.index',compact('posts','postlist'));
            }
        }else {
            return redirect()->back();
        }
    }

    public function detailsPost($id){
        $postDetail = posts::find($id);
        if($postDetail){
            $album_data = albumposts::where('post_no', $postDetail->post_no)->get();
            if($album_data){
                return view('detailpost',compact('postDetail','album_data'));
            }else{
                return view('home');
            }
        }else{
            return redirect()->back();
        }
    }

    public function insertPost(Request $request){
        $request->validate([
            'post_title' =>'required|unique:posts|max:255',
            'post_description'=>'required',
            'postcover_img'=>'required',
        ],[
            'post_title.required' =>"กรุณากรอกชื่อหัวข้อโพส",
            'post_title.max' =>"กรอกข้อมูลได้ไม่เกิน 255 ตัวอักษร",
            'post_title.unique'=>"กรอกชื่อหัวข้อโพสซ้ำ",
            'post_description.required' =>"กรุณากรอกรายละเอียดโพส",
            'postcover_img.required' =>'กรุณาระบุรูปปกโพส',
        ]);
        //จัดการเกี่ยวกับชื่อไฟล์รูปภาพ
        $fileSizeupload = $request->file('postcover_img')->getSize();//เก็บค่าขนาดของไฟล์ที่อัพโหลด
        if($fileSizeupload <= 2*1024*1024 && $fileSizeupload > 0){ //ตรวจสอบขนาดไฟล์ที่อัพโหลดต้องไม่่เกิน 2 MB.
            $cover_img = $request->file('postcover_img');//นำค่าไฟล์ที่ส่งผ่านด้วย method:post มาเก็บในตัวแปล
            $img_ext = strtolower($cover_img->getClientOriginalExtension()); //ทำการดึงชื่อนามสกุลของไฟล์พร้อมกับแปลงเป็นอักษรพิมพ์เล็ก
            $checkExt = array('jpg','jpeg','png','gif','svg','ico');
            if(in_array($img_ext,$checkExt)){
                $newFilenameupload = mktime(date('H'),date('i'),date('s'),date('d'),date('m'),date('Y'))."coverImgPO.".$img_ext; // ตั้งชื่อใหม่ให้ไฟล์ภาพที่เราอัพโหลด ด้วยการประยุกต์ใช้รูปแบบของวันเวลาปัจจุบันมากำหนดเป็นชื่อ
                //สร้าง folder เพื่อทำเป็น path เป้าหมายปลายทางที่ใช้จัดเก็บไฟล์รูปภาพ
                $random_postFD = "PSFD_".hexdec(uniqid());//สร้างชื่อ folder โพส ด้วยวิธีการสุ่มตั้งชื่อด้วยเลขฐาน16
                $Pathfolderupload="./posts_img/";//โฟลเดอร์เริ่มต้นที่ public
                $mkdirfolder = mkdir($Pathfolderupload.$random_postFD,0777,true); //คำสั่งให้ทำการสร้าง Folder ที่ server
                //upload ไฟล์ไปที่ server
                if($mkdirfolder){
                    if($cover_img->move($Pathfolderupload.$random_postFD,$newFilenameupload)){
                        //บันทีกข้อมูลลงตารางข้อมูล เมื่อตรวจสอบพบว่ารูปภาพถูกอัพโหลดไปที่ server สำเร็จจริง
                        $random_postID = "PSID_".hexdec(uniqid());//สร้างรหัสโพสด้วยวิธีการสุ่มตั้งชื่อด้วยเลขฐาน 16 เพื่อเอาไว้ใช้ตอนบันทึกลงตารางฐานข้อมูล

                        $postdata = new posts;
                        $postdata->post_no = $random_postID;
                        $postdata->post_title = $request->post_title;
                        $postdata->post_description = $request->post_description;
                        $postdata->postcover_folder = $random_postFD;//ชื่อ path folder ของรูปภาพปกโพสที่ server สร้างขึ้นใหม่
                        $postdata->postcover_img = $newFilenameupload;//ชื่อใหม่ของรูปภาพปกโพสที่ server สร้างขึ้นใหม่
                        $postdata->user_id = $request->user_id;
                        $postdata->post_category = 'Posts';
                        $postdata->publish_status = 0;
                        $postdata->save();

                        return redirect()->back()->with('success','บันทึกข้อมูลเรียบร้อย');
                    };
                };
            }else{
                return redirect()->back()->with('unsuccess','บันทึกข้อมูลไม่สำเร็จ เพราะชนิดของไฟล์ไม่ถูกต้อง');
            }

        }else{
            return redirect()->back()->with('unsuccess','บันทึกข้อมูลไม่สำเร็จ เพราะขนาดของไฟล์มากกว่า 2 MB หรือไม่มีการอัพโหลดไฟล์');
        };

    }

    public function editPost($id){
        $postEdit = posts::find($id);
        if($postEdit){
            return view('backend.posts.editPost',compact('postEdit'));
        }else{
            return redirect()->back();
        }
    }

    public function editNo($post_no){
        $postEdit = posts::where('post_no',$post_no)->first();
        if($postEdit){
            return view('backend.posts.editpost',compact('postEdit'));
        }else{
            return redirect()->back();
        }
    }

    public function updatePost(Request $request,$id){
        $request->validate([
            'post_title' =>'required|max:255',
            'post_description' =>'required',
        ],[
            'post_title.required' =>"กรุณากรอกชื่อหัวข้อโพส",
            'post_title.max' =>"กรอกข้อมูลได้ไม่เกิน 255 ตัวอักษร",
            'post_description.required' =>"กรุณากรอกรายละเอียดโพส",
        ]);
        $date_update = new DateTime();
        //จัดการเกี่ยวกับชื่อไฟล์รูปภาพ
        $postcover_img = $request->file('postcover_img');
        $postcover_folder = $request->postcover_folder;//โฟลเดอร์ที่จัดเก็บภาพของโพสนี้ไว้ มาจาก component input type:hidden
        if($request->publish_status){
            $publish_status = 1;
        } else {
            $publish_status = 0;
        }
        if($postcover_img){//หากมีการเลือกไฟล์รูปภาพใหม่เพื่อแก้ไขแทนรูปเดิมจริง
            $fileSizeupload = $request->file('postcover_img')->getSize();//เก็บค่าขนาดของไฟล์ที่อัพโหลดใหม่
            if($fileSizeupload <= 2*1024*1024 ){//จำกัดขนาดไฟล์ที่อัพโหลด
                $img_ext = strtolower($postcover_img->getClientOriginalExtension()); //ทำการดึงชื่อนามสกุลของไฟล์พร้อมกับแปลงเป็นอักษรพิมพ์เล็ก
                $newFilenameupload = mktime(date('H'),date('i'),date('s'),date('d'),date('m'),date('Y'))."coverImgPD.".$img_ext; // ตั้งชื่อใหม่ให้ไฟล์ภาพที่เราอัพโหลด ด้วยการประยุกต์ใช้รูปแบบของวันเวลาปัจจุบันมากำหนดเป็นชื่อ
                $Pathfolderupload="./posts_img/".$postcover_folder;//พาทที่จะทำการอัพโหลดรูปภาพไปเก็บที่ Server ซึ่งอยู่ภายใต้ folder เดิม
                if($postcover_img->move($Pathfolderupload,$newFilenameupload)){
                    posts::find($id)->update([
                        'post_title'=>$request->post_title,
                        'post_description'=> $request->post_description,
                        'postcover_img'=>$newFilenameupload,
                        'post_unit'=>$request->post_unit,
                        'post_price'=>$request->post_price,
                        'user_id'=>$request->user_id,
                        'publish_status'=>$publish_status,
                        'updated_at'=>$date_update
                    ]);
                    $old_img = $request->Old_img;//ชื่อไฟล์รูปภาพเดิม มาจาก component hidden
                    unlink($Pathfolderupload."/".$old_img); // ลบภาพเดิมที่ถูกแก้ไขแทนที่
                    return redirect()->back()->with('success','แก้ไขข้อมูลสำเร็จแล้ว');
                }else{
                    return redirect()->back()->with('unsuccess','แก้ไขข้อมูลไม่สำเร็จ');
                }
            }else{
                return redirect()->back()->with('unsuccess','แก้ไขข้อมูลไม่สำเร็จ เพราะขนาดของไฟล์มากกว่า 3 MB ');
            };
        }else {//กรณีแก้ไขเพียงข้อความ ไม่มีการแก้ไขรูปภาพ
            posts::find($id)->update([
                'post_title'=>$request->post_title,
                'post_description'=>$request->post_description,
                'post_unit'=>$request->post_unit,
                'post_price'=>$request->post_price,
                'user_id'=>$request->user_id,
                'publish_status'=>$publish_status,
                'updated_at'=>$date_update
            ]);
            return redirect()->back()->with('success','แก้ไขข้อมูลสำเร็จแล้ว');
        };
    }

    public function delPost($id){
        if($id){
            $postDel = posts::find($id);
            if($postDel){
                $postcover_folder = $postDel->postcover_folder;//ข้อมูลชื่อโฟลเดอร์ที่เก็บรูปภาพปกโพสเอาไว้
                $album_data = albumposts::where('post_no', $postDel->post_no)->first();//ตรวจสอบว่าโพสนี้มีการจัดเก็บรูปรายละเอียดโพสในตาราง albumpost หรือไม่
                if($album_data){ //หากพบว่าโพสนี้มีการจัดเก็บรูปรายละเอียดโพส จะต้องลบรูปที่เป็นรายละเอียดโพสให้เสร็จก่อนที่จะลบรูปปกโพสได้
                    $folderAlbum = $album_data->album_no; //แสดงชื่อโฟรเดอร์หมายเลข album
                    $stmtAlbumDel = albumposts::where('album_no',$folderAlbum)->get();//ค้นหาข้อมูลรูปรายละเอียดโพสทั้งหมด ที่จัดเก็บอยู่ในโฟลเดอร์ album_no นี้
                    foreach($stmtAlbumDel as $key=>$value){
                        $imgDel_name = $value->img_name;
                        $pathDelImg = "./posts_img/".$postcover_folder."/".$folderAlbum."/".$imgDel_name;
                        if(file_exists($pathDelImg)){//หากมีไฟล์ชื่อนี้จริงใน path ที่ระบุ
                            if(unlink($pathDelImg)){//ทำการลบข้อมูลรูปรายละเอียดโพสแต่ละรายการในตารางฐานข้อมูล
                                $delImgAlbum = albumposts::find($value->id)->delete();
                            }else{
                                return redirect()->back()->with('unsuccess','ลบข้อมูลไม่สำเร็จ');
                            }
                        }
                    }
                    $pathDelAlbum = "./posts_img/".$postcover_folder."/".$folderAlbum;
                    if(rmdir($pathDelAlbum)){//ลบโฟลเดอร์ album ที่ว่างเปล่าทิ้ง
                        $img_name = $postDel->postcover_img;
                        $fullPathImg = "./posts_img/".$postcover_folder."/".$img_name;
                        if(file_exists($fullPathImg)){//เช็คว่ามีไฟล์รูปภาพนี้อยู่จริงหรือไม่
                            if(unlink($fullPathImg)){ // หากลบลบรูปภาพปกโพสสำเร็จจริง
                                $fullPathFolderDel = "./posts_img/".$postcover_folder; // path ที่อยู่่ของ Folder ที่ระบบสร้างไว้
                                if(rmdir($fullPathFolderDel)){//คำสั่งลบ Folder เปล่า หลังจากลบรูปภาพปกโพสแล้ว
                                    $delete = posts::find($id)->delete();// ลบข้อมูลโพสในตารางฐานข้อมูล
                                    return redirect()->back()->with('success','ลบข้อมูลเรียบร้อยแล้ว');
                                }else{
                                    return redirect()->back()->with('unsuccess','ลบข้อมูลโฟลเดอร์ '.$postcover_folder.' ไม่สำเร็จ');
                                }

                            }else {
                                return redirect()->back()->with('unsuccess','ลบข้อมูลไม่สำเร็จ');
                            }
                        }elseif(file_exists("./posts_img/".$postcover_folder)){
                            $fullPathFolderDel = "./posts_img/".$postcover_folder;
                            if(rmdir($fullPathFolderDel)){//คำสั่งลบ Folder เปล่า หลังจากลบรูปภาพปกโพสแล้ว
                                $delete = posts::find($id)->delete();// ลบข้อมูลโพสในตารางฐานข้อมูล
                                return redirect()->back()->with('success','ลบข้อมูลเรียบร้อยแล้ว');
                            }else{
                                return redirect()->back()->with('unsuccess','ลบข้อมูลโฟลเดอร์ '.$postcover_folder.' ไม่สำเร็จ');
                            }
                        }

                    }else{
                        return redirect()->back()->with('unsuccess','ลบข้อมูลโฟลเดอร์ '.$postcover_folder.' ไม่สำเร็จ');
                    }
                }

                $img_name = $postDel->postcover_img;
                $fullPathImg = "./posts_img/".$postcover_folder."/".$img_name;
                if(file_exists($fullPathImg)){
                    if(unlink($fullPathImg)){ // หากลบลบรูปภาพปกโพสสำเร็จจริง
                        $fullPathFolderDel = "./posts_img/".$postcover_folder; // path ที่อยู่่ของ Folder ที่ระบบสร้างไว้
                        if(rmdir($fullPathFolderDel)){//คำสั่งลบ Folder เปล่า หลังจากลบรูปภาพปกโพสแล้ว
                            $delete = posts::find($id)->delete();// ลบข้อมูลโพสในตารางฐานข้อมูล
                            return redirect()->back()->with('success','ลบข้อมูลเรียบร้อยแล้ว');
                        }else{
                            return redirect()->back()->with('unsuccess','ลบข้อมูลโฟลเดอร์ '.$postcover_folder.' ไม่สำเร็จ');
                        }

                    }else {
                        return redirect()->back()->with('unsuccess','ลบข้อมูลไม่สำเร็จ');
                    }
                }elseif(file_exists("./posts_img/".$postcover_folder)){
                    $fullPathFolderDel = "./posts_img/".$postcover_folder;
                    if(rmdir($fullPathFolderDel)){//คำสั่งลบ Folder เปล่า หลังจากลบรูปภาพปกโพสแล้ว
                        $delete = posts::find($id)->delete();// ลบข้อมูลโพสในตารางฐานข้อมูล
                        return redirect()->back()->with('success','ลบข้อมูลเรียบร้อยแล้ว');
                    }else{
                        return redirect()->back()->with('unsuccess','ลบข้อมูลโฟลเดอร์ '.$postcover_folder.' ไม่สำเร็จ');
                    }
                }
            }
        }else{
            return view('postslist');
        }

        //
    }
}
