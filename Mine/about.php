<?php
session_start(); 
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDDziennik - O nas</title>
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
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: #f6f6f6;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
                border-radius: 15px; 
                padding: 20px; 
                max-width: 400px; 
                width: 90%;
                text-align: center;
                max-height: 70dvh;
                overflow: auto; 
            }

            form input[type="text"],form textarea,
            form input[type="file"],
            form input[type="submit"] {
                width: calc(100% - 20px); 
                margin-bottom: 10px;
                padding: 10px; 
                border: 1px solid #ccc; 
                border-radius: 5px; 
                font-size: 16px; 
                box-sizing: border-box; 
            }

            form input[type="submit"] {
                background-color: #4CAF50; 
                color: white; 
                border: none; 
                cursor: pointer;
            }

            form input[type="submit"]:hover {
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
        <section id="about-us">
            <h2>O Nasza Firma</h2>
            <p>
                Nasza firma jest dynamicznym podmiotem działającym na rynku mediów od ponad 10 lat. Specjalizujemy się w produkcji wysokiej jakości treści dziennikarskich, które informują, inspirują i angażują naszych czytelników. Nasz zespół składa się z doświadczonych dziennikarzy, redaktorów oraz specjalistów ds. treści, którzy pracują z pasją i zaangażowaniem.
            </p>
            <p>
                Misją naszej firmy jest dostarczanie rzetelnych informacji oraz głębokich analiz, które pomagają naszym czytelnikom zrozumieć współczesny świat. Jesteśmy dumni z naszych osiągnięć i dążymy do ciągłego rozwoju, aby sprostać oczekiwaniom naszych odbiorców.
            </p>
        </section>
        
        <section id="our-journal">
            <h2>Nasz Dziennik</h2>
            <p>
                Nasz dziennik to platforma, na której publikujemy różnorodne artykuły i reportaże na tematy społeczne, polityczne, kulturalne oraz technologiczne. Niezależność redakcyjna oraz wysoki standard etyczny są dla nas fundamentem naszej działalności. Stawiamy na jakość, rzetelność i zrozumienie dla naszych czytelników.
            </p>
            <p>
                Nasz zespół dziennikarzy pracuje z pasją, aby dostarczać treści, które są nie tylko informacyjne, ale także inspirujące i edukacyjne. Zapraszamy do regularnego odwiedzania naszego dziennika, aby być na bieżąco z najważniejszymi wydarzeniami i tematami dnia.
            </p>
        </section>
        
        <section id="mission-vision">
            <h2>Nasza Misja i Wizja</h2>
            <p>
                Naszą misją jest tworzenie treści, które angażują, inspirować i informują naszych czytelników. Dążymy do tego, aby nasz dziennik był źródłem wartościowych informacji oraz miejscem dialogu i refleksji.
            </p>
            <p>
                Naszą wizją jest rozwijanie się jako ceniony podmiot na rynku mediów, który ma wpływ na debatę publiczną oraz wspiera społeczność w zrozumieniu złożonych problemów współczesności.
            </p>
        </section>
        
        <section id="team">
            <h2>Nasz Zespół</h2>
            <p>
                Nasz zespół składa się z pasjonatów, którzy kierują się profesjonalizmem i etyką zawodową. Dzięki różnorodnym doświadczeniom oraz specjalistycznym umiejętnościom, jesteśmy w stanie dostarczać treści na najwyższym poziomie. Chcemy, aby nasza praca miała realny wpływ na naszych czytelników i otaczały nas wartości takie jak uczciwość, otwartość i odpowiedzialność.
            </p>
            <p>
                Każdy członek naszego zespołu wnieś wielkie zaangażowanie w tworzenie wartościowych treści i jesteśmy dumni z każdego projektu, który realizujemy razem.
            </p>
        </section>
        
        <section id="contact">
            <h2>Kontakt</h2>
            <p>
                Zapraszamy do kontaktu z nami w sprawie współpracy, pytań czy sugestii. Jesteśmy otwarci na dialog i chętnie odpowiemy na wszelkie zapytania.
            </p>
            <p>
                E-mail: dddziennik@gmail.com<br>
                Telefon: +48 123 456 789
            </p>
        </section>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
