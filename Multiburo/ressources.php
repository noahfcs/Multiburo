<?php

    require_once 'db.inc.php';
    require_once 'functions.inc.php';
    require_once 'model.inc.php';

    $date   = date("Y-m-d");
    $erreur = '';

    if (isset($_POST['date'])) 
    {
        $date = $_POST['date'];
    }

    if ($date != '' && !estDateValide($date)) 
    {
        $erreur = "Vous ne pouvez pas choisir une date passée.";
        $date   = '';
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MULTIBURO — Disponibilités</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <span class="logo">MULTIBURO</span>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="ressources.php" class="actif">Disponibiliter</a>
    </nav>
</header>

<main>

    <h2 class="section-titre">Espaces disponibles</h2>

    <form action="ressources.php" method="POST">
        <div class="filtres">
            <div class="champ">
                <label for="date">Date souhaitée</label>
                <input type="date" name="date" id="date"
                       min="<?php echo date("Y-m-d"); ?>"
                       value="<?php echo ($date); ?>">
            </div>
            <div class="champ">
                <input type="submit" value="Rechercher" class="btn">
            </div>

        </div>

    </form>

    <?php if ($erreur != '') { ?>
        <p class="message erreur"><?php echo ($erreur); ?></p>
    <?php } ?>
    <?php if ($date != '' && $erreur == '') { ?>

        <table>
            <thead>
                <tr>
                    <th>Espace de travail</th>
                    <th>Nombre de places</th>
                    <th>Réservation</th>
                </tr>
            </thead>
            <tbody>
                <?php echo listeProduit($date); ?>
            </tbody>
        </table>
    <?php } ?>

</main>

<footer>
    &copy; 2026 MULTIBURO — Lyon 3
</footer>

</body>
</html>
