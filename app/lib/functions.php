<?php
function get($name, $def='') {
    return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $def;
}

//A generator for ID
function getID()
{
    $file_name = 'ids';
    if (!file_exists($file_name)) {
        touch($file_name);
        $handle = fopen($file_name, 'r+');
        $id = 0;
    } else {
        $handle = fopen($file_name, 'r+');
        $id = fread($handle, filesize($file_name));
        settype($id, "integer");
    }
    rewind($handle);
    fwrite($handle, ++$id);

    fclose($handle);
    return $id;
}

//A function responsible for uploading a picture
function picUpload($dir, $id) {
    //$defaultImage = "/../data/uploads/no-pic.jpg";
    $target_file = $dir . basename($_FILES["pic"]["name"]);
    $uploadOk = 1;
    $image = '';
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    if(isset($_POST["submit"]) && (isset($_POST['file']))) {
        $check = getimagesize($_FILES["pic"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }
    if(file_exists($target_file)) {
        $uploadOk = 0;
    }
    if($_FILES['pic']['size'] > 6000000) {
        $uploadOk = 0;
    }
    if($imageFileType != "jpg" && $imageFileType != "JPEG" && $imageFileType != "png" && $imageFileType != "PNG"
    && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $image = $id . ".jpeg";

    } else {
        if (move_uploaded_file($_FILES["pic"]["tmp_name"], $target_file)) {
            rename($target_file, $dir . $id . ".jpeg"); //renaming every uploaded file according to the contact ID
            $image = $id . ".jpeg";
        } else {
            $image = '';
        }
    }
    return $image;
}

//A function that helps to delete an image from the "upload" folder when the contact is deleted
function deleteImg($img){
    if(file_exists($img)) {
        $x = unlink($img);
        return $x;
    }

}

