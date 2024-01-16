<?php

session_start();

require_once "UserStorage.php";
require_once "Authentication.php";
require_once "CardStorage.php";

$userStorage = new UserStorage();
$cardStorage = new CardStorage();
$auth = new Authentication($userStorage);

function navigationToIndex() : void {
    header('Location: index.php');
    exit();
}

if (!$auth->is_authenticated()) navigationToProfile();

if(isset($_GET["cardId"])) {
    $card = $cardStorage->findById($_GET["cardId"]);

    if(isset($card)) {
        $user = $userStorage->findOne([ "username" => $_SESSION["user"]["username"] ]);
        $userCardsCount = count($cardStorage->findAll([ "owner" => $_SESSION["user"]["username"] ]));
        if($userCardsCount < 5 && $user["role"] != "admin" && $user["coins"] - $card["price"] > 0) {
            $card["owner"] = $_SESSION["user"]["username"];  
            $card["owner-role"] = $_SESSION["user"]["role"];
            $user["coins"] -= $card["price"];

            $cardStorage->update($_GET["cardId"], $card);
            $userStorage->update($user["id"], $user);
            $_SESSION["user"]["coins"] -= $card["price"];
        }
    }
}

navigationToIndex();
?>
