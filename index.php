<?php

require_once 'config.php';


$pdo = new \PDO(DSN, USER, PASSWORD);

$query = "SELECT * FROM articles";
$statement = $pdo->query($query);
$articles = $statement->fetchAll(PDO::FETCH_ASSOC);

$errors = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $newArticle = array_map('trim', $_POST);

    if (empty($newArticle['name'])) {
        $errors[] = 'Veuillez donenr un nom à votre article';
    }

    $maxLength = 100;

    if (strlen($newArticle['name']) > $maxLength) {
        $errors[] = 'Le nom de l\'article ne peut pas faire plus de ' . $maxLength . ' caractères';
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error;
        }
    } else {
        $query = 'INSERT INTO articles (name) VALUES (:name)';
        $statement = $pdo->prepare($query);
        $statement->bindValue('name', $newArticle['name']);

        $statement->execute();

        header('Location: index.php?message=Article ajouté !');
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>PDO Support</title>
</head>

<body>
    <span><?= $_GET['message'] ?></span>
    <h1>Ma liste de courses</h1>
    <ul>
        <?php foreach ($articles as $article) : ?>
            <li><?= htmlentities($article['name']) ?></li>
        <?php endforeach; ?>
    </ul>

    <form action="" method="POST">
        <label for="name">Article</label>
        <input required type="text" id="name" name="name">
        <button>Ajouter un article</button>
    </form>

</body>

</html>