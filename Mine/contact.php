<?php
session_start(); 
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDDziennik - Kontakt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            position: relative !important;
        }
        header h1 {
            margin: 0;
            font-size: 2.5em;
            letter-spacing: 2px;
        }
        nav {
            background-color: #444;
            display: flex;
            flex-direction: row;
            justify-content: center;
            padding: 10px 20px; 
            position: relative;
        }

        .auth{
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translate(0, -50%);
        }
        nav a, nav button {
            font-size: 1em;
            font-family: inherit !important;
            border: none;
            background-color: transparent;
            appearance: none !important;
            text-decoration: none;
            color: #fff;
            padding: 10px 15px;
            transition: background-color 0.3s ease;
            margin: 0;
            border-radius: 5px;
        }
        nav a:hover, nav button:hover {
            background-color: #555;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .featured-article {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .featured-article img {
            width: 100%;
            object-fit: cover;
            min-height: 100px;
            max-height: 350px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .featured-article h2 {
            color: #333;
            font-size: 1.8em;
            margin-bottom: 10px;
        }
        .featured-article > p {
            color: #666;
            font-size: 1.1em;
            overflow: hidden; 
            text-overflow: ellipsis; 
            display: -webkit-box;
            -webkit-line-clamp: 1; 
            -webkit-box-orient: vertical;
        }
        .full-article {
            display: none;
            margin-top: 15px;
        }
        .full-article > p {
            color: #666;
            font-size: 1.1em;
        }
        .card {
            background-color: #f9f9f9;
            margin-top: 30px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card h2 {
            color: #333;
            font-size: 1.8em;
            margin-bottom: 10px;
        }
        .card ul {
            list-style-type: none;
            padding: 0;
        }
        .card ul li {
            margin-bottom: 8px;
        }
        .card ul li a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .card ul li a:hover {
            color: #0056b3;
        }
        .login, .register {
            border: 2px solid #1e90ff;
            border-radius: 50px;
            padding: 12px 16px;
        }

        .login:hover, .register:hover {
            background-color: #1e90ff; 
            border-color: #1e90ff; 
        }
        .nav{
            padding: 7px 18px !important;
        }
        a.closebtn{
            padding: 0 18px !important;
        }
        @media screen and (max-width: 768px) {
            .container {
                padding: 20px;
            }
            .featured-article h2 {
                font-size: 1.5em;
            }
            .featured-article p {
                font-size: 1em;
            }
            .card h2 {
                font-size: 1.5em;
            }
            .card ul li {
                margin-bottom: 5px;
            }
            .news-ticker {
                margin-top: 20px;
            }
            .login, .register {
                margin: 5px 0;
                padding: 8px 16px;
            }
            nav{
                display: flex ;
                flex-wrap: wrap;
                justify-content: center;
                align-items: center;
                font-size: 0.9em;
            }
            header h1{
                margin-right: 100px;
                font-size: 1.5em;
            }
            footer{
                font-size: 0.8em !important;
            }
        }

            form {
                padding: 20px; 
                max-width: 400px; 
                width: 90%;
                text-align: center;
                max-height: 70dvh;
                margin: 0 auto;
                overflow: auto; 
            }

            form input,form textarea,
            form button {
                width: calc(100% - 20px); 
                margin-bottom: 10px;
                padding: 10px; 
                border: 1px solid #ccc; 
                border-radius: 5px; 
                font-size: 16px; 
                box-sizing: border-box; 
            }

            form button {
                background-color: #4CAF50; 
                color: white; 
                border: none; 
                cursor: pointer;
            }

            form button:hover {
                background-color: #45a049; 
            }

            form label {
                display: block; 
                margin-bottom: 5px; 
                text-align: left; 
            }
            form textarea{
                resize: vertical;
                font-family: inherit;
            }
            .headerr{
                display: flex;
                position: relative;
                justify-content: center;
                text-align: center;
                height: 50px;
            }
            .headerr h2{
                width: 100%;
            }
            .close{
                font-size: 2em;
                user-select: none;
                position: absolute;
                cursor: default;
                right: -5px;
                top: -5px;
                padding: 0 15px;
                border-radius: 50%;
                transition: all .3s ;
            }
            .close:hover{
                background-color: #f01111a1;
                color: white;
            }

    </style>
</head>
<body>
    <header>
        <h1>DDDziennik</h1>
        <?php if(isset($_SESSION['Login']) && $_SESSION['Login'] == true){
            include 'nav.php';
        }
        ?>
    </header>
    <nav>
        <a href="index.php"><i class="fas fa-home"></i> Strona główna</a>
        <a href="all_article.php"><i class="fas fa-pen"></i> Wpisy</a>
        <a href="contact.php"><i class="fas fa-phone"></i> Kontakt</a>
        <a href="about.php"><i class="fas fa-info-circle"></i> O nas</a>
        <?php if (!isset($_SESSION['Login']) || $_SESSION['Login'] == false) : ?>
            <div class="auth">
                <a class='login' href="zaloguj_rejstracja.php">Moje konto</a>
            </div>
        <?php endif; ?>
    </nav>
    <div class="container">
        <section id="contact-form">
            <h2>Napisz do nas</h2>
            <form action="send-email.php" method="POST">
                <div class="form-group">
                    <label for="name">Imię i nazwisko:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Adres e-mail:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Wiadomość:</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Wyślij wiadomość</button>
                </div>
            </form>
        </section>
            
        <section id="contact-details">
            <h2>Dane kontaktowe</h2>
            <p>
                Masz pytania lub chcesz się z nami skontaktować? Skorzystaj z poniższych danych:
            </p>
            <p>
                E-mail: dddziennik@gmail.com<br>
                Telefon: +48 123 456 789<br>
                Adres: ul. Przykładowa 1, 00-000 Warszawa
            </p>
        </section>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
