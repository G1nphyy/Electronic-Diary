<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $data = $_POST['date'];
    $indexLekcji = $_POST['indexLekcji'];
    $klasa = $_POST['klasa'];
    require_once 'db.php';
    $conn = new mysqli($server_name, $user_name, $password, $database);
    $can_i = true;

    switch ($action) {
        case 'cancel':
            $Nauczyciel = $_POST['Nauczyciel'];
            $sql = "INSERT INTO zmiany_plan_lekcji VALUES (NULL, 'Odwolaj', '$data', '$klasa', '$indexLekcji $Nauczyciel')";
            break;
        case 'reschedule':
            $NowaData = $_POST['newDateTime'];
            $Lekcja = $_POST['lekcja'];
            $sql = "INSERT INTO zmiany_plan_lekcji VALUES (NULL, 'Przesun', '$data', '$klasa', '$indexLekcji $NowaData $Lekcja')";
            break;
        case 'substitute':
            $Nauczyciel = $_POST['Nauczyciel'];
            $Lekcja = $_POST['lekcja'];
            $Sala = $_POST['Sala'];
            $sql = "INSERT INTO zmiany_plan_lekcji VALUES (NULL, 'Zastepstwo', '$data', '$klasa', '$indexLekcji $Nauczyciel $Lekcja $Sala')";
            break;
        default:
            $can_i = false;
            echo "Unknown action.";
            break;
    }

    if ($can_i) {
        $result = $conn->query($sql);
        if ($result) {
            echo "Action executed successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    }
    $conn->close();
} else {
    echo "Invalid request.";
}