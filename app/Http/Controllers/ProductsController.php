<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\products;
use App\Models\User;
use DateTime;
use Illuminate\Validation\Rules\Unique;

class ProductsController extends Controller
{
    //
    public function index(){
        $products = products::all();
        $productlist = products::paginate(4);
        return view('backend.products.index',compact('products','productlist'));
    }

    public function insertProduct(Request $request){
        $request->validate([
            'product_title' =>'required|unique:products|max:255',
            'product_description'=>'required',
            'product_unit'=>'required|max:100',
            'product_price'=>'required|max:100',
            'productcover_img'=>'required',
        ],[
            'product_title.required' =>"กรุณากรอกชื่อหัวข้อสินค้า",
            'product_title.max' =>"กรอกข้อมูลได้ไม่เกิน 255 ตัวอักษร",
            'product_title.unique'=>"กรอกชื่อหัวข้อสินค้าซ้ำ",
            'product_description.required' =>"กรุณากรอกรายละเอียดสินค้า",
            'product_unit.required' => "กรุณากรอกหน่วยนับของสินค้า",
            'product_unit.max' => "กรอกข้อมูลได้ไม่เกิน 100 ตัวอักษร",
            'product_price.required' => "กรุณากรอกราคาต่อหน่วยของสินค้า",
            'product_price.max' => "กรอกข้อมูลได้ไม่เกิน 100 ตัวอักษร",
            'productcover_img.required' =>'กรุณาระบุรูปปกสินค้า',
        ]);
        //จัดการเกี่ยวกับชื่อไฟล์รูปภาพ
        $fileSizeupload = $request->file('productcover_img')->getSize();//เก็บค่าขนาดของไฟล์ที่อัพโหลด
        if($fileSizeupload <= 2*1024*1024 && $fileSizeupload > 0){ //ตรวจสอบขนาดไฟล์ที่อัพโหลดต้องไม่่เกิน 2 MB.
            $cover_img = $request->file('productcover_img');//นำค่าไฟล์ที่ส่งผ่านด้วย method:post มาเก็บในตัวแปล
            $img_ext = strtolower($cover_img->getClientOriginalExtension()); //ทำการดึงชื่อนามสกุลของไฟล์พร้อมกับแปลงเป็นอักษรพิมพ์เล็ก
            $newFilenameupload = mktime(date('H'),date('i'),date('s'),date('d'),date('m'),date('Y'))."coverImgPD.".$img_ext; // ตั้งชื่อใหม่ให้ไฟล์ภาพที่เราอัพโหลด ด้วยการประยุกต์ใช้รูปแบบของวันเวลาปัจจุบันมากำหนดเป็นชื่อ
            //สร้าง folder เพื่อทำเป็น path เป้าหมายปลายทางที่ใช้จัดเก็บไฟล์รูปภาพ
            $random_productFD = "PDFD_".hexdec(uniqid());//สร้างชื่อ folder สินค้า ด้วยวิธีการสุ่มตั้งชื่อด้วยเลขฐาน16
            $Pathfolderupload="../public/products_img/";//โฟลเดอร์เริ่มต้นที่ public
            $mkdirfolder = mkdir($Pathfolderupload.$random_productFD,0777,true); //คำสั่งให้ทำการสร้าง Folder ที่ server
            //upload ไฟล์ไปที่ server
            if($mkdirfolder){
                if($cover_img->move($Pathfolderupload.$random_productFD,$newFilenameupload)){
                    //บันทีกข้อมูลลงตารางข้อมูล เมื่อตรวจสอบพบว่ารูปภาพถูกอัพโหลดไปที่ server สำเร็จจริง
                    $random_productID = "PDID_".hexdec(uniqid());//สร้างรหัสสินค้าด้วยวิธีการสุ่มตั้งชื่อด้วยเลขฐาน 16 เพื่อเอาไว้ใช้ตอนบันทึกลงตารางฐานข้อมูล

                    $productdata = new products;
                    $productdata->product_no = $random_productID;
                    $productdata->product_title = $request->product_title;
                    $productdata->product_description = $request->product_description;
                    $productdata->productcover_folder = $random_productFD."/";//ชื่อ path folder ของรูปภาพปกสินค้าที่ server สร้างขึ้นใหม่
                    $productdata->productcover_img = $newFilenameupload;//ชื่อใหม่ของรูปภาพปกสินค้าที่ server สร้างขึ้นใหม่
                    $productdata->product_price = $request->product_price;
                    $productdata->product_unit = $request->product_unit;
                    $productdata->user_id = $request->user_id;
                    $productdata->product_category = 'uncategorized';
                    $productdata->publish_status = 0;
                    $productdata->save();

                    //return view('backend.products.index',compact('products','productlist'))->with('success','บันทึกข้อมูลสินค้าเรียบร้อย');
                    return redirect()->back()->with('success','บันทึกข้อมูลเรียบร้อย');
                };
            };
        }else{
            return redirect()->back()->with('unsuccess','บันทึกข้อมูลไม่สำเร็จ เพราะขนาดของไฟล์มากกว่า 3 MB หรือไม่มีการอัพโหลดไฟล์');
        };

    }

    public function editProduct($id){
        $productEdit = products::find($id);
        return view('backend.products.editProduct',compact('productEdit'));
    }

    public function updateProduct(Request $request,$id){
        $request->validate([
            'product_title' =>'required|max:255',
            'product_unit'=>'required|max:100',
            'product_price'=>'required|max:100',
        ],[
            'product_title.required' =>"กรุณากรอกชื่อหัวข้อสินค้า",
            'product_title.max' =>"กรอกข้อมูลได้ไม่เกิน 255 ตัวอักษร",
            'product_title.unique'=>"กรอกชื่อหัวข้อสินค้าซ้ำ",
            'product_description.required' =>"กรุณากรอกรายละเอียดสินค้า",
            'product_unit.required' => "กรุณากรอกหน่วยนับของสินค้า",
            'product_unit.max' => "กรอกข้อมูลได้ไม่เกิน 100 ตัวอักษร",
            'product_price.required' => "กรุณากรอกราคาต่อหน่วยของสินค้า",
            'product_price.max' => "กรอกข้อมูลได้ไม่เกิน 100 ตัวอักษร",
        ]);
        $date_update = new DateTime();
        //จัดการเกี่ยวกับชื่อไฟล์รูปภาพ
        $productcover_img = $request->file('productcover_img');
        $productcover_folder = $request->productcover_folder;//โฟลเดอร์ที่จัดเก็บภาพของสินค้านี้ไว้ มาจาก component input type:hidden
        if($request->publish_status){
            $publish_status = 1;
        } else {
            $publish_status = 0;
        }
        if($productcover_img){//หากมีการเลือกไฟล์รูปภาพใหม่เพื่อแก้ไขแทนรูปเดิมจริง
            $fileSizeupload = $request->file('productcover_img')->getSize();//เก็บค่าขนาดของไฟล์ที่อัพโหลดใหม่
            if($fileSizeupload <= 2*1024*1024 ){//จำกัดขนาดไฟล์ที่อัพโหลด
                $img_ext = strtolower($productcover_img->getClientOriginalExtension()); //ทำการดึงชื่อนามสกุลของไฟล์พร้อมกับแปลงเป็นอักษรพิมพ์เล็ก
                $newFilenameupload = mktime(date('H'),date('i'),date('s'),date('d'),date('m'),date('Y'))."coverImgPD.".$img_ext; // ตั้งชื่อใหม่ให้ไฟล์ภาพที่เราอัพโหลด ด้วยการประยุกต์ใช้รูปแบบของวันเวลาปัจจุบันมากำหนดเป็นชื่อ
                $Pathfolderupload="../public/products_img/".$productcover_folder;//พาทที่จะทำการอัพโหลดรูปภาพไปเก็บที่ Server ซึ่งอยู่ภายใต้ folder เดิม
                if($productcover_img->move($Pathfolderupload,$newFilenameupload)){
                    products::find($id)->update([
                        'product_title'=>$request->product_title,
                        'product_description'=>$request->product_description,
                        'productcover_img'=>$newFilenameupload,
                        'product_unit'=>$request->product_unit,
                        'product_price'=>$request->product_price,
                        'publish_status'=>$publish_status,
                        'updated_at'=>$date_update
                    ]);
                    $old_img = $request->Old_img;//ชื่อไฟล์รูปภาพเดิม มาจาก component hidden
                    unlink($Pathfolderupload.$old_img); // ลบภาพเดิมที่ถูกแก้ไขแทนที่
                    return redirect()->back()->with('success','แก้ไขข้อมูลสำเร็จแล้ว');
                }else{
                    return redirect()->back()->with('unsuccess','แก้ไขข้อมูลไม่สำเร็จ');
                }
            }else{
                return redirect()->back()->with('unsuccess','แก้ไขข้อมูลไม่สำเร็จ เพราะขนาดของไฟล์มากกว่า 3 MB ');
            };
        }else {//กรณีแก้ไขเพียงข้อความ ไม่มีการแก้ไขรูปภาพ
            products::find($id)->update([
                'product_title'=>$request->product_title,
                'product_description'=>$request->product_description,
                'product_unit'=>$request->product_unit,
                'product_price'=>$request->product_price,
                'publish_status'=>$publish_status,
                'updated_at'=>$date_update
            ]);
            return redirect()->back()->with('success','แก้ไขข้อมูลสำเร็จแล้ว');
        };
    }
}
