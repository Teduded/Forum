
<?php

require 'config/database.php';

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Hämta alla ämnen och deras skapare
$stmt = $pdo->query(
    "SELECT topics.id, topics.title, topics.created_at, users.name
     FROM topics
     JOIN users ON topics.user_id = users.id
     ORDER BY topics.created_at DESC"
);

$topics = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <title>Forum för fårskallar</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="top-bar">

        <h1>Forum för fårskallar</h1>

        <a href="logout.php" class="nav-button logout-button">
            Logga ut
        </a>

    </div>

    <h2>
        Välkommen, <?= htmlspecialchars($_SESSION["name"]) ?>!
    </h2>

    <p>Du är inloggad.</p>

    <div class="navigation">

        <a href="create_topic.php" class="nav-button">
            + Skapa nytt ämne
        </a>

    </div>

    <hr>

    <h2>Diskussionsämnen</h2>

    <?php if (count($topics) > 0): ?>

        <?php foreach ($topics as $topic): ?>

            <div class="topic">

                <h3>
                    <a href="topic.php?id=<?= $topic["id"] ?>">
                        <?= htmlspecialchars($topic["title"]) ?>
                    </a>
                </h3>

                <p>
                    Skapat av:
                    <?= htmlspecialchars($topic["name"]) ?>
                </p>

                <p class="post-info">
                    <?= htmlspecialchars($topic["created_at"]) ?>
                </p>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p>Det finns inga ämnen ännu. Skapa det första!</p>

    <?php endif; ?>

</body>
</html>
