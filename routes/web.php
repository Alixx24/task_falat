<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); 
});



// Route::get('/notes', function () {
//     return view('notes.index');  
// })->name('notes.index');


// Route::get('/test-sms', function() {
//     try {
//         $sender = "2000660110 "; // شماره اختصاصی شما از پنل Kavenegar
//         $message = "پیام تست از لاراول";
//         $receptor = ["09123456789"]; // شماره گیرنده واقعی

//         $result = \Kavenegar\Laravel\Facade::Send($sender, $receptor, $message);

//         if($result){
//             foreach($result as $r){
//                 echo "messageid = $r->messageid<br>";
//                 echo "status = $r->status<br>";
//                 echo "statustext = $r->statustext<br>";
//             }       
//         }
//     } catch(\Kavenegar\Exceptions\ApiException $e){
//         echo "Api Error: ".$e->errorMessage();
//     } catch(\Kavenegar\Exceptions\HttpException $e){
//         echo "Http Error: ".$e->errorMessage();
//     }
// });

use App\Services\KavenegarService;

Route::get('/test-sms', function(KavenegarService $sms) {
    $result = $sms->sendSms('09020160120', 'پیام تست حرفه‌ای');

    dd($result); // نمایش نتیجه
});
