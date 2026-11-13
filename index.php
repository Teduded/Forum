
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
</head>

<body>

    <h1>Forum för fårskallar</h1>

    <h2>
        Välkommen, <?= htmlspecialchars($_SESSION["name"]) ?>!
    </h2>

    <p>Du är inloggad.</p>

    <p><a href="create_topic.php">Skapa nytt ämne</a></p>

    <h2>Diskussionsämnen</h2>

    <?php if (count($topics) > 0): ?>

        <?php foreach ($topics as $topic): ?>

            <div>

                <h3>
                    <a href="topic.php?id=<?= $topic["id"] ?>">
                        <?= htmlspecialchars($topic["title"]) ?>
                    </a>
                </h3>

                <p>
                    Skapat av:
                    <?= htmlspecialchars($topic["name"]) ?>
                </p>

                <p>
                    <?= htmlspecialchars($topic["created_at"]) ?>
                </p>

                <hr>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p>Det finns inga ämnen ännu. Skapa det första!</p>

    <?php endif; ?>

    <p><a href="logout.php">Logga ut</a></p>

</body>
</html>
