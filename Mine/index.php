<?php
session_start(); 
require_once 'db.php';

$conn = new mysqli($server_name, $user_name, $password, $database);
$var = isset($_POST["school_filter"]) ? $_POST["school_filter"] : (isset($_SESSION["school_filter"]) ? $_SESSION["school_filter"] : 0);
$_SESSION["school_filter"] = $var;
$result = $conn -> query("SELECT * FROM schools WHERE Id_szkoly = '$var'");

$result = $result -> fetch_assoc();
$conn -> close();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $result['nazwa_szkoly'] ?? 'DDDziennik' ?></title>
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
            width: 35ch;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }
        header .h1{
            display: flex;
            justify-content: center;
            align-items: center;
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
        .read-more-link{
            color: #333;
            text-decoration: none;
            margin: 10px 10px 10px 0px;
            padding: 8px 12px;
            border: 1px solid #333;
            border-radius: 4px;
            transition: background-color 0.3s ease, color 0.3s ease;
            display: inline-block;
        }
        .read-more-link:hover{
            background-color: #333;
            color: #fff;
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
            border: none;
            margin: 0;
            padding: 0;
            border-radius: none;
            display: block;
        }
        .card ul li a:hover {
            color: #0056b3;
            background-color: transparent;
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
        .nav img{
            height: 2rem !important;
            width: auto !important;
        }
        @media screen and (max-width: 894px) {
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
            .auth{
                display: inline-block;
                position: relative;
                transform: translate(0, 0);
            }
            .login{
                font-size: 1em;
                font-family: inherit !important;
                border: none;
                background-color: transparent;
                appearance: none !important;
                text-decoration: none;
                display: inline-block ;
                color: #fff;
                padding: 10px 15px;
                transition: background-color 0.3s ease;
                margin: 0;
                border-radius: 5px;
            }
            .login:hover{
                background-color: #555;
            }
        }
        .school-selector{
            position: absolute;
            top: 110%;
            background-color: #fff !important;
            z-index: 1;
            padding: 10px 15px;
            box-shadow: 0 0 10px  rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }
        .school-search{
            width: 100%;
            padding: 5px;
            border-radius: 5px;
            box-sizing: border-box;
            border: 1px solid rgba(0, 0, 0, 0.5);
        }
        .dropdown{
            padding: 10px 3px;
        }
        .dropdown div{
            padding: 5px;
            border-bottom: 1px solid #ccc;
            cursor: pointer;
        }
        .dropdown div:hover{
            background-color: #f0f0f0;
            transition: background-color 0.3s ease;
        }
        nav div button {
            height: 100%;
        }
        <?php if (isset($_SESSION['Rola_user']) && ($_SESSION['Rola_user'] == 'Admin' || $_SESSION['Rola_user'] == 'Nauczyciel' || $_SESSION['Rola_user'] == 'Admin_d')) : ?>

            form[action="upload_image.php"] {
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

            form[action="upload_image.php"] input[type="text"],form[action="upload_image.php"] textarea,
            form[action="upload_image.php"] input[type="file"],
            form[action="upload_image.php"] input[type="submit"] {
                width: calc(100% - 20px); 
                margin-bottom: 10px;
                padding: 10px; 
                border: 1px solid #ccc; 
                border-radius: 5px; 
                font-size: 16px; 
                box-sizing: border-box; 
            }

            form[action="upload_image.php"] input[type="submit"] {
                background-color: #4CAF50; 
                color: white; 
                border: none; 
                cursor: pointer;
            }

            form[action="upload_image.php"] input[type="submit"]:hover {
                background-color: #45a049; 
            }

            form[action="upload_image.php"] label {
                display: block; 
                margin-bottom: 5px; 
                text-align: left; 
            }
            form[action="upload_image.php"] textarea{
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
        <?php endif; ?>

    </style>
    <script>

        <?php if (isset($_SESSION['Rola_user']) && ($_SESSION['Rola_user'] == 'Admin' || $_SESSION['Rola_user'] == 'Nauczyciel' || $_SESSION['Rola_user'] == 'Admin_d')) : ?>
            function addForm() {
                if (!document.body.querySelector('body > form')){
                    const form = document.createElement('form');
                    form.action = 'upload_image.php';
                    form.method = 'post';
                    form.enctype = 'multipart/form-data';

                    const header =document.createElement('div');
                    header.className = 'headerr';

                    const h2 = document.createElement('h2');
                    h2.innerHTML = 'Dodaj wpis';
                    header.appendChild(h2);
                    const close = document.createElement('div');
                    close.innerHTML = '&times;'
                    close.className = 'close';
                    close.addEventListener('click', function(){
                        form.style.display = 'none';
                    })
                    header.appendChild(close);

                    form.appendChild(header);

                    const label = document.createElement('label');
                    label.innerText = 'Zdjęcie Tytłuowe: ';
                    label.for = 'zdjecie_header';
                    const inputImageHeader = document.createElement('input');
                    inputImageHeader.type = 'file';
                    inputImageHeader.name = 'zdjecie_header';
                    inputImageHeader.id = 'zdjecie_header';
                    inputImageHeader.accept = 'image/png, image/jpeg';
                    form.appendChild(label);
                    form.appendChild(inputImageHeader);

                    const label2 = document.createElement('label');
                    label2.innerText = 'Zdjęcia: ';
                    label2.for = 'gallery'
                    const inputGallery = document.createElement('input');
                    inputGallery.type = 'file';
                    inputGallery.name = 'gallery[]';
                    inputGallery.id = 'gallery';
                    inputGallery.accept = 'image/png, image/jpeg';
                    inputGallery.multiple = true;
                    form.appendChild(label2);
                    form.appendChild(inputGallery);

                    const inputTytul = document.createElement('input');
                    inputTytul.type = 'text';
                    inputTytul.name = 'tytul';
                    inputTytul.placeholder = 'Tytuł...';
                    form.appendChild(inputTytul);

                    const inputTresc = document.createElement('textarea');
                    inputTresc.name = 'tresc';
                    inputTresc.placeholder = 'Treść..';
                    form.appendChild(inputTresc);

                    const inputSubmit = document.createElement('input');
                    inputSubmit.type = 'submit';
                    inputSubmit.value = 'Wyślij';
                    form.appendChild(inputSubmit);

                    document.body.appendChild(form);
                }else if(document.body.querySelector('body > form') && document.body.querySelector('body > form').style.display == 'none'){
                    document.body.querySelector('body > form').style.display = 'block';
                }
            }
        <?php endif; ?>
    </script>
</head>
<body>
    <header>
        <div class="h1">
            <h1><?= $result['nazwa_szkoly'] ?? 'DDDziennik' ?></h1>
        </div>
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
        <?php if (isset($_SESSION['Rola_user']) && ($_SESSION['Rola_user'] == 'Admin' || $_SESSION['Rola_user'] == 'Nauczyciel' || $_SESSION["Rola_user"] == 'Admin_d')) : ?>
            <button onclick="addForm()"><i class="fas fa-edit"></i> Dodaj Wpis</button>
        <?php endif; ?>
        <div>
            <button onclick="searchSchool()"><i class="fas fa-search"></i> Szukaj szkoły</button>
            <div class="school-selector" style="display: none">
                <label for="szkola">Szkoła: </label>
                <script>
                    function formin(thi){
                        setTimeout(() => {
                            thi.form.submit();
                        }, 100);
                    }                        
                </script>
                <form action="" method="post">
                    <input type="hidden" name="school_filter" value="">                                                                                             
                    <input type="text" class="school-search" id="szkola" placeholder="Wyszukaj szkołę" autocomplete="off" required onchange="formin(this)">
                </form>
                <div class="dropdown"></div>
            </div>
        </div>
        <?php if (!isset($_SESSION['Login']) || $_SESSION['Login'] == false) : ?>
            <div class="auth">
                <a class='login' href="zaloguj_rejstracja.php">Moje konto</a>
            </div>
            <script>

                

                function loginbutton(){
                    let login =document.querySelector('a.login');
                    if (window.outerWidth <= 768){
                        login.innerHTML = '';
                        let i = document.createElement('i');
                        i.className = 'fas fa-cog';
                        login.appendChild(i);
                        let j = ' Moje konto';
                        login.append(j);
                    }else{
                        login.innerHTML = 'Moje konto';
                    }
                }
                loginbutton();
                window.addEventListener('resize', loginbutton)
            </script>
        <?php endif; ?>
    </nav>
    <div class="container">
        
        <?php 
            $conn = @new mysqli($server_name, $user_name, $password, $database);
            $sql = "SELECT * FROM ogloszenia WHERE nalezy_id_szkoly = '$var' ORDER BY data DESC LIMIT 1 ";
            $result = $conn->query($sql);
            if ($result) {
                $ogloszenie = $result->fetch_assoc();
                if ($ogloszenie){
        ?>
            <div class="featured-article">
                
                <h2><?= htmlspecialchars($ogloszenie['tytul']) ?></h2>
                <img src="<?= $ogloszenie['zdjecie_header'] ?>" alt="Nie udało się wczytać zdjęcia">
                <p><?= htmlspecialchars($ogloszenie['tresc']) ?></p>
                <a href="article.php?id=<?= htmlspecialchars($ogloszenie['id']) ?>" class="read-more-link">Czytaj Więcej <i class="fas fa-chevron-right"></i></a>
            </div>
        <?php }} ?>
        <div class="card">
            <h2>Popularne ogłoszenia</h2>
            <ul>
                <?php 
                    $sql = "SELECT * FROM ogloszenia WHERE is_popular = 1 AND nalezy_id_szkoly = '$var' LIMIT 3 ";
                    $result = $conn->query($sql);
                    $rows = [];
                    while($row = $result->fetch_assoc()){
                        $rows[] = $row;
                    }
                    if(empty($rows)){
                        echo "<span style='font-weight: bold'>Brak</span>";
                    }
                    foreach ($rows as $row){
                ?>
                <li><a href="article.php?id=<?= htmlspecialchars($row['id']) ?>"><?=htmlspecialchars($row['tytul'])?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
<script>

    function searchSchool() {
        const element = document.querySelector(".school-selector");
        element.style.display = element.style.display === "none" ? "block" : "none";
    }


const searchInputs = document.querySelectorAll('.school-search');

searchInputs.forEach((input) => {
    const parentDiv = input.closest('.school-selector');
    const dropdown = parentDiv.querySelector('.dropdown');

    input.addEventListener('input', function () {
        const query = this.value;

        if (query.length >= 2) {
            fetch(`search_schools.php?query=${encodeURIComponent(query)}`)
                .then((response) => response.json())
                .then((data) => {
                    dropdown.innerHTML = ''; 

                    data.forEach((school) => {
                        const item = document.createElement('div');
                        item.classList.add('dropdown-item');
                        item.textContent = `${school.nazwa_szkoly}`;
                        item.dataset.value = school.Id_szkoly;

                        item.addEventListener('click', () => {
                            input.value = `${school.nazwa_szkoly}`;
                            try{
                                parentDiv.querySelector('input[name="school_id"]').value = school.Id_szkoly;
                            } catch (error) {
                                parentDiv.querySelector('input[name="school_filter"]').value = school.Id_szkoly;
                            }
                            dropdown.style.display = 'none';
                        });

                        dropdown.appendChild(item);
                    });

                    const item = document.createElement('div');
                        item.classList.add('dropdown-item');
                        item.textContent = `Brak`;
                        item.dataset.value = '.';

                        item.addEventListener('click', () => {
                            input.value = `Brak`;
                            parentDiv.querySelector('input[name="school_id"]').value = '.';
                            dropdown.style.display = 'none';
                        });

                        dropdown.appendChild(item);

                    dropdown.style.display = 'block'; 
                })
                .catch((error) => console.error('Błąd:', error));
        } else {
            dropdown.style.display = 'none'; 
        }
    });

    document.addEventListener('click', (event) => {
        if (!parentDiv.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });
});

</script>
<?php include "disabled_functions.html"?>