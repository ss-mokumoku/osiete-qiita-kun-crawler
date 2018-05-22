<?php
require_once("qiita_json.php");
require_once("extract_qiita_feed.php");

function qiita_json_api($data){
//配列をうけとってitem_idだけ抜き取る

        $json_count = count($data['contents']);
        for($i=$json_count-1;$i>=0;$i--){

        $item_id = $data['contents'][$i]['item_id'];
        $API = qiita_json($item_id);
         //要素を追加する
        $data['contents'][$i]['permanent_id'] = $API['permanent_id'];
        $data['contents'][$i]['user_id'] = $API['user_id'];
        $data['contents'][$i]['profile_image_url'] = $API['profile_image_url'];
        $data['contents'][$i]['description'] = $API['description'];
        $data['contents'][$i]['location'] = $API['location'];
        $data['contents'][$i]['organization'] = $API['organization'];
        $data['contents'][$i]['followees_count'] = $API['followees_count'];
        $data['contents'][$i]['followers_count'] = $API['followers_count'];
        $data['contents'][$i]['items_count'] = $API['items_count'];
        $data['contents'][$i]['github_login_name'] = $API['github_login_name'];
        $data['contents'][$i]['linkedin_id'] = $API['linkedin_id'];
        $data['contents'][$i]['facebook_id'] = $API['facebook_id'];
        $data['contents'][$i]['twitter_screen_name'] = $API['twitter_screen_name'];
        $data['contents'][$i]['website_url'] = $API['website_url'];
//        $data['contents'][$i]['image_monthly_upload_limit'] = $API['image_monthly_upload_limit'];
//        $data['contents'][$i]['image_monthly_upload_remaining'] = $API['image_monthly_upload_remaining'];
//        $data['contents'][$i]['team_only'] = $API['team_only'];
        $data['contents'][$i]['body'] = $API['body'];
        $data['contents'][$i]['private'] = $API['private'];
        $data['contents'][$i]['page_views_count'] = $API['page_views_count'];
        $data['contents'][$i]['likes_count'] = $API['likes_count'];
        $data['contents'][$i]['comments_count'] = $API['comments_count'];
        $data['contents'][$i]['reactions_count'] = $API['reactions_count'];
        $data['contents'][$i]['coediting'] = $API['coediting'];
        $data['contents'][$i]['tags'] = $API['tags'];
//  5      sleep(5);
    }

return $data;
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
?>
