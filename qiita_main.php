<?php
/**	@file
 *  @brief クローラーのmainファイル
 *
 *  @author SystemSoft Arita-takahiro
 *  @date 2018/05/31 最終更新
 */
date_default_timezone_set('Asia/Tokyo');
//RSSの情報を抽出して連想配列にするファイル
require_once 'extract_qiita_feed.php';
//APIの情報を抽出して連想配列にするファイル
require 'extract_api.php';
//RSSの連想配列とAPIの連想配列を組み合わせる
require_once 'integrate.php';
//PDOやINSERTなどデータベース関連の関数があるファイル
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
    //authors_tblにAPIの情報を登録します
    $db->insert_author($api_data);
    //articles_tblにAPIの情報を登録します
    $db->insert_article($api_data);
    //rss_historyにAPIの情報を登録します
    $db->insert_rss_history($api_data);
    //tags_tblにAPIの情報を登録します
    $res = $db->insert_tags($api_data);
    //いいね数をデータベースの履歴に登録
    $db->insert_likescount_history();
    //閲覧数をデータベースの履歴に登録
    $db->insert_page_views_count_history();
    echo 'データベースに登録完了しました。';
    $db->pdo->commit();
} catch (PDOException $e) {
    $db->pdo->rollBack();
    echo $e->getMessage();
    die();
}
$db = null;
