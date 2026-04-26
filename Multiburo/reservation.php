<?php
require_once 'db.inc.php';
require_once 'model.inc.php';
require_once 'functions.inc.php';

$id_bureau = '';
$date      = '';

if (isset($_GET['id']))
{
    $id_bureau = $_GET['id'];
}

if (isset($_GET['date']))
{
    $date = $_GET['date'];
}


$nom    = '';
$prenom = '';
$erreur = '';
$succes = '';

if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['date']))
{
    $nom       = $_POST['nom'];
    $prenom    = $_POST['prenom'];
    $date      = $_POST['date'];
    $id_bureau = $_POST['id_bureau'];

    
    if ($nom == '')
    {
        $erreur = "Le champ Nom est obligatoire.";
    }
    elseif ($prenom == '')
    {
        $erreur = "Le champ Prénom est obligatoire.";
    }
    elseif ($date == '')
    {
        $erreur = "La date est obligatoire.";
    }
    elseif (!estDateValide($date))
    {
        $erreur = "Vous ne pouvez pas choisir une date passée.";
    }
    else
    {
        
        $repUtil = getUtilisateur($nom, $prenom);

        if ($repUtil['ok'] == false)
        {
            
            $erreur = $repUtil['error'];
        }
        else
        {
            
            $id_util = $repUtil['data']['id'];

            
            $repDejaUtil = utilisateurDejaReserve($id_util, $date);

            if ($repDejaUtil['ok'] == false)
            {
                $erreur = $repDejaUtil['error'];
            }
            elseif ($repDejaUtil['existe'] == true)
            {
                $erreur = "Vous avez déjà une réservation pour ce jour, vous ne pouvez pas en ajouter une autre.";
            }
            else
            {
                
                $repDejaRess = ressourceDejaReservee($id_bureau, $date);

                if ($repDejaRess['ok'] == false)
                {
                    $erreur = $repDejaRess['error'];
                }
                elseif ($repDejaRess['existe'] == true)
                {
                    $erreur = "Cette ressource n'est plus disponible à cette date.";
                }
                else
                {
                    $rep = ajouterReservation($id_bureau, $id_util, $date);

                    if ($rep['ok'] == true)
                    {
                        $succes = "Votre réservation a bien été enregistrée !";
                        $nom    = '';
                        $prenom = '';
                    }
                    else
                    {
                        $erreur = $rep['error'];
                    }
                }
            }
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

    <div class="hero">
        <h1><?php echo afficherLib($id_bureau); ?></h1>
        <p>Réservez cet espace de travail pour la date de votre choix.</p>
        <a href="ressources.php" class="btn">Voir les disponibilités</a>
    </div>

    <h2 class="section-titre">Formulaire de réservation</h2>

    <?php if ($succes != '') { ?>
        <p class="message succes"><?php echo $succes; ?></p>
    <?php } ?>

    <?php if ($erreur != '') { ?>
        <p class="message erreur"><?php echo $erreur; ?></p>
    <?php } ?>

    <!-- Formulaire de réservation -->
    <?php if ($succes == '') { ?>

        <form action="reservation.php" method="POST">

            <!-- Champ caché pour l'id de la ressource -->
            <input type="hidden" name="id_bureau" value="<?php echo $id_bureau; ?>">

            <div class="filtres">

                <div class="champ">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom"
                           maxlength="50"
                           value="<?php echo $prenom; ?>">
                </div>

                <div class="champ">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom"
                           maxlength="50"
                           value="<?php echo $nom; ?>">
                </div>

                <div class="champ">
                    <label for="date">Date souhaitée</label>
                    <input type="date" name="date" id="date"
                           min="<?php echo date("Y-m-d"); ?>"
                           value="<?php echo $date; ?>">
                </div>

                <div class="champ">
                    <input type="submit" value="Confirmer la réservation" class="btn">
                </div>

            </div>

        </form>

    <?php } ?>

</main>

<footer>
    &copy; 2026 MULTIBURO — Lyon 3
</footer>

</body>
</html>