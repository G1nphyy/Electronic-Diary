<?php
session_start();

if (!$_SESSION['Login'] || $_SESSION['Rola_user'] == 'Uczen' ) {
    header('Location: Index.php');
    exit();
}

require_once('db.php');

$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_error) {
    die('Error: ' . $conn->connect_error);
} else {
    $user_id = $_POST['user_id'];
    $subject = $_POST['subject'];
    $grade_id = $_POST['grade_id'];
    if($user_id == '' and $subject == '' and $grade_id == ''){
        header("Location: change_roles.php?page={$_GET['page']}&search={$_GET['search']}");
    }
    $sql = "SELECT `$subject` FROM users_oceny WHERE id_ucznia = '$user_id'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $grades = explode(",", $row[$subject]);
        
        unset($grades[$grade_id]);
        
        $updated_grades = implode(",", $grades);
        
        $update_sql = "UPDATE users_oceny SET `$subject` = ? WHERE id_ucznia = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param('si', $updated_grades, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Grade updated successfully.';
        } else {
            $_SESSION['error'] = 'Failed to update grade.';
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = 'Failed to fetch current grades.';
    }

    $conn->close();

    header("Location: change_roles.php?page={$_GET['page']}&search={$_GET['search']}");
    exit();
}
