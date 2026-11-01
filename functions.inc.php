<?php
// functions.inc.php — Fonctions réutilisables

require_once 'model.inc.php';

// Utilisateur considéré comme déjà connecté
define('ID_UTIL_CONNECTE', 1);

// Pas d'identification : on place l'utilisateur connecté dans la session
// (celui choisi dans la liste déroulante, sinon celui par défaut)
function connecterUtilisateur()
{
    if (isset($_POST['id_util']))
    {
        $id_util = $_POST['id_util'];
    }
    elseif (!isset($_SESSION['id_util']))
    {
        $id_util = ID_UTIL_CONNECTE;
    }
    else
    {
        return;
    }

    $rep = getUtilisateur($id_util);
    if ($rep['ok'] == true)
    {
        $_SESSION['id_util'] = $rep['data']['id'];
        $_SESSION['nom']     = $rep['data']['nom'];
        $_SESSION['prenom']  = $rep['data']['prenom'];
    }
    else
    {
        exit($rep['error']);
    }
}

function listeUtilisateurs()
{
    $afficher = '';
    $rep = getUtilisateurs();
    if ($rep['ok'] == true)
    {
        foreach ($rep['data'] as $ligne)
        {
            $selected = '';
            if ($ligne['id'] == $_SESSION['id_util'])
            {
                $selected = ' selected';
            }
            $afficher .= '<option value="'.($ligne['id']).'"'.$selected.'>'.htmlspecialchars($ligne['prenom'].' '.$ligne['nom']).'</option>';
        }
        return $afficher;
    }
    else
    {
        return '';
    }
}

function listePlacesParType($date)
{
    $afficher = '';
    $rep = placesDispoParType($date);
    if ($rep['ok'] == true)
    {
        foreach ($rep['data'] as $ligne)
        {
            $afficher .= '<tr><td>'.htmlspecialchars($ligne['lib']).'</td><td>'.(int)$ligne['nb_espaces'].'</td><td>'.(int)$ligne['nb_places'].'</td></tr>';
        }
        return $afficher;
    }
    else
    {
        return $rep['error'];
    }
}

function listeProduit($date)
{
    $afficher='';
    $rep = accesBDD($date);
    if ($rep['ok'] == true)
    {
        foreach ($rep['data'] as $ligne)
        {
            $afficher .= '<tr><td>'.htmlspecialchars($ligne['lib']).'</td><td>'.($ligne['nb_place']).'</td><td><a href="reservation.php?id='.($ligne['id']).'&date='.$date.'" class="btn-reserver">Réserver</a></td></tr>';
        }
        return $afficher;
    }
    else
    {
        return $rep['error'];
    }
}

function afficherLib($id_bureau)
{
    $afficher = '';
    $rep      = getLib($id_bureau);

    if ($rep['ok'] == true)
    {
        foreach ($rep['data'] as $ligne)
        {
            $afficher .= htmlspecialchars($ligne['lib']);
        }
        return $afficher;
    }
    else
    {
        return $rep['error'];
    }
}

// Date au format AAAA-MM-JJ, réelle, et pas dans le passé
function estDateValide($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date && $date >= date("Y-m-d");
}
?>
