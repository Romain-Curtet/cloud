<?php

function doSQL($sql, $listParam)
{
    try {
        $db = new PDO("mysql:host=localhost;port=3308;dbname=cloud;charset=UTF8", "root", "");
        //$db = new PDO("mysql:host=localhost;dbname=romaincu_cloud;charset=UTF8", "romaincu_romaincu", "Curt290887.");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $request = $db->prepare($sql);
        $request->execute($listParam);

        $completeArray = array();
        while ($row = $request->fetch(PDO::FETCH_ASSOC)) {
            $completeArray[] = $row;
        }

        $request->closeCursor();
        $db = null;
        return $completeArray;
    } catch (PDOException $e) {
        echo 'error : ' . $e->getMessage();
    }
}