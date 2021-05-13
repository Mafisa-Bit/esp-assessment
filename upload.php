<?php
include_once 'Image.php';

$image = new Image();
$upload = $_FILES['image'];

if (!empty($upload['name']) && $image->checkFileType($upload) == 1) {
    $image->save($upload, $_POST['entity']);

}