<?php

function doSQL($sql, $listParam)
{
    try {
        $db = new PDO("mysql:host=localhost;port=3308;dbname=cloud;charset=UTF8", "root", "");
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
        return "error : " . $e->getMessage();
    }
}