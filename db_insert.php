<?php

require('db_main.php');
function insert_author($data2){
    $db = new Database();
    $sql = "INSERT INTO authors_tbl(permanent_id, user_id,
                                    name, profile_image_url
                                   )
            VALUES(:permanent_id, :user_id, :name, :profile_image_url)";
    $json_count = count($data2['contents']);
    for($i=$json_count-1;$i>=0;$i--){
    $params = [':permanent_id'=>$data2['contents'][$i]['permanent_id'],
               ':user_id'=>$data2['contents'][$i]['id'],
               ':name'=>$data2['contents'][$i]['author'],
               ':profile_image_url'=>$data2['contents'][$i]['image']];
    $res = $db->insert($sql,$params);
//    print_r($sql);
//    print_r($params);
//    print_r($res);
    }
}
function insert_article($data2){
    $db = new Database();
    $sql = "INSERT INTO articles_tbl(post_id, url, title,
                                    permanent_id, likes_count,
                                    created_at, updated_at
                                   )
            VALUES(:post_id, :url, :title,
                   :permanent_id, :likes_count,
                   :created_at, :updated_at                  
                  )";
    $json_count = count($data2['contents']);
    for($i=$json_count-1;$i>=0;$i--){
    $params = [':post_id'=>$data2['contents'][$i]['post_id'],
               ':url'=>$data2['contents'][$i]['url'],
               ':title'=>$data2['contents'][$i]['title'],
               ':permanent_id'=>$data2['contents'][$i]['permanent_id'],
               ':likes_count'=>$data2['contents'][$i]['likes_count'],
               ':created_at'=>$data2['contents'][$i]['created_at'],
               ':updated_at'=>$data2['contents'][$i]['updated_id']
               ];
    $res = $db->insert($sql,$params);
//    print_r($sql);
//    print_r($params);
//    print_r($res);
    }
}

function insert_rss_history($data2){
    $db = new Database();
    $sql = "INSERT INTO rss_history(post_id
                                   )
            VALUES(:post_id                 
                  )";
    $json_count = count($data2['contents']);
    for($i=$json_count-1;$i>=0;$i--){
    $params = [':post_id'=>$data2['contents'][$i]['post_id']
              ];
    $res = $db->insert($sql,$params);
//    print_r($sql);
//    print_r($params);
//    print_r($res);
    }
}




























?>    
