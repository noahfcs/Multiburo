<?php
// functions.inc.php — Fonctions réutilisables

require_once 'model.inc.php';

function listeProduit($date)
{
    $afficher='';
    $rep = accesBDD();
    if ($rep['ok'] == true)
    {
        foreach ($rep['data'] as $ligne)
        {
            $afficher .= '<tr><td>'.($ligne['lib']).'</td><td>'.($ligne['nb_place']).'</td><td><a href="reservation.php?id='.($ligne['id']).'&date='.$date.'" class="btn-reserver">Réserver</a></td></tr>';
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
            $afficher .= $ligne['lib'];
        }
        return $afficher;
    }
    else
    {
        return $rep['error'];
    }
}

function estDateValide($date)
{
    return $date >= date("Y-m-d");
}
?>
