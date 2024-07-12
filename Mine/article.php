<?php
session_start();
require_once 'db.php';

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$article_id = intval($_GET['id']);
$conn = new mysqli($server_name, $user_name, $password, $database);
$sql = "SELECT * FROM ogloszenia WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $article = $result->fetch_assoc();
} else {
    die("Article not found.");
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['tytul']); ?></title>
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
        }
        header h1 {
            margin: 0;
            font-size: 2.5em;
            letter-spacing: 2px;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .container img {
            width: 100%;
            object-fit: cover;
            max-height: 450px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .container p {
            color: #666;
            font-size: 1.1em;
            margin-bottom: 20px;
        }
        .full-content {
            color: #333;
            font-size: 1.2em;
            margin-top: 20px;
        }
        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-link:hover {
            background-color: #555;
        }
        .galeria {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }
        .galeria img {
            width: calc(20% - 10px);
            height: auto;
            border-radius: 5px;
            transition: transform 0.3s;
            cursor: pointer;
        }
        .galeria img:hover {
            transform: scale(1.1);
        }
        .fullscreen-img {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .fullscreen-img img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 10px;
        }

        .fullscreen-img .close, .fullscreen-img .prev, .fullscreen-img .next {
            position: absolute;
            font-size: 2em;
            color: #fff;
            cursor: pointer;
        }

        .fullscreen-img .close {
            top: 20px;
            right: 20px;
            user-select: none;
        }

        .fullscreen-img .prev, .fullscreen-img .next {
            top: 50%;
            transform: translateY(-50%);
        }

        .fullscreen-img .prev {
            left: 20px;
            user-select: none;
        }

        .fullscreen-img .next {
            right: 20px;
            user-select: none;
        }
        .authoranddate {
            margin-top: 10px;
            margin-bottom: 20px;
            font-size: 0.9em;
            color: #666;
        }

        .authoranddate .author {
            font-weight: bold;
            margin-right: 10px;
        }

        .authoranddate .date {
            color: #888;
        }
        .popular-button {
            color: #4CAF50; 
            border: 2px solid #4CAF50;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            width: 100%;
            border-radius: 4px;
            font-weight: bold;
            background-color: transparent;
            transition: all .2s linear;
        }
        .red{
            color: #AF4C50; 
            border: 2px solid #AF4C50;
        }
        .blue{
            color: #4C50AF; 
            border: 2px solid #4C50AF;
        }
        .popular-button:hover {
            border: 2px solid transparent ;
            background-color: #45a049;
            color: white;
        }
        .red:hover{
            background-color: #a04549;
        }
        .blue:hover{
            background-color: #4C50AF;
        }
        @media screen and (max-width: 768px) {
            .container {
                padding: 20px;
            }
            header h1 {
                font-size: 2em;
            }
            .container p, .full-content {
                font-size: 1em;
            }
            .galeria img {
                width: calc(50% - 10px);
            }
        }
    </style>
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($article['tytul']); ?></h1>
    </header>
    <div class="container">
        <img src="<?php echo htmlspecialchars($article['zdjecie_header']); ?>" alt="Zdjęcię artykułu">
        <?php if (isset($_SESSION['Rola_user']) && $_SESSION['Rola_user'] == 'Admin' && $article['is_popular'] == '0') : ?>
            <button class="popular-button" onclick="PopularArticle()">Dodaj do popularnych</button>
        <?php elseif(isset($_SESSION['Rola_user']) && $_SESSION['Rola_user'] == 'Admin' && $article['is_popular'] == '1') : ?>
            <button class="popular-button" onclick="PopularArticle()">Usuń z popularnych</button> 
        <?php endif; if (isset($_SESSION['Rola_user']) && $_SESSION['Rola_user'] == 'Admin'): ?>
            <form action="deletepost_all.php" method="POST">
                <input type="hidden" name="id_article" value="<?=$article['id']?>">
                <button type="submit" class="popular-button red">Usuń ogłoszenie</button>
            </form>
        <?php endif; ?>
        <?php if ((isset($_SESSION['Rola_user']) && $_SESSION['Rola_user'] == 'Admin') ||  (isset($_SESSION['user_id']) && $article['id_autora'] == $_SESSION['user_id'])): ?>
            <button onclick="EditArticle()" class="popular-button blue">Edytuj ogłoszenie</button>
        <?php endif; ?>
        <div class="full-content">
            <p><?php echo nl2br(htmlspecialchars($article['tresc'])); ?></p>
        </div>

        <div class="galeria">
            <?php foreach (json_decode($article['zdjecia']) as $image): ?>
                <img src="<?php echo htmlspecialchars($image); ?>" alt="Zdjęcie w galerii">
            <?php endforeach; ?>
        </div>

        <div class="authoranddate">
            <?php 
                $autor = $article['id_autora'];
                $sql = "SELECT * FROM users WHERE id = '$autor'";
                $result = $conn->query($sql);
                $author_info = $result->fetch_assoc();
                echo '<span class="author">Autor: ' . $author_info['Imie'] . ' ' . $author_info['Nazwisko'] . '</span>';
                echo '<span class="date">Data: ' . $article['data'] . '</span>';
            ?>
        </div>
        <a href="index.php" class="back-link"><i class="fas fa-chevron-left"></i> Powrót do ogłoszeń</a>

    </div>

    <div class="fullscreen-img" id="fullscreenImg">
        <span class="close" id="closeFullscreen">&times;</span>
        <span class="prev" id="prevImg">&#10094;</span>
        <span class="next" id="nextImg">&#10095;</span>
        <img src="" alt="Fullscreen Image" id="fullscreenImgSrc">
    </div>



    <script>
        <?php if(isset($_SESSION['Rola_user']) && $_SESSION['Rola_user'] == 'Admin') : ?>
            function PopularArticle() {
                fetch('make_popular.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: <?= $article['id'] ?> })
                })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    window.location.reload();
                })
            }
        <?php endif; ?>

        document.addEventListener("DOMContentLoaded", function() {
            const galleryImages = document.querySelectorAll(".galeria img");
            const fullscreenImg = document.getElementById("fullscreenImg");
            const fullscreenImgSrc = document.getElementById("fullscreenImgSrc");
            const closeFullscreen = document.getElementById("closeFullscreen");
            const prevImg = document.getElementById("prevImg");
            const nextImg = document.getElementById("nextImg");
            
            let currentIndex = 0;

            function showImage(index) {
                fullscreenImg.style.display = "flex";
                fullscreenImgSrc.src = galleryImages[index].src;
                currentIndex = index;
            }

            galleryImages.forEach((img, index) => {
                img.addEventListener("click", () => {
                    showImage(index);
                });
            });

            closeFullscreen.addEventListener("click", () => {
                fullscreenImg.style.display = "none";
                fullscreenImgSrc.src = "";
            });

            prevImg.addEventListener("click", () => {
                if (currentIndex > 0) {
                    showImage(currentIndex - 1);
                }
            });

            nextImg.addEventListener("click", () => {
                if (currentIndex < galleryImages.length - 1) {
                    showImage(currentIndex + 1);
                }
            });

            fullscreenImg.addEventListener("click", (e) => {
                if (e.target !== fullscreenImgSrc && e.target !== prevImg && e.target !== nextImg) {
                    fullscreenImg.style.display = "none";
                    fullscreenImgSrc.src = "";
                }
            });
        });
    </script>


    <?php include 'footer.php'; ?>
</body>
</html>
