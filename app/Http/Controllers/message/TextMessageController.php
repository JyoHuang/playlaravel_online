<?php

namespace App\Http\Controllers\message;

use App\Http\Controllers\Controller;
use Validator;
use App\connectDBModel\TextMessageModel;  //建立自己的Model
use DB;
use Illuminate\Http\Request;

class TextMessageController extends Controller
{
    //取得所有的文字訊息
    public function getTextMessage()
    {
        $messages = DB::table('message_text')->get();
        //包裝成JSON格式回傳
        $response = [
            'success' => true,
            'messages' => $messages,
        ];
        return response()->json($response);
    }
    //增加一筆文字訊息
    public function addTextMessage()
    {
        //取得所有輸入
        $input = request()->all();
        //建立檢查輸入的規則
        $rules = [
            'message' => [
                'required',
                'max:15',
            ]
        ];
        //檢查看看輸入的東西有沒有問題
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->messages(),
            ];
            return response()->json($response);
        }

        //新增至資料庫
        $mTextMessageModel = new TextMessageModel();
        $mTextMessageModel->message = $input['message'];
        $mTextMessageModel->save();
        $added_id = $mTextMessageModel->id;

        $response = [
            'success' => true,
            'message_id' => $added_id,
        ];
        return response()->json($response);
    }
    //更新某一筆文字訊息
    public function updateTextMessage()
    {
        //取得所有輸入
        $input = request()->all();
        //建立檢查輸入的規則
        $rules = [
            'message' => [
                'required',
                'max:15',
            ],
            'id' => [
                'required',
            ]
        ];
        //檢查看看
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->messages(),
            ];
            return response()->json($response);
        }

        //更新至資料庫
        DB::table('message_text')
            ->where('id', $input['id'])
            ->update(['message' => $input['message']]);
        $response = [
            'success' => true,
            'message' => $input['message'],
        ];
        return response()->json($response);
    }
    //刪除某一筆文字的訊息
    public function deleteTextMessage()
    {
        //取得所有輸入
        $input = request()->all();
        //建立檢查輸入的規則
        $rules = [
            'id' => [
                'required',
            ]
        ];
        //檢查看看
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->messages(),
            ];
            return response()->json($response);
        }

        //更新至資料庫
        DB::table('message_text')
            ->where('id', $input['id'])
            ->delete();
        $response = [
            'success' => true,
        ];
        return response()->json($response);
    }
    //搜尋某一筆文字的訊息
    public function searchtextmessage()
    {
        //取得所有輸入
        $input = request()->all();
        //建立檢查輸入的規則
        $rules = [
            'id' => [
                'required',
            ]
        ];
        //檢查看看
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->messages(),
            ];
            return response()->json($response);
        }

        $messages = DB::table('message_text')
            ->where('id', $input['id'])
            ->get();

        $response = [
            'success' => true,
            'messages' => $messages,
        ];
        return response()->json($response);
    }
}
