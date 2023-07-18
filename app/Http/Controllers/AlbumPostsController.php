<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\posts;
use App\Models\albumposts;

class AlbumPostsController extends Controller
{
    //
    public function albumPostQueryId($id){
        $post_album = posts::find($id);
        if($post_album){
            $album_data = albumposts::where('post_no', $post_album->post_no)->get();
            return view('backend.albumposts.addimgs',compact('post_album','album_data'));
        }else {
            return view('home');
        }
    }

    public function albumPostAddImg(Request $request){
        $request->validate([
            'album_img' => 'required',
            'album_img.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ],[
                'album_img.required' =>"กรุณาระบุรูปภาพโพส",
                'album_img.max' =>"ขนาดไฟล์ไม่เกินไฟล์ละ 2 MB",
                'album_img.image' =>"รองรับเฉพาะไฟล์นามสกุล jpg,jpeg,png เท่านั้น",
        ]);
        $post_no = $request->post_no;
        $postcover_folder = $request->postcover_folder;
        $user_id = $request->user_id;
        if($request->file('album_img')){
            //ตรวจสอบว่าได้มีข้อมูลหมายเลข post_no นี้อยู่ในฐานข้อมูลแล้วหรือไม่ หากมีจะต้องใช้เลข album_no เดิม
            $album_Oldno = albumposts::where('post_no',$post_no)->first();
            if($album_Oldno){//กรณีเคยบันทึกข้อมูลรูปภาพเพิ่มเติมของโพสตัวนี้
                $album_NO = $album_Oldno->album_no;
                $Pathfolderpost="./posts_img/".$postcover_folder."/";//ชื่อโฟลเดอร์ที่จัดเก็บข้อมูลรูปภาพของโพสชิ้นนี้
                foreach($request->file('album_img') as $key=>$value){
                    $imgNewName = time().rand(1,99).'_albPS.'.$value->extension(); //ตั้งชื่อใหม่ให้รูปภาพด้วยวิธีการ random ชื่อ
                    if($value->move($Pathfolderpost.$album_NO,$imgNewName)){

                        $album_data = new albumposts;
                        $album_data->album_no = $album_NO;
                        $album_data->post_no = $post_no;
                        $album_data->img_name = $imgNewName;
                        $album_data->user_id = $user_id;
                        $album_data->save();

                    };
                }
                return redirect()->back()->with('success','เพิ่มข้อมูลรูปภาพสำเร็จแล้ว');
            }else{//กรณีไม่เคยบันทึกข้อมูลรูปภาพเพิ่มเติมของโพสตัวนี้
                $album_NO = "ALBPSNO_".hexdec(uniqid());//สร้างหมายเลข Album โพส ด้วยวิธีการสุ่มตั้งชื่อด้วยเลขฐาน16
                $Pathfolderpost="./posts_img/".$postcover_folder."/";//ชื่อโฟลเดอร์ที่จัดเก็บข้อมูลรูปภาพของโพสชิ้นนี้
                $mkdirfolder = mkdir($Pathfolderpost.$album_NO,0777,true); //คำสั่งให้ทำการสร้าง Folder ย่อยภายใน folder ที่เก็บภาพโพส
                if($mkdirfolder){
                    foreach($request->file('album_img') as $key=>$value){
                        $imgNewName = time().rand(1,99).'_albPS.'.$value->extension(); //ตั้งชื่อใหม่ให้รูปภาพด้วยวิธีการ random ชื่อ
                        if($value->move($Pathfolderpost.$album_NO,$imgNewName)){

                            $album_data = new albumposts;
                            $album_data->album_no = $album_NO;
                            $album_data->post_no = $post_no;
                            $album_data->img_name = $imgNewName;
                            $album_data->user_id = $user_id;
                            $album_data->save();

                        };
                    }
                    return redirect()->back()->with('success','เพิ่มข้อมูลรูปภาพสำเร็จแล้ว');
                }
            }
        }else {
            return redirect()->back()->with('unsuccess','เพิ่มข้อมูลรูปภาพ ไม่สำเร็จแล้ว');
        }
    }

    public function deleteImg($id){
        if($id){//หากไม่มีหมายเลข id ที่ส่งมาจะให้ออกจากการทำงาน controller นี้
            $albumImgDel = albumposts::find($id);
            if($albumImgDel){
                $album_no = $albumImgDel->album_no; //ได้ชื่อโฟลเดอร์ที่เก็บรูปภาพรายละเอีบดโพส
                $albumImg_name = $albumImgDel->img_name;//ได้ชื่อรูปภาพ
                $post_no = $albumImgDel->post_no;//ได้หมายเลขโพส
                $pathpost_no = posts::where('post_no', $post_no)->first();
                if($pathpost_no){
                    $postCover_folder = $pathpost_no->postcover_folder;//ได้ชื่อโฟลเดอร์ที่เก็บรูปภาพปกโพส และเก็บไว้ในตัวแปร $postCover_folder
                    $pathDelalbumImg = "./posts_img/".$postCover_folder."/".$album_no ; // path สำหรับการลบไฟล์
                        if(file_exists($pathDelalbumImg."/".$albumImg_name)){//หากมีไฟล์ชื่อนี้จริงใน path ที่ระบุ
                            if(unlink($pathDelalbumImg."/".$albumImg_name)){//ทำการลบข้อมูลรูปรายละเอียดโพสแต่ละรายการในตารางฐานข้อมูล
                                $delImgAlbum = albumposts::find($id)->delete();
                                // Start ทำการตรวจสอบว่าในโฟลเดอร์มีไฟล์อยู่หรือไม่
                                $dirFolderAlbum = opendir($pathDelalbumImg);
                                $data ="";
                                $fcount = 0;
                                while($data = readdir($dirFolderAlbum)){
                                    $fcount = $fcount+1;
                                }
                                if(($fcount < 3)){
                                    rmdir($pathDelalbumImg); // ทำการลบโฟลเดอร์รูปภาพรายละเอียดโพส
                                    return redirect()->back()->with('success','ลบข้อมูลรูปภาพรายละเอียดโพส พร้อมทั้ง Folder ที่จัดเก็บรูปภาพสำเร็จ');
                                }else{
                                    return redirect()->back()->with('success','ลบข้อมูลเฉพาะรูปภาพรายละเอียดโพสสำเร็จ');
                                }
                                closedir($dirFolderAlbum);
                                // End ทำการตรวจสอบว่าในโฟลเดอร์มีไฟล์อยู่หรือไม่

                            }else{
                                return redirect()->back()->with('unsuccess','ลบข้อมูลรูปภาพรายละเอียดโพสดังกล่าว ไม่สำเร็จ!!');
                            }
                        }else{// หากไม่พบว่ามีไฟล์ดังกล่าวอยู่ในโฟลเดอร์
                            return redirect()->back()->with('unsuccess','ลบข้อมูล ไม่สำเร็จ เพราะไม่มีไฟล์ชื่อนี้อยู่ที่โฟลเดอร์ตามที่ระบุ!!');
                        }

                }else{//
                    return redirect()->back()->with('unsuccess','ลบข้อมูลรูปภาพรายละเอียดโพสดังกล่าว ไม่สำเร็จ!!');
                }

            }else{//หากไม่พบหมายเลข id ของ albumImage ในตารางฐานข้อมูลจะให้ออกจากการทำงาน controller นี้
                return redirect()->back()->with('unsuccess','ลบข้อมูลรูปภาพรายละเอียดโพสดังกล่าว ไม่สำเร็จ!!');
            }
        }else{
            return redirect()->back()->with('unsuccess','ลบข้อมูลรูปภาพรายละเอียดโพสดังกล่าว ไม่สำเร็จ!!');
        }
    }
}
