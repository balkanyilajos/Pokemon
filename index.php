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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Home</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
    <header>
        <div class="title">
            <h1><a href="index.php">IKémon</a> > Home</h1>
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
            <form id="card-form" method="POST">
                <label for="card-selector">Choose a card type: </label>
                <select name="card-selector" id="card-selector">
                    <option value="">-</option>
                    <option value="electric" <?php if(isset($_POST['card-selector']) && $_POST['card-selector'] === 'electric') echo 'selected'; ?>>electric</option>
                    <option value="fire" <?php if(isset($_POST['card-selector']) && $_POST['card-selector'] === 'fire') echo 'selected'; ?>>fire</option>
                    <option value="grass" <?php if(isset($_POST['card-selector']) && $_POST['card-selector'] === 'grass') echo 'selected'; ?>>grass</option>
                    <option value="water" <?php if(isset($_POST['card-selector']) && $_POST['card-selector'] === 'water') echo 'selected'; ?>>water</option>
                    <option value="bug" <?php if(isset($_POST['card-selector']) && $_POST['card-selector'] === 'bug') echo 'selected'; ?>>bug</option>
                    <option value="normal" <?php if(isset($_POST['card-selector']) && $_POST['card-selector'] === 'normal') echo 'selected'; ?>>normal</option>
                    <option value="poison" <?php if(isset($_POST['card-selector']) && $_POST['card-selector'] === 'poison') echo 'selected'; ?>>poison</option>
                </select>
            </form>

            <div id="card-list">
                <?php foreach ($entries as $key => $entry) : ?>
                    <?php if(isset($entry["owner-role"]) && $entry["owner-role"] == "admin" && (!isset($_POST['card-selector']) || $_POST['card-selector'] === "" || $_POST['card-selector'] === $entry["type"]) ): ?>
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
            import { navigateTo, sendSelectTagChangeWithForm } from "./card.js";
            navigateTo("buy.php", "div.buy");
            sendSelectTagChangeWithForm('select#card-selector', 'form#card-form');
        </script>
    </main>

    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>