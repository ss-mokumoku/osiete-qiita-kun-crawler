<?php

date_default_timezone_set('Asia/Tokyo');
require 'extract_api.php';
require_once 'extract_qiita_feed.php';
require_once 'integrate.php';
require_once 'database.php';

//Qiitaの人気記事を20件RSSから取得します
$rss_data = extract_qiita_feed('https://qiita.com/popular-items/feed.atom');
// ↓内部のfeed.atomから人気記事を取得する場合のコード
//$rss_data = extract_qiita_feed('feed.atom');

$db = new Database();
$db->insert_crawl_history($rss_data);

//RSSのitem_idにAPIの情報を付け加えて、結果を連想配列にして返す
$api_data = integrate_rss_api($rss_data);

//トランザクション
try {
    $db->pdo->beginTransaction();
    //authors_tbl,articles_tbl,rss_history,qiita_page_tags,tags_tbl全てにAPIの情報を登録します
    $db->insert_author($api_data);
    $db->insert_article($api_data);
    $db->insert_rss_history($api_data);
    $res = $db->insert_tags($api_data);
    $db->insert_likescount_history();
    $db->insert_page_views_count_history();
    echo 'データベースに登録完了しました。';
    $db->pdo->commit();
} catch (PDOException $e) {
    $db->pdo->rollBack();
    echo $e->getMessage();
    die();
}
$db = null;
