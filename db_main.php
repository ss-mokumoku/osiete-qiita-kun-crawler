<?php

/**
 * Databaseの基本クラス.
 */
class Database
{
    public $pdo; // PDOのインスタンスを保持する
    public $host = 'localhost';
    private $user = 'root';
    private $pass = 'systemsoftkensyu2018';
    private $databaseName = 'qiita_kadai';

    /**
     * コンストラクタ
     * インスタンス生成時に、最初に呼ばれるメソッド.
     */
    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=qiita_kadai', 'root', 'systemsoftkensyu2018');
//        $this->pdo = new PDO('mysql:host='.$host.';dbname='.$databaseName, $user, $pass);
        // MySQLに接続する
    }

    //    トランザクション
    //    $pdo -> beginTransaction();

    /**
     * select文を実行する汎用SQL.
     *
     * @param string $selectSQL select文
     * @param array  $params    select文に入れる汎用パラメータ。キーは名前付きプリペアドステートメント（例： ':name'）
     * @param mixed  $sql
     *
     * @throws PDOException
     *
     * @return select文の結果（連想配列）
     */
    public function select($sql, array $params = [])
    {
        $sth = $this->pdo->prepare($sql);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * insert文を実行する.
     *
     * @param string $sql    insert文
     * @param array  $params select文に入れる汎用パラメータ。キーは名前付きプリペアドステートメント（例： ':name'）
     * @param mixed  $data2
     *
     * @throws PDOException
     */
    public function insert_author($data2)
    {
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
        $json_count = count($data2['contents']);
        $sth = $this->pdo->prepare($sql);
        for ($i = 0; $i < $json_count; ++$i) {
            $params = [':permanent_id' => $data2['contents'][$i]['permanent_id'],
                ':user_id' => $data2['contents'][$i]['user_id'],
                ':name' => $data2['contents'][$i]['author'],
                ':profile_image_url' => $data2['contents'][$i]['profile_image_url'],
                ':description' => $data2['contents'][$i]['description'],
                ':location' => $data2['contents'][$i]['location'],
                ':organization' => $data2['contents'][$i]['organization'],
                ':followees_count' => $data2['contents'][$i]['followees_count'],
                ':followers_count' => $data2['contents'][$i]['followers_count'],
                ':items_count' => $data2['contents'][$i]['items_count'],
                ':github_login_name' => $data2['contents'][$i]['github_login_name'],
                ':linkedin_id' => $data2['contents'][$i]['linkedin_id'],
                ':facebook_id' => $data2['contents'][$i]['facebook_id'],
                ':twitter_screen_name' => $data2['contents'][$i]['twitter_screen_name'],
                ':website_url' => $data2['contents'][$i]['website_url'],
            ];

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

    public function insert_article($data2)
    {
        $sql = '
    INSERT INTO articles_tbl(post_id, url, title,
    body, permanent_id, likes_count,
    private, page_views_count,
    comments_count, reactions_count,
    coediting, created_at, updated_at
    )
    VALUES(:post_id, :url, :title,
    :body, :permanent_id, :likes_count,
    :private, :page_views_count,
    :comments_count, :reactions_count,
    :coediting, :created_at, :updated_at
    )';
        /*
        $sql = "INSERT INTO articles_tbl(post_id, url, title,
        body, permanent_id, likes_count,
        private, page_views_count,
        comments_count, reactions_count,
        coediting, created_at, updated_at
        )
        VALUES(:post_id, :url, :title,
        :body, :permanent_id, :likes_count,
        :private, :page_views_count,
        :comments_count, :reactions_count,
        :coediting, :created_at, :updated_at
        )";
        */
        $json_count = count($data2['contents']);
        for ($i = 0; $i < $json_count; ++$i) {
            $params = [':post_id' => $data2['contents'][$i]['item_id'],
                ':url' => $data2['contents'][$i]['link'],
                ':title' => $data2['contents'][$i]['title'],
                ':body' => $data2['contents'][$i]['body'],
                ':permanent_id' => $data2['contents'][$i]['permanent_id'],
                ':likes_count' => $data2['contents'][$i]['likes_count'],
                ':private' => $data2['contents'][$i]['private'],
                ':page_views_count' => $data2['contents'][$i]['page_views_count'],
                ':comments_count' => $data2['contents'][$i]['comments_count'],
                ':reactions_count' => $data2['contents'][$i]['reactions_count'],
                ':coediting' => $data2['contents'][$i]['coediting'],

                ':created_at' => $data2['contents'][$i]['published']->format(DateTime::ATOM),
                ':updated_at' => $data2['contents'][$i]['updated']->format(DateTime::ATOM),
                //               ':record_created_at'=>$data2['contents'][$i]['record_created_at']
            ];
            $sth = $this->pdo->prepare($sql, $params);
            $sth->bindParam(':post_id', $params[':post_id'], PDO::PARAM_STR);
            $sth->bindParam(':url', $params[':url'], PDO::PARAM_STR);
            $sth->bindParam(':title', $params[':title'], PDO::PARAM_STR);
            $sth->bindParam(':body', $params[':body'], PDO::PARAM_STR);
            $sth->bindParam(':permanent_id', $params[':permanent_id'], PDO::PARAM_STR);
            $sth->bindParam(':likes_count', $params[':likes_count'], PDO::PARAM_INT);
            $sth->bindParam(':private', $params[':private'], PDO::PARAM_STR);
            $sth->bindParam(':page_views_count', $params[':page_views_count'], PDO::PARAM_INT);
            $sth->bindParam(':comments_count', $params[':comments_count'], PDO::PARAM_INT);
            $sth->bindParam(':reactions_count', $params[':reactions_count'], PDO::PARAM_INT);
            $sth->bindParam(':coediting', $params[':coediting'], PDO::PARAM_STR);
            $sth->bindParam(':created_at', $params[':created_at'], PDO::PARAM_STR);
            $sth->bindParam(':updated_at', $params[':updated_at'], PDO::PARAM_STR);
            //        $sth->bindParam(':record_creared_at',$params[":record_created_at"],PDO::PARAM_INT);
            $sth->execute();
        }
    }

    public function insert_rss_history($data2)
    {
        $sql = 'INSERT INTO rss_history(post_id) VALUES(:post_id)';
        $json_count = count($data2['contents']);
        $sth = $this->pdo->prepare($sql);
        for ($i = 0; $i < $json_count; ++$i) {
            $params = [':post_id' => $data2['contents'][$i]['item_id'],
            ];
            $sth->bindParam(':post_id', $params[':post_id'], PDO::PARAM_STR);
            $sth->execute();
        }
    }

    public function tags($data2)
    {
        //タグがすでにタグ管理テーブルに登録されていたらIDを返す
        $sql = 'select tag_id from tags_tbl where tag_name=(:tags)';
        $sql2 = 'insert into tags_tbl(tag_name) values(:tags)';
        $sql3 = 'insert into qiita_page_tags(post_id,tag_id)
                    values(:post_id,:tag_id)';
        $sth = $this->pdo->prepare($sql);
        $sth2 = $this->pdo->prepare($sql2);
        $sth3 = $this->pdo->prepare($sql3);
        $json_count = count($data2['contents']);
        for ($i = 0; $i < $json_count; ++$i) {
            $tags_count = count($data2['contents'][$i]['tags']);
            for ($j = 0; $j < $tags_count; ++$j) {
                $params = [':tags' => $data2['contents'][$i]['tags'][$j],
                    ':post_id' => $data2['contents'][$i]['item_id'],
                ];
                $sth->bindParam(':tags', $params[':tags'], PDO::PARAM_STR);
                $sth->execute();
                $tagid = $sth->fetchAll(PDO::FETCH_ASSOC);

                //タグ管理テーブルに登録されていなければ登録する
                if (empty($tagid)) {
                    $sth2->bindParam(':tags', $params[':tags'], PDO::PARAM_STR);
                    $sth2->execute();
                    //IDを記事のタグ一覧に登録する
                    $d = $this->pdo->lastInsertID();
                } else {
                    $d = $tagid[0]['tag_id'];
                }

                //        print($d);

                $sth3->bindParam(':post_id', $params[':post_id'], PDO::PARAM_STR);
                $sth3->bindParam(':tag_id', $d, PDO::PARAM_STR);
                $sth3->execute();
            }
        }
    }

    public function insert_crawl_history()
    {
        $date = new DateTime();
        $date = (string) $date->format('Y-m-d H:i:s');
        $sql = 'INSERT INTO crawl_history(rss_updated) VALUES (:created)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':created', $date, PDO::PARAM_STR);
        $stmt->execute();
    }
}
