<?php

namespace App\Http\Controllers\email;

use App\Http\Controllers\Controller;
use Validator;
use App\connectDBModel\TextMessageModel;  //建立自己的Model
use DB;
use Illuminate\Http\Request;
use Mail;

class EmailController extends Controller
{
    //寄送Email api
    public function sendemail(Request $request)
    {   
        $name = $request->input('name');
        $email = $request->input('email');
        
        //產生隨機驗證碼
        $verifyCode = rand(1111, 9999);

        //更新至資料表
        /*
        DB::table('user')->where('email', $email)
            ->update(array('verifyCode' => $verifyCode,));
        */

        $mail_binding = [
            'name' => $name,
            'email' => $email,
            'verifyCode' => $verifyCode,
        ];
        Mail::send('emailview.verifyCodeEmail', $mail_binding,
            function($mail) use ($request){
                //收件人
                $mail->to($request->input('email'));
                //寄件人
                $mail->from('createdigit@gmail.com');
                //郵件主旨
                $mail->subject('恭喜註冊 找蔬舒 成功，請將驗證碼填寫至APP');
            });

        //包裝成JSON格式回傳
        $response = [
            'success' => true
        ];
        return response()->json($response);
    }

    
}
