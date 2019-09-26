<?php

namespace App\Handlers;

class ImageUploadHandler
{
    //只允许一下后缀的图片上传
    protected $allowed_ext = ['png', 'jpg', 'gif', 'jpeg'];

    public function save($file, $folder, $file_prefix)
    {
        //构建存储文件的规则，直如uploads/images/avatars/201909/22
        //文件夹切割能让查找效率更高
        $folder_name = "uploads/images/$folder/" . date("Ym/d", time());

        //文件具体存贮的物理路径，`public_path()`,获取`public` 的文件的物理路径。
        //植入
        $upload_path = public_path() . '/' . $folder_name;
        //获取文件后缀名，因图片从剪切板里黏贴时后缀名为空，所以此处确保后缀名一直存在
        $extension =strtolower($file->getClientOriginalExtension()) ?:'png';

        //拼接文件名，加前缀是为了增加辨析度，前缀可以是相关数据模型的OID
        $filename =$file_prefix . '_' . time() .'_' .str_random(10) . '.' .$extension;

        //如果上传的不是图片将停止操作
        if(! in_array($extension ,$this->allowed_ext)){
            return false;
        }
        //将图片移动的到我们的目标存储路径中
        $file->move($upload_path,$filename);

        return  [
            'path'=>config('app.url')."/$folder_name/$filename"
        ];
    }
}
