<?php

date_default_timezone_set('Asia/Tokyo');
require 'qiita_json.php';
require_once 'extract_qiita_feed.php';
require_once 'qiita_json_api.php';
require_once 'db_main.php';

//Qiitaの人気記事を20件RSSから取得します
$rss_data = extract_qiita_feed('https://qiita.com/popular-items/feed.atom');
/*
↓内部のfeed.atomから人気記事を取得する場合のコード
$data = extract_qiita_feed("feed.atom");
*/
$db = new Database();
$db->insert_crawl_history();

//RSSのitem_idをもとにAPIにアクセスして必要な情報を配列で返す関数です
$api_data = qiita_json_api($rss_data);

//トランザクション
try {
    $db->pdo->beginTransaction();
    //authors_tbl,articles_tbl,rss_history,qiita_page_tags,tags_tbl全てにAPIの情報を登録します
    $db->insert_author($api_data);
    $db->insert_article($api_data);
    $db->insert_rss_history($api_data);
    $res = $db->tags($api_data);
    echo 'データベースに登録完了しました。';
    $db->pdo->commit();
} catch (PDOException $e) {
    $db->pdo->rollBack();
    echo $e->getMessage();
    die();
}
$db = null;
