<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\products;
use App\Models\User;
use App\Models\albumproducts;
use Illuminate\Console\View\Components\Alert;

class AlbumProductsController extends Controller
{
    public function index(){

    }

    public function albumQueryId($id){
        $product_album = products::find($id);
        if($product_album){
            $album_data = albumproducts::where('product_no', $product_album->product_no)->get();
            return view('backend.albumproducts.addimgs',compact('product_album','album_data'));
        }else {
            return view('welcome');
        }

    }

    public function albumAddImg(Request $request){
        $request->validate([
            'album_img' => 'required',
            'album_img.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ],[
                'album_img.required' =>"กรุณาระบุรูปภาพสินค้า",
                'album_img.max' =>"ขนาดไฟล์ไม่เกินไฟล์ละ 2 MB",
                'album_img.image' =>"รองรับเฉพาะไฟล์นามสกุล jpg,jpeg,png เท่านั้น",
        ]);
        $product_no = $request->product_no;
        $productcover_folder = $request->productcover_folder;
        $user_id = $request->user_id;
        if($request->file('album_img')){
            //ตรวจสอบว่าได้มีข้อมูลหมายเลข Product_no นี้อยู่ในฐานข้อมูลแล้วหรือไม่ หากมีจะต้องใช้เลข album_no เดิม
            $album_Oldno = albumproducts::where('product_no',$product_no)->first();
            if($album_Oldno){//กรณีเคยบันทึกข้อมูลรูปภาพเพิ่มเติมของสินค้าตัวนี้
                $album_NO = $album_Oldno->album_no;
                $PathfolderProduct="./products_img/".$productcover_folder."/";//ชื่อโฟลเดอร์ที่จัดเก็บข้อมูลรูปภาพของสินค้าชิ้นนี้
                foreach($request->file('album_img') as $key=>$value){
                    $imgNewName = time().rand(1,99).'_albPD.'.$value->extension(); //ตั้งชื่อใหม่ให้รูปภาพด้วยวิธีการ random ชื่อ
                    if($value->move($PathfolderProduct.$album_NO,$imgNewName)){

                        $album_data = new albumproducts;
                        $album_data->album_no = $album_NO;
                        $album_data->product_no = $product_no;
                        $album_data->img_name = $imgNewName;
                        $album_data->user_id = $user_id;
                        $album_data->save();

                    };
                }
                return redirect()->back()->with('success','เพิ่มข้อมูลรูปภาพสำเร็จแล้ว');
            }else{//กรณีไม่เคยบันทึกข้อมูลรูปภาพเพิ่มเติมของสินค้าตัวนี้
                $album_NO = "ALBNO_".hexdec(uniqid());//สร้างหมายเลข Album สินค้า ด้วยวิธีการสุ่มตั้งชื่อด้วยเลขฐาน16
                $PathfolderProduct="./products_img/".$productcover_folder."/";//ชื่อโฟลเดอร์ที่จัดเก็บข้อมูลรูปภาพของสินค้าชิ้นนี้
                $mkdirfolder = mkdir($PathfolderProduct.$album_NO,0777,true); //คำสั่งให้ทำการสร้าง Folder ย่อยภายใน folder ที่เก็บภาพสินค้า
                if($mkdirfolder){
                    foreach($request->file('album_img') as $key=>$value){
                        $imgNewName = time().rand(1,99).'_albPD.'.$value->extension(); //ตั้งชื่อใหม่ให้รูปภาพด้วยวิธีการ random ชื่อ
                        if($value->move($PathfolderProduct.$album_NO,$imgNewName)){

                            $album_data = new albumproducts;
                            $album_data->album_no = $album_NO;
                            $album_data->product_no = $product_no;
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
            $albumImgDel = albumproducts::find($id);
            if($albumImgDel){
                $album_no = $albumImgDel->album_no; //ได้ชื่อโฟลเดอร์ที่เก็บรูปภาพรายละเอีบดสินค้า
                $albumImg_name = $albumImgDel->img_name;//ได้ชื่อรูปภาพ
                $product_no = $albumImgDel->product_no;//ได้หมายเลขสินค้า
                $pathProduct_no = products::where('product_no', $product_no)->first();
                if($pathProduct_no){
                    $ProductCover_folder = $pathProduct_no->productcover_folder;//ได้ชื่อโฟลเดอร์ที่เก็บรูปภาพปกสินค้า และเก็บไว้ในตัวแปร $ProductCover_folder
                    $pathDelalbumImg = "./products_img/".$ProductCover_folder."/".$album_no ; // path สำหรับการลบไฟล์
                        if(file_exists($pathDelalbumImg."/".$albumImg_name)){//หากมีไฟล์ชื่อนี้จริงใน path ที่ระบุ
                            if(unlink($pathDelalbumImg."/".$albumImg_name)){//ทำการลบข้อมูลรูปรายละเอียดสินค้าแต่ละรายการในตารางฐานข้อมูล
                                $delImgAlbum = albumproducts::find($id)->delete();
                                // Start ทำการตรวจสอบว่าในโฟลเดอร์มีไฟล์อยู่หรือไม่
                                $dirFolderAlbum = opendir($pathDelalbumImg);
                                $data ="";
                                $fcount = 0;
                                while($data = readdir($dirFolderAlbum)){
                                    $fcount = $fcount+1;
                                }
                                if(($fcount < 3)){
                                    rmdir($pathDelalbumImg); // ทำการลบโฟลเดอร์รูปภาพรายละเอียดสินค้า
                                    return redirect()->back()->with('success','ลบข้อมูลรูปภาพรายละเอียดสินค้า พร้อมทั้ง Folder ที่จัดเก็บรูปภาพสำเร็จ');
                                }else{
                                    return redirect()->back()->with('success','ลบข้อมูลเฉพาะรูปภาพรายละเอียดสินค้าสำเร็จ');
                                }
                                closedir($dirFolderAlbum);
                                // End ทำการตรวจสอบว่าในโฟลเดอร์มีไฟล์อยู่หรือไม่

                            }else{
                                return redirect()->back()->with('unsuccess','ลบข้อมูลรูปภาพรายละเอียดสินค้าดังกล่าว ไม่สำเร็จ!!');
                            }
                        }else{// หากไม่พบว่ามีไฟล์ดังกล่าวอยู่ในโฟลเดอร์
                            return redirect()->back()->with('unsuccess','ลบข้อมูล ไม่สำเร็จ เพราะไม่มีไฟล์ชื่อนี้อยู่ที่โฟลเดอร์ตามที่ระบุ!!');
                        }

                }else{//
                    return redirect()->back()->with('unsuccess','ลบข้อมูลรูปภาพรายละเอียดสินค้าดังกล่าว ไม่สำเร็จ!!');
                }

            }else{//หากไม่พบหมายเลข id ของ albumImage ในตารางฐานข้อมูลจะให้ออกจากการทำงาน controller นี้
                return redirect()->back()->with('unsuccess','ลบข้อมูลรูปภาพรายละเอียดสินค้าดังกล่าว ไม่สำเร็จ!!');
            }
        }else{
            return redirect()->back()->with('unsuccess','ลบข้อมูลรูปภาพรายละเอียดสินค้าดังกล่าว ไม่สำเร็จ!!');
        }
    }
}
