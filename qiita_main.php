<?php
date_default_timezone_set('Asia/Tokyo');

require('qiita_json.php');
require_once ('extract_qiita_feed.php');
require_once ('qiita_json_api.php');
require_once('db_main.php');
//$a = qiita_json('1a49e860a09a613b09d4');
//print_r ($a);
//feed.atomを読み込んで、必要な情報を抽出し、表示しているプログラム 

//$data = extract_qiita_feed("https://qiita.com/popular-items/feed.atom");
$data = extract_qiita_feed("feed.atom");
$data2 = qiita_json_api($data);
//print_r($data2);

$db = new Database();

//$res = $db->tags($data2);
//print_r($res);


$db->insert_author($data2);
//$db->insert_article($data2);
//$db->insert_rss_history($data2);
















/*
for($s = 0; $s <= 19;$s++){
echo $data2['contents'][$s]['permanent_id']."\n";
}
*/
//$a = qiita_json($item_id);
//print_r ($a);



//print_r($data);

//#extract_and_print("feed.atom");



?>
