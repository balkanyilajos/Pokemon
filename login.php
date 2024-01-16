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
    }

    $password = trim($_POST["password"] ?? "");
    if (strlen($password) == 0) {
        $errors["password"] = "Entering the password is mandatory!";
    }

    if (count($errors) === 0) {
        $user = $auth->authenticate($username, $password);

        if (!is_null($user)) {
            $auth->login($user);

            header("Location: login.php");
            exit();
        } else {
            $errors['invalid'] = "Incorrect username or password!";
        }
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
    <title>Authentication</title>
</head>

<body>
    <header>
        <h1>Authentication</h1>
    </header>
    <main>
        <form class="login" method="post">
            <label for="username">Name</label>
            <input id="username" name="username" value="<?= $username ?? "" ?>">
            <?php if (isset($errors["username"])) : ?><br><span class="error"><?= $errors["username"] ?></span><?php endif; ?>

            <br>

            <label for="password">Password</label>
            <input id="password" name="password" value="<?= $password ?? "" ?>">
            <?php if (isset($errors["password"])) : ?><br><span class="error"><?= $errors["password"] ?></span><?php endif; ?>

            <br>

            <a href="register.php">Create new account</a>
            <button type="submit">Login</button>
        </form>
    </main>
</body>

</html>