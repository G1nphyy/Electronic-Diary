<?php
session_start();

if (!isset($_SESSION['Login']) || $_SESSION['Rola_user'] == 'Uczen') {
    header('Location: zaloguj.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $opis = isset($_POST['opis']) ? htmlspecialchars($_POST['opis']) : '';
    $waga = isset($_POST['waga']) ? intval($_POST['waga']) : 1;
    $przedmiot = isset($_POST['przedmiot']) ? $_POST['przedmiot'] : '';
    $grades = $_POST['grades'] ?? [];

    require_once 'db.php'; 

    $conn = new mysqli($server_name, $user_name, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    foreach ($grades as $id => $grade) {
        $id = intval($id); 
        $grade = intval($grade); 

        $sql = "SELECT * FROM users_oceny WHERE id_ucznia = '$id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
         
            $oceny = $row[$przedmiot]; 
            $oceny = explode(',', $oceny); 
            $oceny[] = $grade.":".$waga."-".$opis;
            if(empty($oceny[0])) {
                $oceny_str = implode('', $oceny);
            }else{
                $oceny_str = implode(',', $oceny);
            }
        }

        $sql = "UPDATE users_oceny SET `$przedmiot` = ? WHERE id_ucznia = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $oceny_str,$id);
        
        if ($stmt->execute()) {
            echo "Grades updated successfully for user with ID: $id<br>";
        } else {
            echo "Error updating grades: " . $conn->error;
        }

        $stmt->close();
    }

    $conn->close();

    $redirect_page = "change_roles.php?page={$_GET['page']}&search={$_GET['search']}";
    header("Location: $redirect_page");
    exit();
} else {
    header('Location: zaloguj.php');
    exit();
}