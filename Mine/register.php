<?php
session_start();
require_once("db.php");


if(isset($_SESSION['Login']) && $_SESSION['Login']){
    header('Location: welcome.php');
    exit();
}
$conn = @new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_errno != 0) {
    echo $conn->connect_errno . " ";
    echo '<br> <a href="zaloguj_rejstracja.php?register=1">Wróć do strony rejestrowania</a>';
} else {
    $is_okay = true;
    if(isset($_POST['imie'])){
        $Imie = $_POST['imie'];
        $_SESSION['checking_imie'] = $Imie;
        if(!preg_match("/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ]+$/", $Imie)){
            $_SESSION['imie_e'] = 'Imię może składać się tylko z liter';
            $is_okay = false;
        }
        if(str_contains($Imie," ")){
            $_SESSION['imie_e'] = 'Imie nie może posiadać spacji';
            $is_okay = false;
        }
        if($Imie == ''){
            $_SESSION['imie_e'] = 'Nie podano Imienia';
            $is_okay = false;
        }
    }else{
        $is_okay = false;
    }
    if(isset($_POST['nazwisko'])){
        $Nazwisko = $_POST['nazwisko'];
        $_SESSION['checking_nazwisko'] = $Nazwisko;
        if(!preg_match("/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ]+$/", $Nazwisko)){
            $_SESSION['nazwisko_e'] = 'Nazwisko może składać się tylko z liter';
            $is_okay = false;
        }
        if(str_contains($Nazwisko," ")){
            $_SESSION['nazwisko_e'] = 'Nazwisko nie może posiadać spacji';
            $is_okay = false;
        }
        if($Nazwisko == ''){
            $_SESSION['nazwisko_e'] = 'Nie podano Nazwiska';
            $is_okay = false;
        }
    }else{
        $is_okay = false;
    }
    if(isset($_POST['email'])){
        $Email = $_POST['email'];
        $_SESSION['checking_email'] = $Email;
        if(!filter_var($Email, FILTER_VALIDATE_EMAIL)){
            $_SESSION['email_e'] = 'Błędna struktura E-mail';
            $is_okay = false;
        }
        if($Email == ''){
            $_SESSION['email_e'] = 'Nie podano Email';
            $is_okay = false;
        }
    }else{
        $is_okay = false;
    }
    
    if (isset($_POST['haslo'])) {
        $Haslo = $_POST['haslo'];
        $_SESSION['checking_haslo'] = $Haslo;
        if ($Haslo == '') {
            $_SESSION['haslo_e'] = 'Nie podano Hasła';
            $is_okay = false;
        } elseif (strlen($Haslo) < 8) {
            $_SESSION['haslo_e'] = 'Hasło musi być dłuższe niż 8 znaków';
            $is_okay = false;
        } elseif (str_contains($Haslo, " ")) {
            $_SESSION['haslo_e'] = 'Hasło nie może posiadać spacji';
            $is_okay = false;
        }
    }else {
        $is_okay = false;
    }
    if (isset($_POST['haslo1'])) {
            $haslo1 = $_POST['haslo1'];
            $_SESSION['checking_haslo1'] = $haslo1;

            if ($haslo1 !== $Haslo) {
                $is_okay = false;
                $_SESSION['haslo1_e'] = 'Hasła nie są takie same';
            }
    } else{
            $is_okay = false;
            $_SESSION['haslo1_e'] = 'Nie podano powtórzenia hasła';
    }

    if(isset($_POST['regulamin'])){
        $Regulamin = $_POST['regulamin'];
        $_SESSION['checking_checkbox'] = $Regulamin;
        if(!$Regulamin){
            $_SESSION['checkbox_e'] = 'Zapoznanie się z regulaminem jest wymagane';
            $is_okay = false;
        }
    }else{
        $is_okay = false;
        unset($_SESSION['checking_checkbox']);
        $_SESSION['checkbox_e'] = 'Zapoznanie się z regulaminem jest wymagane';
    }

    if(!$is_okay){
        header('Location: zaloguj_rejstracja.php?register=1');
        exit();
    }

    $Imie = htmlentities($Imie, ENT_QUOTES, 'UTF-8');
    $Nazwisko = htmlentities($Nazwisko, ENT_QUOTES, 'UTF-8');
    $Haslo = password_hash($Haslo, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM users WHERE `E-mail` = '$Email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['alert'] = 'Urzytkownik z tym adresem E-mail znajduje się w bazie danych';
        header('Location: zaloguj_rejstracja.php?register=1');
        exit();
    } else {
        $Imie = strtolower($Imie);
        $Nazwisko = strtolower($Nazwisko);

        $Imie[0] = strtoupper($Imie[0]);
        $Nazwisko[0] = strtoupper($Nazwisko[0]);

        $sql1 = "INSERT INTO users VALUES (NULL,'$Imie','$Nazwisko',NULL,'$Email','$Haslo', 'Uczen', NULL, '')";
        $result1 = $conn->query($sql1);

        unset($_SESSION['cheaking_imie']);
        unset($_SESSION['cheaking_nazwisko']);
        unset($_SESSION['cheaking_email']);
        unset($_SESSION['checking_haslo']);
        unset($_SESSION['checking_haslo1']);
        unset($_SESSION['Imie_e']);
        unset($_SESSION['Nazwisko_e']);
        unset($_SESSION['email_e']);
        unset($_SESSION['haslo_e']);
        unset($_SESSION['haslo1_e']);

        $sql2 = "SELECT id FROM users WHERE `E-mail` = '$Email'";
        $result2 = $conn->query($sql2);
        $result2 = $result2->fetch_assoc();
        $OK = $result2['id'];

        $sql3 = "INSERT INTO users_oceny VALUES (NULL, '$OK' , NULL, NULL,NULL)";
        $result3 = $conn->query($sql3);

        $_SESSION['alert_l'] = 'Utworzono Konto, zaloguj się na podane dane';
        header('Location: zaloguj_rejstracja.php');
        exit();
    }

}