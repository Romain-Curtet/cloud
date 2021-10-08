<?php

function doSQL($sql, $listParam)
{
    try {
        $bdd = new PDO("mysql:host=localhost;port=3308;dbname=cloud;charset=UTF8", "root", "");
        //$bdd = new PDO("mysql:host=localhost;dbname=romaincu_cloud;charset=UTF8", "romaincu_romaincu", "Curt290887.");
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $requete = $bdd->prepare($sql);
        $requete->execute($listParam);

        $tableauComplet = array();
        while ($row = $requete->fetch(PDO::FETCH_ASSOC)) {
            $tableauComplet[] = $row;
        }

        $requete->closeCursor();
        $bdd = null;
        return $tableauComplet;
    } catch (PDOException $e) {
        return "error : " . $e->getMessage();
    }
}