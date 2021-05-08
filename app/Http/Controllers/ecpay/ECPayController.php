<?php

namespace App\Http\Controllers\ecpay;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Log;


use \ECPay_PaymentMethod as ECPayMethod;

use Illuminate\Support\Str;

class ECPayController extends Controller
{

    public function DemoPage()
    {
        return redirect('/ecpay_demo/enterpage');
    }

    public function EnterPage()
    {
        //dd(url('/'));
        return view('ecpay.enterpage');
    }

    public function BilllistPage(){
        return view('ecpay.billlistpage');
    }

    public function Topay()
    {
        $input = request()->all();
        $uuid_temp = str_replace("-", "",substr(Str::uuid()->toString(), 0,18));

        request()->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        try {
            $obj = new \ECPay_AllInOne();

            //服務參數
            $obj->ServiceURL  = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";   //服務位置
            $obj->HashKey     = '5294y06JbISpM5x9' ;                                           //測試用Hashkey，請自行帶入ECPay提供的HashKey
            $obj->HashIV      = 'v77hoKGq4kWxNNIS' ;                                           //測試用HashIV，請自行帶入ECPay提供的HashIV
            $obj->MerchantID  = '2000132';                                                     //測試用MerchantID，請自行帶入ECPay提供的MerchantID
            $obj->EncryptType = '1';                                                           //CheckMacValue加密類型，請固定填入1，使用SHA256加密
            //基本參數(請依系統規劃自行調整)
            $MerchantTradeNo = $uuid_temp ;
            $obj->Send['ReturnURL']         = url('/')."/callback" ;    //付款完成通知回傳的網址
            $obj->Send['PeriodReturnURL']         = url('/')."/callback" ;    //付款完成通知回傳的網址
            $obj->Send['ClientBackURL'] = url('/')."/success" ;    //付款完成通知回傳的網址
            $obj->Send['MerchantTradeNo']   = $MerchantTradeNo;                          //訂單編號
            $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');                       //交易時間
            $obj->Send['TotalAmount']       = $input["product_price"];                                      //交易金額
            $obj->Send['TradeDesc']         = "good to drink" ;                          //交易描述
            $obj->Send['ChoosePayment']     = ECPayMethod::Credit ;              //付款方式:Credit
            $obj->Send['IgnorePayment']     = ECPayMethod::GooglePay ;           //不使用付款方式:GooglePay
            //訂單的商品資料
            array_push($obj->Send['Items'], array('Name' => $input["name"], 'Price' => $input["product_price"],
            'Currency' => "元", 'Quantity' => (int) "1", 'URL' => "dedwed"));
            //session()->forget('cart');
            $obj->CheckOut();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        
    }

    public function callback()
    {
        try{
            $input = request()->all();
            Log::info(json_encode($input)); 
            return "1|OK";
        }catch (Exception $e) {
            return "1|OK";
        }
    }

    public function redirectFromECpay () {
        session()->flash('success', 'Order success!');
        return redirect('/ecpay_demo/billlistpage');
    }
}
