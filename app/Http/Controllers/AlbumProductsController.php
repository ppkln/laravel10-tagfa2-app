<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\products;
use App\Models\User;
use App\Models\albumproducts;

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
                $PathfolderProduct="../public/products_img/".$productcover_folder."/";//ชื่อโฟลเดอร์ที่จัดเก็บข้อมูลรูปภาพของสินค้าชิ้นนี้
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
                $PathfolderProduct="../public/products_img/".$productcover_folder."/";//ชื่อโฟลเดอร์ที่จัดเก็บข้อมูลรูปภาพของสินค้าชิ้นนี้
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
}
