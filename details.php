<?php 
    session_start();

    require_once "UserStorage.php";
    require_once "Authentication.php";
    require_once "CardStorage.php";

    $cardStorage = new CardStorage();
    $auth = new Authentication(new UserStorage());

    if(!$auth->is_authenticated()) {
        header('Location: login.php');
        exit();
    }

    if(!isset($_GET["id"]) || $cardStorage->findById($_GET["id"]) === null) {
        header('Location: index.php');
        exit();
    }

    $entry = $cardStorage->findById($_GET["id"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | <?= $entry["name"] ?></title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/details.css">
</head>

<body>
    <header>
        <h1><a href="index.php">IKémon</a> > Pikachu</h1>
    </header>
    <div id="content">
        <div id="details">
            <div class="image clr-<?= $entry["type"] ?>">
                <img src="<?= $entry["image"] ?>" alt="">
            </div>
            <div class="info">
                <div class="description"> <?= $entry["description"] ?> </div>
                <span class="card-type"><span class="icon">🏷</span> Type: <?= $entry["type"] ?></span>
                <div class="attributes">
                    <div class="card-hp"><span class="icon">❤</span> Health: <?= $entry["hp"] ?></div>
                    <div class="card-attack"><span class="icon">⚔</span> Attack: <?= $entry["attack"] ?></div>
                    <div class="card-defense"><span class="icon">🛡</span> Defense: <?= $entry["defense"] ?></div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>