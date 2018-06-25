<?php
/**	@file
 *  @brief クローラーのmainファイル
 *
 *  @author SystemSoft Arita-takahiro
 *  @date 2018/05/31 最終更新
 */
date_default_timezone_set('Asia/Tokyo');
//RSSの情報を抽出して連想配列にするファイル
require_once 'rss/extract_qiita_feed.php';
//APIの情報を抽出して連想配列にするファイル
require 'api/extract_api.php';
//RSSの連想配列とAPIの連想配列を組み合わせる
require_once 'api/integrate.php';
//PDOやINSERTなどデータベース関連の関数があるファイル
require_once 'database/database.php';

//Qiitaの人気記事を20件RSSから取得します
$rss_data = extract_qiita_feed('https://qiita.com/popular-items/feed.atom');
//echo $rss_data['updated'];

$db = new Database();
//crawl_historyテーブルからの更新履歴を取得する
$rss_updated = $db->get_rss_updated();

//RSSの更新履歴とcrawl_historyテーブルの更新履歴を比較
//もしすでに、登録したRSSの情報ならば登録しない
//新しい更新履歴のRSSならば登録する
//if (date('Y年n月j日g時i分s秒', strtotime($rss_data['updated'])) === date('Y年n月j日g時i分s秒', strtotime($rss_updated[0]['rss_updated']))) {
//    echo 'すでに登録済みです。';
//} else {
    //$db = new Database();
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

//    echo '登録しました';
//}
