<?php
require_once 'db.inc.php';

function accesBDD()
{
    $rep = array();

    try
    {
        $cnx = new PDO('mysql:dbname='.BDD.';host='.HOST.';port='.PORT, LOGIN, PASSW);
        $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $cnx->prepare("SELECT id, lib, nb_place FROM ressource");
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
        $rep['error'] = '<div class="center"><p><span class="alert">Erreur base de données :</span><br>' . $e->getMessage() . '</p></div>';
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
        $rep['error'] = '<div class="center"><p><span class="alert">Erreur base de données :</span><br>' . $e->getMessage() . '</p></div>';
    }

    return $rep;
}

function getUtilisateur($nom, $prenom)
{
    $rep = array();

    try
    {
        $cnx = new PDO('mysql:dbname='.BDD.';host='.HOST.';port='.PORT, LOGIN, PASSW);
        $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $cnx->prepare("SELECT id FROM utilisateur WHERE nom = :nom AND prenom = :prenom");
        $stmt->bindParam(':nom',    $nom,    PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->execute();

        if ($result = $stmt->fetch())
        {
            $rep['ok']   = true;
            $rep['data'] = $result;
        }
        else
        {
            $rep['ok']    = false;
            $rep['error'] = "Aucun utilisateur trouvé avec ce nom et ce prénom.";
        }
    }
    catch (PDOException $e)
    {
        $rep['ok']    = false;
        $rep['error'] = '<div class="center"><p><span class="alert">Erreur base de données :</span><br>' . $e->getMessage() . '</p></div>';
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
        $rep['error'] = '<div class="center"><p><span class="alert">Erreur base de données :</span><br>' . $e->getMessage() . '</p></div>';
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
        $rep['error'] = '<div class="center"><p><span class="alert">Erreur base de données :</span><br>' . $e->getMessage() . '</p></div>';
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
        $rep['error'] = '<div class="center"><p><span class="alert">Erreur base de données :</span><br>' . $e->getMessage() . '</p></div>';
    }

    return $rep;
}
?>