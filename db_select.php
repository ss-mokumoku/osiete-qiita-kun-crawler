<?php
/**	@file
 *  @brief SELECTを実行するコード
 *
 *  @author SystemSoft Arita-takahiro
 *  @date 2018/05/21
 */
    require 'db_main.php';
    $db = new Database();
    $sql = 'SELECT * FROM articles_tbl';
    $res = $db->select($sql);
    print_r($res);
