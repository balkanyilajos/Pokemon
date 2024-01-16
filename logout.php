<?php

    session_start();

    require_once "Authentication.php";
    require_once "UserStorage.php";

    $auth = new Authentication(new UserStorage());

    $auth->logout();

    header('Location: index.php');
    exit();

?>