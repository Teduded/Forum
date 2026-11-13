
<?php

require 'config/database.php';

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");

    if ($title === "") {

        $error = "Skriv en rubrik.";

    } else {

        $stmt = $pdo->prepare(
            "INSERT INTO topics (title, user_id)
             VALUES (?, ?)"
        );

        $stmt->execute([
            $title,
            $_SESSION["user_id"]
        ]);

        header("Location: index.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <title>Skapa ämne</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<h1>Skapa ett nytt ämne</h1>

<?php if ($error): ?>
    <p style="color:red;">
        <?= htmlspecialchars($error) ?>
    </p>
<?php endif; ?>

<form method="POST">

    <label>Rubrik:</label><br>
    <input type="text" name="title" maxlength="200" required>
    <br><br>

    <button type="submit">Skapa ämne</button>

</form>

<p><a href="index.php">Tillbaka</a></p>

</body>
</html>
