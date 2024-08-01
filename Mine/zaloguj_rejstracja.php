<?php session_start(); 
if (isset($_SESSION['Login']) && $_SESSION['Login']) {
    header('Location: welcome.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');

        * {
            box-sizing: border-box;
        }

        body {
            background: #f6f5f7;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        h1 {
            font-weight: bold;
            margin: 0;
        }

        h2 {
            text-align: center;
        }

        label{
            font-size: 12px;
            line-height: 20px;
            margin: 10px 0 20px;
            display: inline-block;
        }
        label input{
            width: auto;
        }
        .inny{
            color: #ff4b2b;
            font-size: 12px;
            font-weight: 100;
            line-height: 20px;
            letter-spacing: 0.5px;
            margin: 10px 0 20px;
        }
        p {
            font-size: 14px;
            font-weight: 100;
            line-height: 20px;
            letter-spacing: 0.5px;
            margin: 10px 0 20px;
        }

        span.error {
            color: #ff464e;
            font-size: 12px;
            display: block;
            text-align: left;
            margin-top: -8px;
            margin-bottom: 10px;
        }

        a {
            color: #333;
            font-size: 14px;
            text-decoration: none;
            margin: 10px 0;
        }

        a {
            color: #333;
            font-size: 14px;
            text-decoration: none;
            margin: 15px 0;
        }

        button {
            border-radius: 20px;
            border: 1px solid #444444;
            background-color:  #444444;
            color: #FFFFFF;
            font-size: 12px;
            font-weight: bold;
            padding: 12px 45px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: transform 80ms ease-in;
        }

        button:active {
            transform: scale(0.95);
        }

        button:focus {
            outline: none;
        }

        button.ghost {
            background-color: transparent;
            border-color: #FFFFFF;
        }

        form {
            background-color: #FFFFFF;
            padding: 10px 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height:100% ;
            text-align: center;
        }

        input {
            background-color: #eee;
            border: none;
            padding: 12px 15px;
            margin: 8px 0;
            width: 100%;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 14px 28px rgba(0,0,0,0.25), 
                    0 10px 10px rgba(0,0,0,0.22);
            position: relative;
            overflow: hidden;
            width: 768px;
            max-width: 100%;
            min-height: 480px;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            overflow: auto;

            transition: all 0.6s ease-in-out;
        }

        .sign-in-container {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .container.right-panel-active .sign-in-container {
            transform: translateX(100%);
        }

        .sign-up-container {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
            
        }

        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            height: 100%;
            animation: show 0.6s;
        }

        @keyframes show {
            0%, 49.99% {
                opacity: 0;
                z-index: 1;
            }
            
            50%, 100% {
                opacity: 1;
                z-index: 5;
            }
        }

        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .container.right-panel-active .overlay-container{
            transform: translateX(-100%);
        }

        .overlay {
            background: #444444;
            background: -webkit-linear-gradient(to right, #444444, #282828);
            background: linear-gradient(to right, #444444, #282828);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 0 0;
            color: #FFFFFF;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .container.right-panel-active .overlay {
            transform: translateX(50%);
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .overlay-left {
            transform: translateX(-20%);
        }

        .container.right-panel-active .overlay-left {
            transform: translateX(0);
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
        }

        .container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        .social-container {
            margin: 20px 0;
        }

        .social-container a {
            border: 1px solid #DDDDDD;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 5px;
            height: 40px;
            width: 40px;
        }
        .buttons{
            display: grid;
            gap: 10px;
        }


    </style>
    <script>
    
        function chceckWIDTH() {
            if (window.outerWidth <= 768) {
                window.location.href = 'zaloguj.php';
            }
        }

        window.addEventListener('resize', chceckWIDTH);
        chceckWIDTH();
    </script>
    <title>Strona Logowania/Rejestracji</title>
</head>
<body>
    <div class="container <?php if(isset($_GET['register']) && $_GET['register'] == 1) {echo 'right-panel-active';} ?>" id="container">
        <div class="form-container sign-up-container">
            <form action="register.php" method="post">
                <h1>Załóż konto</h1>
                <input type="text" name="imie" placeholder="Imię" value="<?php echo isset($_SESSION['checking_imie']) ? htmlspecialchars($_SESSION['checking_imie']) : ''; ?>"><br>
                <?php
                    if (isset($_SESSION['imie_e'])) {
                        echo '<span class="error">' . $_SESSION['imie_e'] . '</span>';
                        unset($_SESSION['imie_e']);
                    }
                ?>
                <input type="text" name="nazwisko" placeholder="Nazwisko" value="<?php echo isset($_SESSION['checking_nazwisko']) ? htmlspecialchars($_SESSION['checking_nazwisko']) : ''; ?>"><br>
                <?php
                    if (isset($_SESSION['nazwisko_e'])) {
                        echo '<span class="error">' . $_SESSION['nazwisko_e'] . '</span>';
                        unset($_SESSION['nazwisko_e']);
                    }
                ?>
                <input type="email" name="email" placeholder="E-mail" value="<?php echo isset($_SESSION['checking_email']) ? htmlspecialchars($_SESSION['checking_email']) : ''; ?>"><br>
                <?php
                    if (isset($_SESSION['email_e'])) {
                        echo '<span class="error">' . $_SESSION['email_e'] . '</span>';
                        unset($_SESSION['email_e']);
                    }
                ?>
                <input type="password" name="haslo" placeholder="Hasło" value="<?php echo isset($_SESSION['checking_haslo']) ? htmlspecialchars($_SESSION['checking_haslo']) : ''; ?>"><br>
                <?php
                    if (isset($_SESSION['haslo_e'])) {
                        echo '<span class="error">' . $_SESSION['haslo_e'] . '</span>';
                        unset($_SESSION['haslo_e']);
                    }
                ?>
                <input type="password" name="haslo1" placeholder="Powtórz hasło" value="<?php echo isset($_SESSION['checking_haslo1']) ? htmlspecialchars($_SESSION['checking_haslo1']) : ''; ?>"><br>
                <?php
                    if (isset($_SESSION['haslo1_e'])) {
                        echo '<span class="error">' . $_SESSION['haslo1_e'] . '</span>';
                        unset($_SESSION['haslo1_e']);
                    }
                ?>
                <label>
                Zapoznałam(em) się z <a href="Regulamin.html" target="_blank" class="inny">Regulaminem</a>
                <input type="checkbox" name="regulamin" <?= isset($_SESSION['checking_checkbox']) ? 'checked' : ''?> >
                </label>
                <?php
                    if (isset($_SESSION['checkbox_e'])) {
                        echo '<span class="error">' . $_SESSION['checkbox_e'] . '</span>';
                        unset($_SESSION['checkbox_e']);
                    }
                ?>
                <button type="submit">Zarejestruj się</button>
                <p>
                    <?php
                        if (isset($_SESSION['alert'])) {
                            echo $_SESSION['alert'];
                            unset($_SESSION['alert']);
                        }
                    ?>
                </p>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="login.php" method="post">
                <h1>Zaloguj się</h1>
                <input type="email" name="login_l" placeholder="E-mail" value="<?php echo isset($_SESSION['checking_login_l']) ? htmlspecialchars($_SESSION['checking_login_l']) : ''; ?>"><br>
                <?php
                    if (isset($_SESSION['login_e'])) {
                        echo '<span class="error">' . $_SESSION['login_e'] . '</span>';
                        unset($_SESSION['login_e']);
                    }
                ?>
                <input type="password" name="haslo_l" placeholder="Hasło" value="<?php echo isset($_SESSION['checking_haslo_l']) ? htmlspecialchars($_SESSION['checking_haslo_l']) : ''; ?>"><br>
                <?php
                    if (isset($_SESSION['haslo_el'])) {
                        echo '<span class="error">' . $_SESSION['haslo_el'] . '</span>';
                        unset($_SESSION['haslo_el']);
                    }
                ?>
                <button type="submit">Zaloguj się</button>
                <p>
                    <?php
                        if (isset($_SESSION['alert_l'])) {
                            echo $_SESSION['alert_l'];
                            unset($_SESSION['alert_l']);
                        }
                    ?>
                </p>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Cześć, Przyjacielu!</h1>
                    <p>Wprowadź swoje dane osobowe i rozpocznij podróż z nami</p>
                    <div class="buttons">
                        <button class="ghost" id="signIn">Zaloguj się</button>
                        <button class="ghost" onclick="window.location.href = 'index.php'">Powróć</button>
                    </div>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Witamy ponownie!</h1>
                    <p>Aby korzystać z DDDziennika, zaloguj się za pomocą swoich danych osobowych</p>

                    <div class="buttons">
                        <button class="ghost" id="signUp">Zarejestruj się</button>
                        <button class="ghost" onclick="window.location.href = 'index.php'">Powróć</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });
    </script>
</body>
</html>
