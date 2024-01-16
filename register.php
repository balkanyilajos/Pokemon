<?php

session_start();

require_once "UserStorage.php";
require_once "Authentication.php";

$auth = new Authentication(new UserStorage());

if ($auth->is_authenticated()) {
    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];

    $username = trim($_POST["username"] ?? "");
    if (strlen($username) == 0) {
        $errors["username"] = "Entering the username is mandatory!";
    } else if (strlen($username) > 16) {
        $errors["username"] = "The username can be a maximum of 16 characters long!";
    } else if ($auth->user_exists($username)) {
        $errors["username"] = "The specified username is taken!";
    }

    $email = trim($_POST["email"] ?? "");
    if(strlen($email) == 0) {
        $errors["email"] = "Entering an email address is mandatory!";
    } else if(strlen($email) > 32) {
        $errors["email"] = "The email address can be a maximum of 32 characters long!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format!";
    }

    $password = trim($_POST["password"] ?? "");
    if (strlen($password) == 0) {
        $errors["password"] = "Entering the password is mandatory!";
    } else if(strlen($password) > 32) {
        $errors["password"] = "The password can be a maximum of 32 characters long!";
    }

    if (count($errors) === 0) {
        $auth->register([
            'username' => $username,
            'email'    => $email,
            'password' => $password,
            'coins'    => 0
        ]);

        header("Location: login.php");
        exit();
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
    <link rel="stylesheet" href="styles/authentication.css">
    <title>IKémon | Sign up</title>
</head>

<body>
    <header>
        <h1><a href="login.php">Login</a> > Sign up</h1>
    </header>
    <main>
        <form class="register" method="post">
            <label for="username">Name</label>
            <input id="username" name="username" value="<?= $username ?? "" ?>">
            <?php if (isset($errors["username"])) : ?><br><span class="error"><?= $errors["username"] ?></span><?php endif; ?>
            <br>
            <label for="email">Email address</label>
            <input id="email" name="email" value="<?= $email ?? "" ?>">
            <?php if (isset($errors["email"])) : ?><br><span class="error"><?= $errors["email"] ?></span><?php endif; ?>
            <br>
            <label for="password">Password</label>
            <input id="password" name="password" value="<?= $password ?? "" ?>">
            <?php if (isset($errors["password"])) : ?><br><span class="error"><?= $errors["password"] ?></span><?php endif; ?>
            <br>

            <button type="submit">Sign up</button>
        </form>
    </main>
</body>

</html>