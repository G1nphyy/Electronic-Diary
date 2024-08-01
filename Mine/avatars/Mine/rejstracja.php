<?php
session_start();
unset($_SESSION["checking_haslo"]);
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
    <title>Rejstracja</title>
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
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        input[type="text"]:first-of-type{
            margin: 0!important;
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
        a:not(.inny) {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        a:hover:not(.inny) {
            text-decoration: underline;
        }
        .inny{
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
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
    
    <form action="register_tel.php" method="post">
        <h1>Zarejestruj się</h1>
        <input type="text" name="Imie" placeholder="Imię" value="<?php echo isset($_SESSION['checking_imie']) ? htmlspecialchars($_SESSION['checking_imie']) : ''; ?>"><br>
        <?php
            if (isset($_SESSION['Imie_e'])) {
                echo '<span class="error">' . $_SESSION['Imie_e'] . '</span>';
                unset($_SESSION['Imie_e']);
            }
        ?>
        <br>
        <input type="text" name="Nazwisko" placeholder="Nazwisko" value="<?php echo isset($_SESSION['checking_nazwisko']) ? htmlspecialchars($_SESSION['checking_nazwisko']) : ''; ?>"><br>
        <?php
            if (isset($_SESSION['Nazwisko_e'])) {
                echo '<span class="error">' . $_SESSION['Nazwisko_e'] . '</span>';
                unset($_SESSION['Nazwisko_e']);
            }
        ?>
        <br>
        <input type="text" name="E-mail" placeholder="E-mail" value="<?php echo isset($_SESSION['checking_email']) ? htmlspecialchars($_SESSION['checking_email']) : ''; ?>"><br>
        <?php
            if (isset($_SESSION['email_e'])) {
                echo '<span class="error">' . $_SESSION['email_e'] . '</span>';
                unset($_SESSION['email_e']);
            }
        ?>
        <br>
        <input type="password" name="haslo" placeholder="Hasło" value="<?php echo isset($_SESSION['checking_haslo']) ? htmlspecialchars($_SESSION['checking_haslo']) : ''; ?>"><br>
        <?php
            if (isset($_SESSION['haslo_e'])) {
                echo '<span class="error">' . $_SESSION['haslo_e'] . '</span>';
                unset($_SESSION['haslo_e']);
            }
        ?>
        <br>
        <input type="password" name="haslo1" placeholder="Powtórz hasło" value="<?php echo isset($_SESSION['checking_haslo1']) ? htmlspecialchars($_SESSION['checking_haslo1']) : ''; ?>"><br>
        <?php
            if (isset($_SESSION['haslo1_e'])) {
                echo '<span class="error">' . $_SESSION['haslo1_e'] . '</span>';
                unset($_SESSION['haslo1_e']);
            }
        ?>
        <br>
        <label>
        <input type="checkbox" name="regulamin" <?= isset($_SESSION['checking_chceckbox']) ? 'checked' : ''?> >
        Zapoznałam(em) się z <a href="Regulamin.html" target="_blank" class="inny">Regulaminem</a>
        </label>
        <?php
            if (isset($_SESSION['checkbox_e'])) {
                echo '<span class="error">' . $_SESSION['checkbox_e'] . '</span>';
                unset($_SESSION['checkbox_e']);
            }
        ?>
        <input type="submit" value="Utwórz konto">
        <br>
        <br>
        <p>
            <?php
                if (isset($_SESSION['alert'])) {
                    echo $_SESSION['alert'];
                    unset($_SESSION['alert']);
                }
            ?>
        </p>
        <a href="zaloguj.php">Zaloguj się</a>
        <a href="index.php">Powrót</a>
    </form>
</body>
</html>
