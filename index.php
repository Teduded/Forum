
<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <title>Forum för fårskallar</title>
</head>

<body>

    <h1>Forum för fårskallar</h1>

    <h2>
        Välkommen, <?= htmlspecialchars($_SESSION["name"]) ?>!
    </h2>

    <p>Du är inloggad.</p>
    <p><a href="logout.php">Logga ut</a></p>

</body>
</html>
