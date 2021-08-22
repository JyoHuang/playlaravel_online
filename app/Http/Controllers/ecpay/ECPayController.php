<?php

namespace App\Http\Controllers\ecpay;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Log;
use Exception;


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
        //dd($input);
        //dump($input);
        //return;
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



    //!!!!!!!! 用下面這些就好 !!!!!!!
    //接收訂單前往付款
    public function acceptorderTopay(){
        $input = request()->all();
        $product_names = $input['product_names'];
        $product_prices = $input['product_prices'];
        $product_amounts = $input['product_amounts'];

        //dd($input);
        $uuid_temp = str_replace("-", "",substr(Str::uuid()->toString(), 0,18));
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
            $obj->Send['ReturnURL']         = url('/')."/acceptorderTopayCallback" ;    //付款完成通知回傳的網址
            $obj->Send['PeriodReturnURL']         = url('/')."/acceptorderTopayCallback" ;    //付款完成通知回傳的網址
            $obj->Send['ClientBackURL'] = url('/')."/acceptorderTopayOK" ;    //付款完成通知回傳的網址
            $obj->Send['MerchantTradeNo']   = $MerchantTradeNo;                          //訂單編號
            $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');                       //交易時間
            $obj->Send['TradeDesc']         = "good to drink" ;                          //交易描述
            $obj->Send['ChoosePayment']     = ECPayMethod::Credit ;              //付款方式:Credit
            $obj->Send['IgnorePayment']     = ECPayMethod::GooglePay ;           //不使用付款方式:GooglePay
            //訂單的商品資料
            //array_push($obj->Send['Items'], array('Name' => $input["name"], 'Price' => $input["product_price"],
            //'Currency' => "元", 'Quantity' => (int) "1", 'URL' => "dedwed"));
            $totalPrice = 0;
            for($i = 0 ; $i < count($product_names); $i++){
                array_push($obj->Send['Items'], array('Name' => $product_names[$i], 'Price' =>  $product_prices[$i],
                'Currency' => "元", 'Quantity' => (int) $product_amounts[$i], 'URL' => "dedwed"));
                $totalPrice = $totalPrice + $product_prices[$i]*$product_amounts[$i];
            }
            $obj->Send['TotalAmount'] = $totalPrice;         //交易總金額
            $obj->CheckOut();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    //成功綠界會把資料傳到這個地方
    public function acceptorderTopayCallback(){
        try{
            $input = request()->all();
            Log::info(json_encode($input)); 
            //這邊可以做一些把訂單資料存到資料庫的動作
            return "1|OK";
        }catch (Exception $e) {
            return "1|OK";
        }
    }
    //成功會將頁面導到這邊
    public function acceptorderTopayOK(){
        return view('ecpay.enterpage');
    }
}
