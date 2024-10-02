<?php
session_start();
if(isset($_SESSION['Login']) and $_SESSION['Login']){
    header('Location: welcome.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona Logowania</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 300px;
        }
        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            font-size: 12px;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .back{
            position: fixed; top:10px; left: 10px;
            text-align: center;
        }
        .back button {
            border: 2px #333 solid;
            color: #333;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    
    <form action="login_tel.php" method="post">
        <h1>Zaloguj się</h1>
        <input type="text" name="login" placeholder="E-mail" value="<?php echo isset($_SESSION['checking_login_l']) ? htmlspecialchars($_SESSION['checking_login_l']) : ''; ?>"><br>
        <?php
            if (isset($_SESSION['login_el'])) {
                echo '<span class="error">' . $_SESSION['login_el'] . '</span>';
                unset($_SESSION['login_el']);
            }
        ?>
        <br>
        <input type="password" name="haslo" placeholder="Hasło" value="<?php echo isset($_SESSION['checking_haslo_l']) ? htmlspecialchars($_SESSION['checking_haslo_l']) : ''; ?>"><br>
        <?php
            if (isset($_SESSION['haslo_el'])) {
                echo '<span class="error">' . $_SESSION['haslo_el'] . '</span>';
                unset($_SESSION['haslo_el']);
            }
        ?>
        <br>
        <input type="submit" value="Zaloguj się">
        <br>
        <br>
        <?php
            if (isset($_SESSION['alert_l'])) {
                echo $_SESSION['alert_l'];
                unset($_SESSION['alert_l']);
            }
        ?>
        <a href="rejstracja.php">Zarejestruj się</a>
        <a href="index.php">Powrót</a>
    </form>

    
</body>
</html>
