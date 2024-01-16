<?php

session_start();

require_once "UserStorage.php";
require_once "Authentication.php";
require_once "CardStorage.php";

$userStorage = new UserStorage();
$cardStorage = new CardStorage();
$auth = new Authentication($userStorage);

function navigationToProfile() : void {
    header('Location: profile.php');
    exit();
}

if (!$auth->is_authenticated()) {
    navigationToProfile();
}

if(isset($_GET["cardId"])) {
    $card = $cardStorage->findById($_GET["cardId"]);

    if(isset($card) && $card["owner"] == $_SESSION["user"]["username"]) {
        $user = $userStorage->findOne([ "username" => $_SESSION["user"]["username"] ]);
        if($user["role"] != "admin") {
            $card["owner"] = "admin";
            $card["owner-role"] = "admin";
            $price = (int)round($card["price"] * 0.9);
            
            $user["coins"] += $price;

            $cardStorage->update($_GET["cardId"], $card);
            $userStorage->update($user["id"], $user);
            $_SESSION["user"]["coins"] += $price;
        }
    }
}

navigationToProfile();
?>
