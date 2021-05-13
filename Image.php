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
        try {

            $file_ext = $this->getFileExt($image);
            if ($file_ext != "jpg" && $file_ext != "jpeg" && $file_ext != "png") {
                return 'Invalid Image type';
            } else {
                move_uploaded_file($image['tmp_name'], "images/" . $image['name']);
                return true;
            }

        } catch (Exception $e) {

        }

    }

    public function resize($image, $entity)
    {
        try {
            list($width, $height) = getimagesize('images/' . $image['name']);

            $php_image = null;

            if ($this->getFileExt($image) == 'png') {
                $php_image = imagecreatefrompng('images/' . $image['name']);
            } else {
                $php_image = imagecreatefromjpeg('images/' . $image['name']);
            }

            if ($entity == 'staff') {
                $new_image = imagecreatetruecolor(self::STAFF, self::STAFF);

                imagecopyresampled($new_image, $php_image, 0, 0, 0, 0, new $width, new $height, self::STAFF, self::STAFF);

            } else {
                $new_image = imagecreatetruecolor(self::PRODUCT, self::PRODUCT);
                imagecopyresampled($new_image, $php_image, 0, 0, 0, 0, new $width, new $height, self::PRODUCT, self::PRODUCT);
            }


        } catch (Exception $e) {

        }


    }


    public function process_image($image, $width, $height)
    {
        try {
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

        } catch (Exception $e) {

        }


    }

    public function square_crop($image)
    {
        $im = null;

        if ($this->getFileExt($image) == 'png') {
            $im = imagecreatefrompng($image['tmp_name']);

        } else {
            $im = imagecreatefromjpeg($image['tmp_name']);

        }
        $this->image_crop($im, $image);
    }

    private function image_crop($im, $image)
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