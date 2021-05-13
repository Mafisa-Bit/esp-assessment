<?php


class Image
{

    const STAFF = 250;
    const PRODUCT = 300;

    /*
     * Check if Image/File type
     */

    public function save($image, $entity)
    {
        if ($this->checkFileType($image) == 1) {
            $this->resize($image, $entity);
        }
    }

    public function checkFileType($image)
    {
        $file_ext = $this->getFileExt($image);
        if ($file_ext != "jpg" && $file_ext != "jpeg" && $file_ext != "png") {
            return 'Invalid Image type';
        } else {
            move_uploaded_file($image['tmp_name'], "images/" . $image['name']);
            return true;
        }
    }

    public function resize($image, $entity)
    {
        list($width, $height) = getimagesize($image);

        if ($entity == 'staff') {
            $new_width = self::STAFF;
            $new_height = self::STAFF;
        } else {
            $new_width = self::PRODUCT;
            $new_height = self::PRODUCT;
        }

        $new_image = imagecreatetruecolor($new_width, $new_height);


        switch ($this->getFileExt($image)):
            case 'png':
                $php_image = imagecreatefrompng($image['tmp_name']);
                imagecopyresized($new_image, $php_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

            case 'jpg' || 'jpeg':
                $php_image = imagecreatefromjpeg($image['tmp_name']);
                imagecopyresized($new_image, $php_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        endswitch;



    }


    public function crop_image($image, $width, $height)
    {
        switch ($this->getFileExt($image)):
            case 'png':
                $im = imagecreatefrompng($image['tmp_name']);
                $size = min(imagesx($im), imagesy($im));
                $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $width, 'height' => $height]);
                if ($im2 !== FALSE) {
                    imagepng($im2, $image['tmp_name'] . 'cropped.' . $this->getFileExt($image));
                    imagedestroy($im2);
                }
                imagedestroy($im);

            case 'jpg' || 'jpeg':
                $im = imagecreatefromjpeg($image['tmp_name']);
                $size = min(imagesx($im), imagesy($im));
                $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
                if ($im2 !== FALSE) {
                    imagejpeg($im2, $image['tmp_name'] . 'cropped.' . $this->getFileExt($image));
                    imagedestroy($im2);
                }
                imagedestroy($im);

        endswitch;

    }

    public function square_crop($image)
    {
        switch ($this->getFileExt($image)):
            case 'png':
                $im = imagecreatefrompng($image['tmp_name']);
                $this->processImage($im, $image);

            case 'jpg' || 'jpeg':
                $im = imagecreatefromjpeg($image['tmp_name']);
                $this->processImage($im, $image);

        endswitch;

    }

    private function processImage($im, $image)
    {
        $size = min(imagesx($im), imagesy($im));
        $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);

        if ($im2 !== FALSE) {

            imagejpeg($im2, $image['tmp_name'] . 'cropped.' . $this->getFileExt($image));
            imagedestroy($im2);
        }
        imagedestroy($im);
    }

    private function getFileExt($image)
    {
        return strtolower(end(explode('.', $image['name'])));
    }


}