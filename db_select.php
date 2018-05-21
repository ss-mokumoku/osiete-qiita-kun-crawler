<?php
    require('db_main.php');
    $db = new Database();
    $sql = "SELECT * FROM authors_tbl";
    $res = $db->select($sql);
    print_r($res);
?>
