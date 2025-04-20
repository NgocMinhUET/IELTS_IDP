<?php

namespace App\Services;


class BaseService
{
    /**
     * Get urlFile local
     *
     * @param $file
     * @param string $folder
     * @return string
     */
    public function getUrlFile($file, string $folder): string
    {
        $url = '';
        if ($file !== null) {
            $image = date('Y_m_d'). '_' . time() . $file->getClientOriginalName();
            $file->move('images/' . $folder, $image);
            $url = 'images/' . $folder . '/' . $image;
        }

        return $url;
    }

    /**
     * Upload multiple file local
     *
     * @param array  $files
     * @param string $folder
     * @return array[]
     */
    public function uploadFileMultiple(array $files, string $folder): array
    {
        $name = [];
        $original_name = [];
        foreach ($files as $key => $value) {
            $image = uniqid() . time() . '.' . $value->getClientOriginalExtension();
            $destinationPath = public_path(). '/images/' . $folder;
            $value->move($destinationPath, $image);
            $name[] = $image;
            $original_name[] = $value->getClientOriginalName();
        }

        return [
            'name'          => $name,
            'original_name' => $original_name
        ];
    }

    /**
     * Get slug
     *
     * @param string $str
     * @return array|string|string[]|null
     */
    public function getSlug(string $str): array|string|null
    {
        if (!$str) {
            return '';
        }
        $unicode = [
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'd' => 'đ|Đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'i' => 'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ',
            '' => '(|)|{|}|*|!|#|@|$|%|^|&|+|_|:|?|<|>|]|[|-|.|`,',
        ];
        foreach ($unicode as $ascii => $uni) {
            $arr = explode("|", $uni);
            $str = str_replace($arr, $ascii, $str);
        }
        $str = strtolower($str);
        $str = str_replace(' ', '-', $str);

        return preg_replace('/-+/', '-', $str);
    }
}
