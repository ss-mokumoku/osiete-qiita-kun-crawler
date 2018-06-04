<?php
function qiita_json($item) {

    // APIをコール
    $c = curl_init('https://qiita.com/api/v2/items/'.$item);
    if(!empty(getenv('QIITA_ACCESS_TOKEN'))) { // 環境変数にアクセストークンが指定されていたらアクセストークンを有効にする
        $token = getenv('QIITA_ACCESS_TOKEN');
        echo $token .PHP_EOL;
        curl_setopt($c, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.$token,
            'Content-Type: application/json'
        ]);
    }
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($c);
    curl_close($c);
    //文字化けをしないようにする
    $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
    //連想配列にする
    $arr = json_decode($json,true);
    $json_count = count($arr['tags']);
    $tags_count = array();


    for($i=$json_count-1;$i>=0;$i--){
        $tag = $arr['tags'][$i]['name'];
        $tags_count[] = $tag;
    }

    $contents = array(
        //authors_tblの項目
        "permanent_id"        => $arr['user']['permanent_id'],
        "user_id"             => $arr['user']['id'],
        "profile_image_url"   => $arr['user']['profile_image_url'],
        "description"         => $arr['user']['description'],
        "location"            => $arr['user']['location'],
        "organization"        => $arr['user']['organization'],
        "followees_count"     => $arr['user']['followees_count'],
        "followers_count"     => $arr['user']['followers_count'],
        "items_count"         => $arr['user']['items_count'],
        "github_login_name"   => $arr['user']['github_login_name'],
        "linkedin_id"         => $arr['user']['linkedin_id'],
        "facebook_id"         => $arr['user']['facebook_id'],
        "twitter_screen_name" => $arr['user']['twitter_screen_name'],
        "website_url"         => $arr['user']['website_url'],

        "post_id"             => $arr['id'],
        "body"                => $arr['body'],
        "private"             => $arr['private'],
        "page_views_count"    => $arr['page_views_count'],
        "likes_count"         => $arr['likes_count'],
        "comments_count"      => $arr['comments_count'],
        "reactions_count"     => $arr['reactions_count'],
        "coediting"           => $arr['coediting'],
        "tags"                => $tags_count,
    );
    return $contents;
}





/*
$json = file_get_contents("qiita_API.json");
//文字化けをしないようにする
$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
//連想配列にする
$arr = json_decode($json,true);
echo $arr['id'];
echo $arr['likes_count'];
echo $arr['user']['profile_image_url'];
*/
?>
