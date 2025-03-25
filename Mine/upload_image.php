<?php
session_start();
require_once 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli($server_name, $user_name, $password, $database);
    $tytul = isset($_POST['tytul']) ? $_POST['tytul'] : '';
    $tresc = isset($_POST['tresc']) ? $_POST['tresc'] : '';
    $id_autora = $_SESSION['user_id']; 
    

    $header_image_path = '';
    if (isset($_FILES['zdjecie_header']) && $_FILES['zdjecie_header']['error'] === UPLOAD_ERR_OK) {
        $header_image_name = basename($_FILES['zdjecie_header']['name']);
        $header_target_dir = "uploads/";
        $header_target_file = $header_target_dir . time() . '_header_' . $header_image_name;
        
        $check = getimagesize($_FILES['zdjecie_header']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['zdjecie_header']['tmp_name'], $header_target_file)) {
                $header_image_path = $header_target_file;
            } else {
                echo "Wystąpił błąd podczas przesyłania pliku nagłówka.";
                exit;
            }
        } else {
            echo "Plik nagłówka nie jest obrazem.";
            exit;
        }
    }
    $gallery_image_paths = [];
    if (isset($_FILES['gallery']) && is_array($_FILES['gallery']['name'])) {
        foreach ($_FILES['gallery']['name'] as $key => $gallery_image_name) {
            if (!empty($gallery_image_name) && $_FILES['gallery']['error'][$key] === UPLOAD_ERR_OK) {
                $gallery_target_file = $header_target_dir . time() . '_gallery_' . basename($gallery_image_name);
    
                $check = getimagesize($_FILES['gallery']['tmp_name'][$key]);
                if ($check !== false) {
                    if (move_uploaded_file($_FILES['gallery']['tmp_name'][$key], $gallery_target_file)) {
                        $gallery_image_paths[] = $gallery_target_file;
                    } else {
                        echo "Wystąpił błąd podczas przesyłania pliku galerii: " . $gallery_image_name;
                        exit;
                    }
                } else {
                    echo "Plik galerii nie jest obrazem: " . $gallery_image_name;
                    exit;
                }
            }
        }
    }

    $gallery_images_json = json_encode($gallery_image_paths);

    $stmt = $conn->prepare("INSERT INTO ogloszenia (tytul, tresc, id_autora, zdjecie_header, zdjecia, data, is_popular, is_edited) VALUES (?, ?, ?, ?, ?, ?, 0, 0)");
    if ($stmt) {
        $data = date("Y-m-d H:i:s");
        $stmt->bind_param("ssisss", $tytul, $tresc, $id_autora, $header_image_path, $gallery_images_json, $data);
        
        if ($stmt->execute()) {
            header('Location: index.php');
        } else {
            echo "Wystąpił błąd podczas zapisywania wiadomości: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Wystąpił błąd podczas przygotowywania zapytania: " . $conn->error;
    }

    $conn->close();
}
