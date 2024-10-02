<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tytul = $_POST['edit_header'];
    $tresc = $_POST['edit_tresc'];
    $id = isset($_GET['id']) ? $_GET['id'] : false;

    if (!$id || empty($tytul) || empty($tresc)) {
        die("Invalid request. Please provide all required fields.");
    }

    $zdjecie_header = $_FILES['zdjecie_header'];
    $zdjecia = $_FILES['zdjecia'];

    $conn = new mysqli($server_name, $user_name, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($zdjecie_header['size'] > 0 && $zdjecie_header['error'] == 0) {
        $header_image_path = uploadImage($zdjecie_header);
        if (!$header_image_path) {
            die("Error uploading header image.");
        }
        $data = date("Y-m-d H:i:s");
        $sql = "UPDATE ogloszenia SET tytul = ?, tresc = ?, zdjecie_header = ?, is_edited = 1, data_edited = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $tytul, $tresc, $header_image_path, $data ,$id);
    } else {
        $data = date("Y-m-d H:i:s");
        $sql = "UPDATE ogloszenia SET tytul = ?, tresc = ?, is_edited = 1, data_edited = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $tytul, $tresc, $data, $id);
    }

    if ($stmt->execute()) {
        if (!empty($zdjecia['name'][0])) {
            $gallery_images_paths = [];
            foreach ($zdjecia['tmp_name'] as $index => $tmp_name) {
                if ($zdjecia['size'][$index] > 0 && $zdjecia['error'][$index] == 0) {
                    $gallery_image_path = uploadImage($zdjecia, $index);
                    if ($gallery_image_path) {
                        $gallery_images_paths[] = $gallery_image_path;
                    } else {
                        die("Error uploading gallery image.");
                    }
                }
            }
            $gallery_images_json = json_encode($gallery_images_paths);
            $update_gallery_sql = "UPDATE ogloszenia SET zdjecia = ? WHERE id = ?";
            $stmt_gallery = $conn->prepare($update_gallery_sql);
            $stmt_gallery->bind_param("si", $gallery_images_json, $id);
            $stmt_gallery->execute();
        }

        exit();
    }

    $stmt->close();
    $conn->close();

}

function uploadImage($file, $index = null) {
    $target_dir = "uploads/";
    if ($index !== null) {
        $target_file = $target_dir . time() . "_gallery_" . basename($file['name'][$index]);
    }else{
        $target_file = $target_dir . time() . "_header_" . basename($file['name']);
    }
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($index !== null){
        $check = getimagesize($file['tmp_name'][$index]);
    }else{
        $check = getimagesize($file['tmp_name']);
    }
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    if ($index !== null){
        if ($file['size'][$index] > 500000) {
            $uploadOk = 0;
        }
    }else{
        if ($file['size'] > 500000) {
            $uploadOk = 0;
        }
    }


    $allowed_formats = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowed_formats)) {
        $uploadOk = 0;
    }


    if ($uploadOk == 0) {
        return false;

    } else {

        if ($index !== null){
            if (move_uploaded_file($file['tmp_name'][$index], $target_file)) {
                return $target_file;
            } else {
                return false;
            }
        }else{
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                return $target_file;
            } else {
                return false;
            }
        }
    }
}