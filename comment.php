<?php
/**	@file
 *  @brief APIからコメント情報を抽出する
 *
 *  @author SystemSoft Arita-takahiro
 *  @date 2018/05/21
 */

//データベースファイルの呼び出し
require 'db_main.php';
//インスタンス作成
$db = new Database();

$sql = 'SELECT post_id FROM articles_tbl';

$sth = $db->pdo->prepare($sql);
$sth->execute();
$res = $sth->fetchAll(PDO::FETCH_ASSOC);

$comment_arr = [];
$comment_count = count($res);

//  APIをたたいて、コメントの情報を取得して連想配列で返す
for ($i = 0; $i < $comment_count; ++$i) {
    //for ($i = 2; $i < 3; ++$i) { APIを複数回たたかないようにするためのコード
    $c = curl_init('https://qiita.com/api/v2/items/'.$res[$i]['post_id'].'/comments');
    //$c = curl_init('http://10.20.30.99/qiita/'.$item.'.json');
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($c);
    //文字化けをしないようにする
    $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
    //連想配列にする
    $arr = json_decode($json, true);
    $arr['post_id'] = $res[$i]['post_id'];
    $comment_arr[] = $arr;
}

//コメントしたユーザーの情報をauthors_tblに格納します
comment_author($comment_arr);
//コメントの情報をcomment_tblに格納します
comment_insert($comment_arr);

/**
 *  @brief コメントの情報をcomment_tblに格納する
 *  @date 2018/05/21
 *  @note
 *
 * @param mixed $comment_arr コメント情報の連想配列
 */
function comment_insert($comment_arr)
{
    $db = new Database();
    $comment_sql = 'insert into comment_tbl(comment_id, updated_at, body, rendered_body, permanent_id, post_id)
        values(:comment_id, :updated_at, :body, :rendered_body, :permanent_id, :post_id)';
    $sth = $db->pdo->prepare($comment_sql);
    $kiji_number = count($comment_arr);
    for ($j = 0; $j < $kiji_number; ++$j) {
        $comment_number = count($comment_arr[$j]);

        for ($k = 0; $k < $comment_number - 1; ++$k) {
            $params = [':comment_id' => $comment_arr[$j][$k]['id'],
                ':updated_at' => $comment_arr[$j][$k]['updated_at'],
                ':body' => $comment_arr[$j][$k]['body'],
                ':rendered_body' => $comment_arr[$j][$k]['rendered_body'],
                ':permanent_id' => $comment_arr[$j][$k]['user']['permanent_id'],
                ':post_id' => $comment_arr[$j]['post_id'],
            ];

            $sth->bindParam(':comment_id', $params[':comment_id'], PDO::PARAM_STR);
            $sth->bindParam(':updated_at', $params[':updated_at'], PDO::PARAM_STR);
            $sth->bindParam(':body', $params[':body'], PDO::PARAM_STR);
            $sth->bindParam(':rendered_body', $params[':rendered_body'], PDO::PARAM_STR);
            $sth->bindParam(':permanent_id', $params[':permanent_id'], PDO::PARAM_STR);
            $sth->bindParam(':post_id', $params[':post_id'], PDO::PARAM_STR);
            $sth->execute();
        }
    }
}

/**
 *  @brief コメントしたユーザーの情報をauthors_tblに格納する関数
 *  @date 2018/05/21
 *  @note
 *
 * @param mixed $comment_arr コメント情報の連想配列
 */
function comment_author($comment_arr)
{
    $db = new Database();
    $sql = 'INSERT INTO authors_tbl(permanent_id, user_id,
        name, profile_image_url,
        description,location,
        organization, followees_count,
        followers_count, items_count,
        github_login_name, linkedin_id,
        facebook_id, twitter_screen_name,
        website_url
    )
    VALUES(:permanent_id, :user_id,
        :name, :profile_image_url,
        :description, :location,
        :organization, :followees_count,
        :followers_count, :items_count,
        :github_login_name, :linkedin_id,
        :facebook_id, :twitter_screen_name,
        :website_url
    )';

    $comment_count2 = count($comment_arr);

    for ($j = 0; $j < $comment_count2; ++$j) {
        $comment_count3 = count($comment_arr[$j]);

        for ($k = 0; $k < $comment_count3 - 1; ++$k) {
            $params = [':permanent_id' => $comment_arr[$j][$k]['user']['permanent_id'],
                ':user_id' => $comment_arr[$j][$k]['user']['id'],
                ':name' => $comment_arr[$j][$k]['user']['name'],
                ':profile_image_url' => $comment_arr[$j][$k]['user']['profile_image_url'],
                ':description' => $comment_arr[$j][$k]['user']['description'],
                ':location' => $comment_arr[$j][$k]['user']['location'],
                ':organization' => $comment_arr[$j][$k]['user']['organization'],
                ':followees_count' => $comment_arr[$j][$k]['user']['followees_count'],
                ':followers_count' => $comment_arr[$j][$k]['user']['followers_count'],
                ':items_count' => $comment_arr[$j][$k]['user']['items_count'],
                ':github_login_name' => $comment_arr[$j][$k]['user']['github_login_name'],
                ':linkedin_id' => $comment_arr[$j][$k]['user']['linkedin_id'],
                ':facebook_id' => $comment_arr[$j][$k]['user']['facebook_id'],
                ':twitter_screen_name' => $comment_arr[$j][$k]['user']['twitter_screen_name'],
                ':website_url' => $comment_arr[$j][$k]['user']['website_url'],
            ];
            $sth = $db->pdo->prepare($sql);
            $sth->bindParam(':permanent_id', $params[':permanent_id'], PDO::PARAM_STR);
            $sth->bindParam(':user_id', $params[':user_id'], PDO::PARAM_STR);
            $sth->bindParam(':name', $params[':name'], PDO::PARAM_STR);
            $sth->bindParam(':profile_image_url', $params[':profile_image_url'], PDO::PARAM_STR);
            $sth->bindParam(':description', $params[':description'], PDO::PARAM_STR);
            $sth->bindParam(':location', $params[':location'], PDO::PARAM_STR);
            $sth->bindParam(':organization', $params[':organization'], PDO::PARAM_STR);
            $sth->bindParam(':followees_count', $params[':followees_count'], PDO::PARAM_INT);
            $sth->bindParam(':followers_count', $params[':followers_count'], PDO::PARAM_INT);
            $sth->bindParam(':items_count', $params[':items_count'], PDO::PARAM_INT);
            $sth->bindParam(':github_login_name', $params[':github_login_name'], PDO::PARAM_STR);
            $sth->bindParam(':linkedin_id', $params[':linkedin_id'], PDO::PARAM_STR);
            $sth->bindParam(':facebook_id', $params[':facebook_id'], PDO::PARAM_STR);
            $sth->bindParam(':twitter_screen_name', $params[':twitter_screen_name'], PDO::PARAM_STR);
            $sth->bindParam(':website_url', $params[':website_url'], PDO::PARAM_STR);

            $sth->execute();
        }
    }
}
