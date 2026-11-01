<?php
session_start();

require_once 'db.inc.php';
require_once 'model.inc.php';
require_once 'functions.inc.php';

connecterUtilisateur();

$id_bureau = '';
$date      = '';
$lib       = '';
$erreur    = '';
$succes    = '';

// Le lien "Réserver" arrive en GET, la confirmation du formulaire en POST
if (isset($_POST['id']))
{
    $id_bureau = $_POST['id'];
}
elseif (isset($_GET['id']))
{
    $id_bureau = $_GET['id'];
}

if (isset($_POST['date']))
{
    $date = $_POST['date'];
}
elseif (isset($_GET['date']))
{
    $date = $_GET['date'];
}

// Contrôle des données reçues
if (filter_var($id_bureau, FILTER_VALIDATE_INT) === false || !estDateValide($date))
{
    $erreur = "Ressource ou date invalide.";
}
else
{
    $rep = getLib($id_bureau);
    if ($rep['ok'] == true)
    {
        $lib = $rep['data'][0]['lib'];
    }
    else
    {
        $erreur = "Cette ressource n'existe pas.";
    }
}

// Enregistrement de la réservation
if ($erreur == '' && isset($_POST['reserver']))
{
    $id_util = $_SESSION['id_util'];

    $repUtil = utilisateurDejaReserve($id_util, $date);
    $repRess = ressourceDejaReservee($id_bureau, $date);

    if ($repUtil['ok'] == false || $repRess['ok'] == false)
    {
        $erreur = "Erreur base de données, veuillez réessayer plus tard.";
    }
    elseif ($repUtil['existe'] == true)
    {
        $erreur = "Vous avez déjà une réservation pour ce jour, vous ne pouvez plus en ajouter.";
    }
    elseif ($repRess['existe'] == true)
    {
        $erreur = "Cette ressource n'est plus disponible, vous ne pouvez pas la réserver.";
    }
    else
    {
        $rep = ajouterReservation($id_bureau, $id_util, $date);
        if ($rep['ok'] == true)
        {
            $succes = "Votre réservation est confirmée.";
        }
        else
        {
            $erreur = "La réservation n'a pas pu être enregistrée.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MULTIBURO — Réservation</title>
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

    <h2 class="section-titre">Réservation</h2>

    <?php if ($erreur != '') { ?>
        <p class="message erreur"><?php echo htmlspecialchars($erreur); ?></p>
    <?php } ?>

    <?php if ($succes != '') { ?>
        <p class="message succes"><?php echo htmlspecialchars($succes); ?></p>
    <?php } ?>

    <?php if ($lib != '') { ?>
        <div class="recap">
            <h3>Récapitulatif</h3>
            <div class="ligne"><span>Espace de travail</span><span><?php echo htmlspecialchars($lib); ?></span></div>
            <div class="ligne"><span>Date</span><span><?php echo date('d/m/Y', strtotime($date)); ?></span></div>
            <div class="ligne"><span>Client</span><span><?php echo htmlspecialchars($_SESSION['prenom'].' '.$_SESSION['nom']); ?></span></div>
        </div>
    <?php } ?>

    <?php if ($lib != '' && $erreur == '' && $succes == '') { ?>
        <form action="reservation.php" method="POST">
            <input type="hidden" name="id" value="<?php echo (int)$id_bureau; ?>">
            <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
            <input type="submit" name="reserver" value="Confirmer la réservation" class="btn">
        </form>
    <?php } else { ?>
        <a href="ressources.php" class="btn">Retour aux disponibilités</a>
    <?php } ?>

</main>

<footer>
    2026 MULTIBURO — Lyon 3
</footer>

</body>
</html>
