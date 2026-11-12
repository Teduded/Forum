
<?php

require 'config/database.php';

session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    // Hämta användaren från databasen
    $stmt = $pdo->prepare(
        "SELECT id, username, name, password
         FROM users
         WHERE username = ?"
    );

    $stmt->execute([$username]);

    $user = $stmt->fetch();

    // Kontrollera lösenordet
    if ($user && password_verify($password, $user["password"])) {

        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["name"] = $user["name"];

        header("Location: index.php");
        exit;

    } else {

        $error = "Felaktigt användarnamn eller lösenord.";

    }
}

?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <title>Logga in</title>
</head>

<body>

<h1>Logga in</h1>

<?php if ($error): ?>
    <p style="color:red;">
        <?= htmlspecialchars($error) ?>
    </p>
<?php endif; ?>

<form method="POST">

    <label>Användarnamn:</label><br>
    <input type="text" name="username" required>
    <br><br>

    <label>Lösenord:</label><br>
    <input type="password" name="password" required>
    <br><br>

    <button type="submit">Logga in</button>

</form>

</body>
</html>
