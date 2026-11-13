
<?php

require 'config/database.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if ($name == "" || $username == "" || $password == "") {

        $error = "Fyll i alla fält.";

    } elseif (strlen($password) < 8) {

        $error = "Lösenordet måste vara minst 8 tecken.";

    } else {

        // Kontrollera om användarnamnet redan finns
        $stmt = $pdo->prepare(
            "SELECT id FROM users WHERE username = ?"
        );

        $stmt->execute([$username]);

        if ($stmt->fetch()) {

            $error = "Användarnamnet är redan upptaget.";

        } else {

            // Kryptera lösenordet
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Spara användaren i databasen
            $stmt = $pdo->prepare(
                "INSERT INTO users (name, username, password)
                 VALUES (?, ?, ?)"
            );

            $stmt->execute([
                $name,
                $username,
                $hashedPassword
            ]);

            $success = "Ditt konto har skapats!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <title>Registrera konto</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<h1>Registrera dig</h1>

<?php if ($error): ?>
    <p style="color:red;">
        <?= htmlspecialchars($error) ?>
    </p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color:green;">
        <?= htmlspecialchars($success) ?>
    </p>
<?php endif; ?>

<form method="POST">

    <label>Namn:</label><br>
    <input type="text" name="name" required>
    <br><br>

    <label>Användarnamn:</label><br>
    <input type="text" name="username" required>
    <br><br>

    <label>Lösenord:</label><br>
    <input type="password" name="password" required>
    <br><br>

    <button type="submit">
        Skapa konto
    </button>

</form>

</body>
</html>
