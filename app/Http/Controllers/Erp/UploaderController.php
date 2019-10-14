<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploaderController extends Controller
{
    // 图片异步上传
    public function picUpload(Request $request){
        if($request->hasFile('file') && $request->file('file')->isValid()){
            $filepath = date('Ymd',time());

            /*if(!is_dir($filepath)){
                $res=mkdir(iconv("UTF-8", "GBK", $filepath),0777,true);
                if(!$res){
                    return false;
                }
            }*/
            //判断文件是否上传成功
            $filename = sha1(time().$request->file('file')->getClientOriginalName()).
                '.'.$request->file('file')->getClientOriginalExtension();
            //保存移动文件
            Storage::disk('public')->put($filepath.'/'.$filename,file_get_contents($request->file('file')->path()));

            //返回数据
            $result = [
                'code' => '0',
                'errMsg' => '',
                'succMsg' => '文件上传成功！',
                'path' => '/storage/'.$filepath.'/'.$filename,
            ];
        }else{
            $result = [
                'code' => '1',
                'errMsg' => $request->file('file')->getErrorMessage(),
            ];
        }
        //返回信息
        return response()->json($result);
    }




}
