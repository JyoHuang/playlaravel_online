<?php

namespace App\Http\Controllers\message;

use App\Http\Controllers\Controller;
use Validator;
use App\connectDBModel\ImageMessageModel;  //建立自己的Model
use DB;
use Illuminate\Http\Request;
use Log;
use Storage;
use Illuminate\Support\Str;

class ImageMessageController extends Controller
{
    //取得所有圖片的內容
    public function getImageMessage()
    {
        $messages = DB::table('message_image')->get();

        $response = [
            'success' => true,
            'messages' => $messages,
        ];
        return response()->json($response);
    }
    //增加一筆圖片內容
    public function addImageMessage()
    {
        $payload = request()->all();

        //1.有沒有檔案
        if (empty(request()->file('file-to-upload'))) {
            return response()->json(['success' => false, 'message' => '請選擇檔案']);
        }

        $name = request()->file('file-to-upload')->getClientOriginalName();
        //2.選的是不是圖片
        $beginIndex = strripos($name, ".");  //找到abc.png 第一個出現.的位置 =3
        $extension = substr($name, $beginIndex); //取出abc.png 的子字串 從位置$beginIndex=3
        if ($extension != '.jpg' && $extension != '.png' && $extension != '.jpeg') {
            return response()->json(['success' => false, 'message' => '請選擇正確檔案']);
        }

        //3.看一下size對不對
        $validator = Validator::make(
            $payload,
            [
                //'file-to-upload' => 'dimensions:width=800,height=250',
                'file-to-upload' => 'max:10000'
            ]
        );
        if ($validator->fails()) {
            $response = [
                'success' => false,
                //'message' => '請上傳尺寸為800x250的圖',
                'message' => '請上傳尺寸小於10MB的圖',
            ];
            return $response;
        }


        //4.儲存
        $path = request()->file('file-to-upload')->store('public/files');
        $fileName = basename($path);

        

        //5.把路徑存到資料表中
        //新增至資料庫
        $mImageMessageModel = new ImageMessageModel();
        $mImageMessageModel->image_local = $fileName;
        $mImageMessageModel->save();
        $added_id = $mImageMessageModel->id;

        $response = [
            'success' => true,
            'image_local' => $fileName,
            'message_id' => $added_id,
        ];

        return $response;
    }
    //更新一筆圖片的內容
    public function updateImageMessage()
    {
        //取得所有輸入
        $payload = request()->all();
        //建立檢查輸入的規則
        $rules = [
            'id' => [
                'required',
            ]
        ];
        //檢查看看
        $validator = Validator::make($payload, $rules);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->messages(),
            ];
            return response()->json($response);
        }




        //1.有沒有檔案
        if (empty(request()->file('file-to-upload'))) {
            return response()->json(['success' => false, 'message' => '請選擇檔案']);
        }

        $name = request()->file('file-to-upload')->getClientOriginalName();
        //2.選的是不是圖片
        $beginIndex = strripos($name, ".");
        $extension = substr($name, $beginIndex);
        if ($extension != '.jpg' && $extension != '.png' && $extension != '.jpeg') {
            return response()->json(['success' => false, 'message' => '請選擇正確檔案']);
        }

        //3.看一下size對不對
        $validator = Validator::make(
            $payload,
            [
                'file-to-upload' => 'max:10000'
            ]
        );
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => '請上傳尺寸小於10MB的圖',
            ];
            return $response;
        }

        //4.儲存
        $path = request()->file('file-to-upload')->store('public/files');
        $fileName = basename($path);


        //先刪除原本的圖片
        $result = DB::table('message_image')
            ->where('id', $payload['id'])
            ->first();
        if (empty($result)) {
            $response = [
                'success' => false,
                'message' => '不存在',
            ];
            return $response;
        }
        $filePath = $result->image_local;
        $deleteResult = Storage::delete("public/files/" . $filePath);

        //5.把路徑存到資料表中
        //更新至資料庫
        DB::table('message_image')
            ->where('id', $payload['id'])
            ->update(['image_local' => $fileName]);

        $response = [
            'success' => true,
            'imageUrl' => $fileName,
        ];

        return $response;
    }
    //刪除一筆圖片的內容
    public function deleteImageMessage()
    {
        //取得所有輸入
        $payload = request()->all();
        //建立檢查輸入的規則
        $rules = [
            'id' => [
                'required',
            ]
        ];
        //檢查看看
        $validator = Validator::make($payload, $rules);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->messages(),
            ];
            return response()->json($response);
        }

        $result = DB::table('message_image')
            ->where('id', $payload['id'])
            ->first();
        if (empty($result)) {
            $response = [
                'success' => false,
                'message' => '不存在',
            ];
            return $response;
        }

        //先刪除圖片
        $filePath = $result->image_local;
        $deleteResult = Storage::delete("public/files/" . $filePath);


        //再刪除資料庫的內容
        $result = DB::table('message_image')
            ->where('id', $payload['id'])
            ->delete();

        $response = [
            'success' => true,
            'filePath' => $filePath,
            'deleteResult' => $deleteResult
        ];
        return $response;
    }
    //搜尋一筆圖片的內容
    public function searchImageMessage()
    {
        //取得所有輸入
        $payload = request()->all();
        //建立檢查輸入的規則
        $rules = [
            'id' => [
                'required',
            ]
        ];
        //檢查看看
        $validator = Validator::make($payload, $rules);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->messages(),
            ];
            return response()->json($response);
        }

        $result = DB::table('message_image')
            ->where('id', $payload['id'])
            ->get();
        $response = [
            'success' => true,
            'message' => $result,
        ];
        return $response;
    }
}
