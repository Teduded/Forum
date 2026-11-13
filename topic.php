
<?php

require 'config/database.php';

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$topic_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$topic_id) {
    die("Ogiltigt ämne.");
}

// Hämta ämnet och skaparen
$stmt = $pdo->prepare(
    "SELECT topics.id, topics.title, users.name
     FROM topics
     JOIN users ON topics.user_id = users.id
     WHERE topics.id = ?"
);

$stmt->execute([$topic_id]);

$topic = $stmt->fetch();

if (!$topic) {
    die("Ämnet finns inte.");
}

?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($topic["title"]) ?></title>
</head>

<body>

    <h1><?= htmlspecialchars($topic["title"]) ?></h1>

    <p>
        Skapat av:
        <?= htmlspecialchars($topic["name"]) ?>
    </p>

    <h2>Diskussion</h2>

    <p>Här kommer inläggen och svaren att visas.</p>

    <p><a href="index.php">Tillbaka till forumet</a></p>

</body>
</html>
