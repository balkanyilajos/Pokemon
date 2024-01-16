<?php

session_start();

require_once "UserStorage.php";
require_once 'CardStorage.php';
require_once "Authentication.php";

$auth = new Authentication(new UserStorage());
$cardStorage = new CardStorage();

function headerToProfile() : void {
    header('Location: profile.php');
    exit();
}

if (!$auth->is_authenticated() || $_SESSION["user"]["role"] != "admin") {
    headerToProfile();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];

    $name = trim($_POST["name"] ?? "");
    if (strlen($name) == 0) {
        $errors["name"] = "Entering the name is mandatory!";
    } else if (strlen($name) > 16) {
        $errors["name"] = "The name can be a maximum of 16 characters long!";
    } else if (!is_null($cardStorage->findOne(["name" => $name]))) {
        $errors["name"] = "The specified name is taken!";
    }

    $health = trim($_POST["health"] ?? "");
    if (strlen($health) == 0) {
        $errors["health"] = "Entering the health is mandatory!";
    } else if (!is_numeric($health)) {
        $errors["health"] = "The input is not a number!";
    } else if (intval($health) <= 0) {
        $errors["health"] = "The input must be at least 1!";
    } else if (intval($health) != floatval($health)) {
        $errors["health"] = "The input must be an integer!";
    } else {
        $health = intval($health);
    }

    $attack = trim($_POST["attack"] ?? "");
    if (strlen($attack) == 0) {
        $errors["attack"] = "Entering the attack is mandatory!";
    } else if (!is_numeric($attack)) {
        $errors["attack"] = "The input is not a number!";
    } else if (intval($attack) <= 0) {
        $errors["attack"] = "The input must be at least 1!";
    } else if (intval($attack) != floatval($attack)) {
        $errors["attack"] = "The input must be an integer!";
    } else {
        $attack = intval($attack);
    }

    $defense = trim($_POST["defense"] ?? "");
    if (strlen($defense) == 0) {
        $errors["defense"] = "Entering the defense is mandatory!";
    } else if (!is_numeric($defense)) {
        $errors["defense"] = "The input is not a number!";
    } else if (intval($defense) <= 0) {
        $errors["defense"] = "The input must be at least 1!";
    } else if (intval($defense) != floatval($defense)) {
        $errors["defense"] = "The input must be an integer!";
    } else {
        $defense = intval($defense);
    }

    $price = trim($_POST["price"] ?? "");
    if (strlen($price) == 0) {
        $errors["price"] = "Entering the price is mandatory!";
    } else if (!is_numeric($price)) {
        $errors["price"] = "The input is not a number!";
    } else if (intval($price) <= 0) {
        $errors["price"] = "The input must be at least 1!";
    } else if (intval($price) != floatval($price)) {
        $errors["price"] = "The input must be an integer!";
    } else {
        $price = intval($price);
    }

    $description = trim($_POST["description"] ?? "");
    if (strlen($description) == 0) {
        $errors["description"] = "Entering the description is mandatory!";
    } else if (strlen($description) > 256) {
        $errors["description"] = "The description can be a maximum of 256 characters long!";
    } else if (!is_null($cardStorage->findOne(["name" => $description]))) {
        $errors["description"] = "The specified description is taken!";
    }


    $image = trim($_POST["image"] ?? "");
    if (strlen($image) == 0) {
        $errors["image"] = "Entering the image link is mandatory!";
    } else if (strlen($image) > 360) {
        $errors["image"] = "The image link can be a maximum of 360 characters long!";
    } else if (!is_null($cardStorage->findOne(["image" => $image]))) {
        $errors["image"] = "The image is already taken!";
    } else if (!str_starts_with($image, "https://")) {
        $errors["image"] = "This is not a link!";
    }

    $types = ["electric", "fire", "grass", "water", "bug", "normal", "poison"];
    $type = $_POST["type"] ?? "";
    if (!in_array($type, $types)) {
        $errors["type"] = "Invalid input!";
    }

    if (count($errors) === 0) {
        $cardStorage->add([
            "name" => $name,
            "type" => $type,
            "hp" => $health,
            "attack" => $attack,
            "defense" => $defense,
            "price" => $price,
            "description" => $description,
            "image" => $image,
            "owner" => $_SESSION["user"]["username"],
            "owner-role" => $_SESSION["user"]["role"]
        ]);

        headerToProfile();
    }
}
?>

<!DOCTYPE html>
<html lang="hu-HU">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/newCard.css">
    <title>IKémon | Create new card</title>
</head>

<body>
    <header>
        <div class="title">
            <h1><a href="index.php">IKémon</a> > Card maker</h1>
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
        <form class="register" method="post">
            <label for="name">Name</label>
            <input id="name" name="name" value="<?= $name ?? "" ?>">
            <?php if (isset($errors["name"])) : ?><br><span class="error"><?= $errors["name"] ?></span><?php endif; ?>
            <br>

            <label for="health">Health</label>
            <input id="health" name="health" value="<?= $health ?? "" ?>">
            <?php if (isset($errors["health"])) : ?><br><span class="error"><?= $errors["health"] ?></span><?php endif; ?>
            <br>
            <label for="attack">Attack</label>
            <input id="attack" name="attack" value="<?= $attack ?? "" ?>">
            <?php if (isset($errors["attack"])) : ?><br><span class="error"><?= $errors["attack"] ?></span><?php endif; ?>
            <br>

            <label for="defense">Defense</label>
            <input id="defense" name="defense" value="<?= $defense ?? "" ?>">
            <?php if (isset($errors["defense"])) : ?><br><span class="error"><?= $errors["defense"] ?></span><?php endif; ?>
            <br>

            <label for="price">Price</label>
            <input id="price" name="price" value="<?= $price ?? "" ?>">
            <?php if (isset($errors["price"])) : ?><br><span class="error"><?= $errors["price"] ?></span><?php endif; ?>
            <br>

            <label for="description">Description</label>
            <input id="description" name="description" value="<?= $description ?? "" ?>">
            <?php if (isset($errors["description"])) : ?><br><span class="error"><?= $errors["description"] ?></span><?php endif; ?>
            <br>

            <label for="image">Image</label>
            <input id="image" name="image" value="<?= $image ?? "" ?>">
            <?php if (isset($errors["image"])) : ?><br><span class="error"><?= $errors["image"] ?></span><?php endif; ?>
            <br>

            <label for="card-selector">Card type </label>
            <select name="type" id="card-selector">
                <option value="electric" <?= (isset($type) && $type == "electric") ? "selected" : ""; ?>>electric</option>
                <option value="fire" <?= (isset($type) && $type == "fire") ? "selected" : ""; ?>>fire</option>
                <option value="grass" <?= (isset($type) && $type == "grass") ? "selected" : ""; ?>>grass</option>
                <option value="water" <?= (isset($type) && $type == "water") ? "selected" : ""; ?>>water</option>
                <option value="bug" <?= (isset($type) && $type == "bug") ? "selected" : ""; ?>>bug</option>
                <option value="normal" <?= (isset($type) && $type == "normal") ? "selected" : ""; ?>>normal</option>
                <option value="poison" <?= (isset($type) && $type == "poison") ? "selected" : ""; ?>>poison</option>
            </select>
            <br>

            <button type="submit">Send</button>
        </form>
    </main>

    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>