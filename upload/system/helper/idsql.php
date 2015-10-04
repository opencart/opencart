<?php
function getIdSql($id) {
        $sql_id = "";

        if (empty($id)) {
            $sql_id = " IS NULL";
        } else {
            $sql_id = " = '" . (int)$id . "'";
        }  

        return $sql_id;
}

