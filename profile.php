<?php

session_start();

require_once "UserStorage.php";
require_once "Authentication.php";
require_once 'CardStorage.php';

$cardStorage = new CardStorage();
$entries = $cardStorage->findAll();
$auth = new Authentication(new UserStorage());

if (!$auth->is_authenticated()) {
    header('Location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="hu-HU">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/profile.css">
    <link rel="stylesheet" href="styles/cards.css">
    <title>IKémon | Profile</title>
</head>

<body>
    <header>
        <div class="title">
            <h1><a href="index.php">IKémon</a> > Profile</h1>
        </div>
        <div class="right-details-box">
            <details>
                <summary><?= $_SESSION["user"]["username"] ?><br><span class="icon"><?= $_SESSION["user"]["coins"] ?? "-" ?>💰</span></summary>
                <ul>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </details>
        </div>
    </header>

    <main>
        <div id="content">
            <h2>Hello <?= $_SESSION["user"]["username"] ?? "" ?> (<?= $_SESSION["user"]["role"] ?>)!</h2>
            <div class="card-hp"><span class="icon">📧</span> Email: <?= $_SESSION["user"]["email"] ?? "-" ?></div>
            <div class="card-hp"><span class="icon">💰</span> Coins: <?= $_SESSION["user"]["coins"] ?? "-" ?></div>
            <h2>Your cards:</h2>
            <?php if($_SESSION["user"]["role"] == "admin"): ?>
                <button onclick="window.location.href='./newCard.php'">Add new card</button>
            <?php endif; ?>
            <div id="card-list">
                <?php foreach ($entries as $key => $entry) : ?>
                    <?php if ($entry["owner"] === $_SESSION["user"]["username"]) : ?>
                        <div class="pokemon-card">
                            <div class="image clr-<?= $entry["type"] ?>">
                                <img src="<?= $entry["image"] ?>" alt="">
                            </div>
                            <div class="details">
                                <h2><a href="details.php?id=<?= $key ?>"><?= $entry["name"] ?></a></h2>
                                <span class="card-type"><span class="icon">🏷</span> <?= $entry["type"] ?></span>
                                <span class="attributes">
                                    <span class="card-hp"><span class="icon">❤</span> <?= $entry["hp"] ?></span>
                                    <span class="card-attack"><span class="icon">⚔</span> <?= $entry["attack"] ?></span>
                                    <span class="card-defense"><span class="icon">🛡</span> <?= $entry["defense"] ?></span>
                                </span>
                            </div>
                            <div class="buy">
                                <span class="card-price" data-card="<?= $key ?>"><span class="icon">💰</span> <?= $entry["price"] ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <script type="module">
            import { navigateTo } from "./card.js";
            navigateTo("sell.php", "div.buy");
        </script>
    </main>

    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>