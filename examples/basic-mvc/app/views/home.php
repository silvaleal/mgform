<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Contact</h2>

    <?php foreach ($_SESSION['errors'] ?? [] as $error => $value): ?>
        <div>
            <span><?= $error ?>: </span>
            <span><?= $value ?></span>
        </div>
    <?php endforeach ?>

    <form method="post" data-mgformvalidation>
        <input type="text" name="nome" placeholder="Nome">
        <input type="text" name="clan" placeholder="Clan">
        <input type="text" name="email" placeholder="email">

        <button type="submit">Enviar</button>

    </form>
</body>

</html>