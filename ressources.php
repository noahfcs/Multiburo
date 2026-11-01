<?php
    session_start();

    require_once 'db.inc.php';
    require_once 'functions.inc.php';
    require_once 'model.inc.php';

    connecterUtilisateur();

    $date   = date("Y-m-d");
    $erreur = '';

    if (isset($_POST['date'])) 
    {
        $date = $_POST['date'];
    }

    if ($date != '' && !estDateValide($date)) 
    {
        $erreur = "Impossible : cette date est déjà passée.";
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
        <a href="ressources.php" class="actif">Disponibilités</a>
    </nav>
</header>

<main>

    <h2 class="section-titre">Espaces disponibles</h2>

    <form action="ressources.php" method="POST">
        <div class="filtres">
            <div class="champ">
                <label for="id_util">Utilisateur</label>
                <select name="id_util" id="id_util">
                    <?php echo listeUtilisateurs(); ?>
                </select>
            </div>
            <div class="champ">
                <label for="date">Date souhaitée</label>
                <input type="date" name="date" id="date"
                       value="<?php echo htmlspecialchars($date); ?>">
            </div>
            <div class="champ">
                <input type="submit" value="Rechercher" class="btn">
            </div>

        </div>

    </form>

    <?php if ($erreur != '') { ?>
        <p class="message erreur"><?php echo htmlspecialchars($erreur); ?></p>
    <?php } ?>
    <?php if ($date != '' && $erreur == '') { ?>

        <p class="info-resultat">Disponibilités pour le <strong><?php echo date('d/m/Y', strtotime($date)); ?></strong></p>

        <table class="table-types">
            <thead>
                <tr>
                    <th>Type d'espace</th>
                    <th>Espaces libres</th>
                    <th>Places libres</th>
                </tr>
            </thead>
            <tbody>
                <?php echo listePlacesParType($date); ?>
            </tbody>
        </table>

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
