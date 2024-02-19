<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ImageUpload
{

    /**
     * @param Request $request
     * @return $this|false|string
     */
    public function upload_image_trait($file,$type)
    {
        $newname = uniqid() . '-'.$type."." . $file->getClientOriginalExtension();
        $destinationPath = public_path() .'/uploads/';
        $file->move($destinationPath, $newname);
        return $newname;
    }


    public function delete_image_trait($file_name)
    {
        $file_Path = public_path() . '/uploads/' . $file_name;
        if (is_file($file_Path) && file_exists($file_Path)) {
            unlink($file_Path);
        }
        return true;
    }

}
