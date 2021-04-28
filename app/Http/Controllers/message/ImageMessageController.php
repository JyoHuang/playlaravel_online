<?php

namespace App\Http\Controllers\message;

use App\Http\Controllers\Controller;
use Validator;
use App\connectDBModel\ImageMessageModel;  //建立自己的Model
use DB;
use Illuminate\Http\Request;
use Log;
use Storage;

class ImageMessageController extends Controller
{

    public function getImageMessage()
    {
        $messages = DB::table('message_image')->get();

        $response = [
            'success' => true,
            'messages' => $messages,
        ];
        return response()->json($response);
    }

    public function addImageMessage()
    {
        $payload = request()->all();

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

        $domain_name = request()->root();
        $imageUrl = $domain_name . '/storage/files/' . $fileName;

        //5.把路徑存到資料表中
        //新增至資料庫
        $mImageMessageModel = new ImageMessageModel();
        $mImageMessageModel->image_local = $imageUrl;
        $mImageMessageModel->save();
        $added_id = $mImageMessageModel->id;

        $response = [
            'success' => true,
            'imageUrl' => $imageUrl,
            'message_id' => $added_id,
        ];

        return $response;
    }

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

        $domain_name = request()->root();
        $imageUrl = $domain_name . '/storage/files/' . $fileName;


        //4刪除原本的圖片
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
        $image_local_to_delete = $result->image_local;
        $indexOfFilePath = strpos($image_local_to_delete, 'files');
        $filePath = substr($image_local_to_delete, $indexOfFilePath);
        $deleteResult = Storage::delete("public/" . $filePath);

        //5.把路徑存到資料表中
        //更新至資料庫
        DB::table('message_image')
            ->where('id', $payload['id'])
            ->update(['image_local' => $imageUrl]);

        $response = [
            'success' => true,
            'imageUrl' => $imageUrl,
        ];

        return $response;
    }
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
        $image_local_to_delete = $result->image_local;
        $indexOfFilePath = strpos($image_local_to_delete, 'files');
        $filePath = substr($image_local_to_delete, $indexOfFilePath);
        $deleteResult = Storage::delete("public/" . $filePath);

        //再刪除資料庫的內容
        $result = DB::table('message_image')
            ->where('id', $payload['id'])
            ->delete();

        $response = [
            'success' => true,
            'image_local_to_delete' => $image_local_to_delete,
            'filePath' => $filePath,
            'deleteResult' => $deleteResult
        ];
        return $response;
    }
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
