<?php
/**	@file
 *  @brief RSSの情報とAPIの情報をつなぎ合わせて連想配列をつくる
 *
 *  @author SystemSoft Arita-takahiro
 *  @date 2018/05/21 新規作成
 *
 * @param mixed $rss_data
 */
require_once 'extract_api.php';
require_once 'rss/extract_qiita_feed.php';

function integrate_rss_api($rss_data)
{
    //RSSの配列をうけとってitem_idだけ抜き取る
    //その後item_idを利用してAPIの情報（配列）を取得する
    //RSSの配列とAPIの情報をくっつけて、それを連想配列にしてリターンする
    for ($i = 0; $i < count($rss_data['contents']); ++$i) {
        $item_id = $rss_data['contents'][$i]['item_id'];
        $API = extract_api($item_id);
        //RSSの配列にAPIの要素を追加する
        $rss_data['contents'][$i]['permanent_id'] = $API['permanent_id'];
        $rss_data['contents'][$i]['user_id'] = $API['user_id'];
        $rss_data['contents'][$i]['profile_image_url'] = $API['profile_image_url'];
        $rss_data['contents'][$i]['description'] = $API['description'];
        $rss_data['contents'][$i]['location'] = $API['location'];
        $rss_data['contents'][$i]['organization'] = $API['organization'];
        $rss_data['contents'][$i]['followees_count'] = $API['followees_count'];
        $rss_data['contents'][$i]['followers_count'] = $API['followers_count'];
        $rss_data['contents'][$i]['items_count'] = $API['items_count'];
        $rss_data['contents'][$i]['github_login_name'] = $API['github_login_name'];
        $rss_data['contents'][$i]['linkedin_id'] = $API['linkedin_id'];
        $rss_data['contents'][$i]['facebook_id'] = $API['facebook_id'];
        $rss_data['contents'][$i]['twitter_screen_name'] = $API['twitter_screen_name'];
        $rss_data['contents'][$i]['website_url'] = $API['website_url'];
        $rss_data['contents'][$i]['body'] = $API['body'];
        $rss_data['contents'][$i]['private'] = $API['private'];
        $rss_data['contents'][$i]['page_views_count'] = $API['page_views_count'];
        $rss_data['contents'][$i]['likes_count'] = $API['likes_count'];
        $rss_data['contents'][$i]['comments_count'] = $API['comments_count'];
        $rss_data['contents'][$i]['reactions_count'] = $API['reactions_count'];
        $rss_data['contents'][$i]['coediting'] = $API['coediting'];
        $rss_data['contents'][$i]['tags'] = $API['tags'];
        //  5秒間隔でループを行うことも可能      sleep(5);
    }

    return $rss_data;
}
/*
$API = qiita_json($item_id_count[0]);
//要素を追加する
$data['contents']['id'] = $API['id'];
$data['contents']['likes_count'] = $API['like_count'];
$data['contents']['user'] = $API['user'];
$data['contents']['tags'] = $API['tags'];
print_r($data);
}
*/
