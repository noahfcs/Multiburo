<?php
require_once 'db.inc.php';

// Ressources encore libres pour la date choisie
function accesBDD($date)
{
    $rep = array();

    try
    {
        $cnx = new PDO('mysql:dbname='.BDD.';host='.HOST.';port='.PORT, LOGIN, PASSW);
        $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $cnx->prepare("SELECT id, lib, nb_place FROM ressource
                               WHERE id NOT IN (SELECT id_ress FROM reservation WHERE date = :date)");
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();

        if ($result = $stmt->fetchAll())
        {
            $rep['ok']   = true;
            $rep['data'] = $result;
        }
        else
        {
            $rep['ok']    = false;
            $rep['error'] = '<div class="center"><p><span class="alert">Aucune donnée</span></p></div>';
        }
    }
    catch (PDOException $e)
    {
        $rep['ok']    = false;
        $rep['error'] = '<div class="center"><p><span class="alert">Erreur base de données :</span><br>Veuillez réessayer plus tard.</p></div>';
        error_log($e->getMessage());
    }

    return $rep;
}

// Nombre d'espaces et de places libres par type pour la date choisie
function placesDispoParType($date)
{
    $rep = array();

    try
    {
        $cnx = new PDO('mysql:dbname='.BDD.';host='.HOST.';port='.PORT, LOGIN, PASSW);
        $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $cnx->prepare("SELECT t.lib, COUNT(r.id) AS nb_espaces, COALESCE(SUM(r.nb_place), 0) AS nb_places
                               FROM type_ressource t
                               LEFT JOIN ressource r ON r.id_type = t.id
                                    AND r.id NOT IN (SELECT id_ress FROM reservation WHERE date = :date)
                               GROUP BY t.id, t.lib
                               ORDER BY t.id");
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();

        $rep['ok']   = true;
        $rep['data'] = $stmt->fetchAll();
    }
    catch (PDOException $e)
    {
        $rep['ok']    = false;
        $rep['error'] = '<div class="center"><p><span class="alert">Erreur base de données :</span><br>Veuillez réessayer plus tard.</p></div>';
        error_log($e->getMessage());
    }

    return $rep;
}

function getUtilisateurs()
{
    $rep = array();

    try
    {
        $cnx = new PDO('mysql:dbname='.BDD.';host='.HOST.';port='.PORT, LOGIN, PASSW);
        $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $cnx->prepare("SELECT id, nom, prenom FROM utilisateur ORDER BY nom, prenom");
        $stmt->execute();

        $rep['ok']   = true;
        $rep['data'] = $stmt->fetchAll();
    }
    catch (PDOException $e)
    {
        $rep['ok']    = false;
        $rep['error'] = '<div class="center"><p><span class="alert">Erreur base de données :</span><br>Veuillez réessayer plus tard.</p></div>';
        error_log($e->getMessage());
    }

    return $rep;
}

function getUtilisateur($id_util)
{
    $rep = array();

    try
    {
        $cnx = new PDO('mysql:dbname='.BDD.';host='.HOST.';port='.PORT, LOGIN, PASSW);
        $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $cnx->prepare("SELECT id, nom, prenom FROM utilisateur WHERE id = :id_util");
        $stmt->bindParam(':id_util', $id_util, PDO::PARAM_INT);
        $stmt->execute();

        if ($result = $stmt->fetch())
        {
            $rep['ok']   = true;
            $rep['data'] = $result;
        }
        else
        {
            $rep['ok']    = false;
            $rep['error'] = '<div class="center"><p><span class="alert">Utilisateur inconnu</span></p></div>';
        }
    }
    catch (PDOException $e)
    {
        $rep['ok']    = false;
        $rep['error'] = '<div class="center"><p><span class="alert">Erreur base de données :</span><br>Veuillez réessayer plus tard.</p></div>';
        error_log($e->getMessage());
    }

    return $rep;
}

function getLib($id_bureau)
{
    $rep = array();

    try
    {
        $cnx = new PDO('mysql:dbname='.BDD.';host='.HOST.';port='.PORT, LOGIN, PASSW);
        $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $cnx->prepare("SELECT lib FROM ressource WHERE id = :srch_id");
        $stmt->bindParam(':srch_id', $id_bureau, PDO::PARAM_INT);
        $stmt->execute();

        if ($result = $stmt->fetchAll())
        {
            $rep['ok']   = true;
            $rep['data'] = $result;
        }
        else
        {
            $rep['ok']    = false;
            $rep['error'] = '<div class="center"><p><span class="alert">Aucune donnée</span></p></div>';
        }
    }
    catch (PDOException $e)
    {
        $rep['ok']    = false;
        $rep['error'] = '<div class="center"><p><span class="alert">Erreur base de données :</span><br>Veuillez réessayer plus tard.</p></div>';
        error_log($e->getMessage());
    }

    return $rep;
}



function utilisateurDejaReserve($id_util, $date)
{
    $rep = array();

    try
    {
        $cnx = new PDO('mysql:dbname='.BDD.';host='.HOST.';port='.PORT, LOGIN, PASSW);
        $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $cnx->prepare("SELECT id_util FROM reservation WHERE id_util = :id_util AND date = :date");
        $stmt->bindParam(':id_util', $id_util, PDO::PARAM_INT);
        $stmt->bindParam(':date',    $date,    PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetch())
        {
            $rep['ok']     = true;
            $rep['existe'] = true;
        }
        else
        {
            $rep['ok']     = true;
            $rep['existe'] = false;
        }
    }
    catch (PDOException $e)
    {
        $rep['ok']    = false;
        $rep['error'] = '<div class="center"><p><span class="alert">Erreur base de données :</span><br>Veuillez réessayer plus tard.</p></div>';
        error_log($e->getMessage());
    }

    return $rep;
}

function ressourceDejaReservee($id_ress, $date)
{
    $rep = array();

    try
    {
        $cnx = new PDO('mysql:dbname='.BDD.';host='.HOST.';port='.PORT, LOGIN, PASSW);
        $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $cnx->prepare("SELECT id_ress FROM reservation WHERE id_ress = :id_ress AND date = :date");
        $stmt->bindParam(':id_ress', $id_ress, PDO::PARAM_INT);
        $stmt->bindParam(':date',    $date,    PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetch())
        {
            // La ressource est déjà réservée ce jour-là
            $rep['ok']     = true;
            $rep['existe'] = true;
        }
        else
        {
            $rep['ok']     = true;
            $rep['existe'] = false;
        }
    }
    catch (PDOException $e)
    {
        $rep['ok']    = false;
        $rep['error'] = '<div class="center"><p><span class="alert">Erreur base de données :</span><br>Veuillez réessayer plus tard.</p></div>';
        error_log($e->getMessage());
    }

    return $rep;
}

function ajouterReservation($id_ress, $id_util, $date)
{
    $rep = array();

    try
    {
        $cnx = new PDO('mysql:dbname='.BDD.';host='.HOST.';port='.PORT, LOGIN, PASSW);
        $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $cnx->prepare("INSERT INTO reservation (id_ress, id_util, date) 
                                VALUES (:id_ress, :id_util, :date)");

        $stmt->bindParam(':id_ress',  $id_ress,  PDO::PARAM_INT);
        $stmt->bindParam(':id_util',  $id_util,  PDO::PARAM_INT);
        $stmt->bindParam(':date',     $date,     PDO::PARAM_STR);

        $stmt->execute();

        $rep['ok'] = true;
    }
    catch (PDOException $e)
    {
        $rep['ok']    = false;
        $rep['error'] = '<div class="center"><p><span class="alert">Erreur base de données :</span><br>Veuillez réessayer plus tard.</p></div>';
        error_log($e->getMessage());
    }

    return $rep;
}
?>