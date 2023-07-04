<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            สวัสดีคุณ {{Auth::User()->name}} ระดับการทำงาน: {{Auth::User()->lv_working}}
        </h2>
    </x-slot>
    <div class="container d-flex p-2">
        <div class="products-con">
            <div class="products-item">
                <div class="products-img">
                    <img src="/logo-nueng1.png" >
                </div>
                <div class="products-detail">
                    รายละเอียดสินค้า 1
                </div >
                <div class="products-price">
                    <div class="products-left">
                        ฿3500.00 Baht
                    </div >
                    <div class="products-right">
                        ขายแล้ว 200 ชิ้น
                    </div >
                </div >
            </div>
        </div>
    </div>

    <!-- script of fancybox -->
    <script>
      Fancybox.bind('[data-fancybox="gallery"]', {
        //
      });
    </script>

</x-app-layout>

