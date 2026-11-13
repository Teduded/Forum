
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

// Hämta ämnet
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

$error = "";

// Spara nytt inlägg
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $content = trim($_POST["content"] ?? "");

    if ($content === "") {

        $error = "Skriv något innan du publicerar.";

    } else {

        $stmt = $pdo->prepare(
            "INSERT INTO posts (topic_id, user_id, content)
             VALUES (?, ?, ?)"
        );

        $stmt->execute([
            $topic_id,
            $_SESSION["user_id"],
            $content
        ]);

        header("Location: topic.php?id=" . $topic_id);
        exit;
    }
}

// Hämta alla inlägg
$stmt = $pdo->prepare(
    "SELECT posts.content, posts.created_at, users.name
     FROM posts
     JOIN users ON posts.user_id = users.id
     WHERE posts.topic_id = ?
     ORDER BY posts.created_at ASC"
);

$stmt->execute([$topic_id]);

$posts = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($topic["title"]) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="top-bar">
        <a href="index.php" class="nav-button">← Tillbaka till forumet</a>
        <a href="logout.php" class="nav-button logout-button">Logga ut</a>
    </div>

    <h1><?= htmlspecialchars($topic["title"]) ?></h1>

    <p>
        Skapat av:
        <?= htmlspecialchars($topic["name"]) ?>
    </p>

    <hr>

    <h2>Skriv ett inlägg</h2>

    <?php if ($error): ?>
        <p class="error">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <div class="write-box">

        <form method="POST">

            <textarea
                name="content"
                rows="5"
                placeholder="Skriv ditt inlägg här..."
                required
            ></textarea>

            <br><br>

            <button type="submit">Publicera inlägg</button>

        </form>

    </div>

    <hr>

    <h2>Diskussion</h2>

    <?php if (count($posts) > 0): ?>

        <?php foreach ($posts as $post): ?>

            <div class="post">

                <p class="post-content">
                    <?= nl2br(htmlspecialchars($post["content"])) ?>
                </p>

                <p class="post-info">
                    Skrivet av:
                    <strong><?= htmlspecialchars($post["name"]) ?></strong>
                </p>

                <p class="post-info">
                    <?= htmlspecialchars($post["created_at"]) ?>
                </p>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p>Inga inlägg ännu.</p>

    <?php endif; ?>

</body>
</html>
