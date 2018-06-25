<?php

/**	@file
 *  @brief Databaseの基本クラス
 *
 *  @author SystemSoft Arita-takahiro
 *  @date 2018/05/21
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
        // $this->pdo = new PDO('mysql:host='.$host.';dbname='.$databaseName, $user, $pass);
        // MySQLに接続する
        $this->pdo = new PDO('mysql:host=localhost;dbname=qiita_kadai', 'root', 'systemsoftkensyu2018');
    }

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
     * @param string $sql      insert文
     * @param array  $params   select文に入れる汎用パラメータ。キーは名前付きプリペアドステートメント（例： ':name'）
     * @param mixed  $data2
     * @param mixed  $api_data
     *
     * @throws PDOException
     */
    public function insert_author($api_data)
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
        $sth = $this->pdo->prepare($sql);
        //nameなどの記事の作者の情報をSQL文にバインドして、authors_tblにバインドする
        for ($i = 0; $i < count($api_data['contents']); ++$i) {
            $params = [':permanent_id' => $api_data['contents'][$i]['permanent_id'],
                ':user_id' => $api_data['contents'][$i]['user_id'],
                ':name' => $api_data['contents'][$i]['author'],
                ':profile_image_url' => $api_data['contents'][$i]['profile_image_url'],
                ':description' => $api_data['contents'][$i]['description'],
                ':location' => $api_data['contents'][$i]['location'],
                ':organization' => $api_data['contents'][$i]['organization'],
                ':followees_count' => $api_data['contents'][$i]['followees_count'],
                ':followers_count' => $api_data['contents'][$i]['followers_count'],
                ':items_count' => $api_data['contents'][$i]['items_count'],
                ':github_login_name' => $api_data['contents'][$i]['github_login_name'],
                ':linkedin_id' => $api_data['contents'][$i]['linkedin_id'],
                ':facebook_id' => $api_data['contents'][$i]['facebook_id'],
                ':twitter_screen_name' => $api_data['contents'][$i]['twitter_screen_name'],
                ':website_url' => $api_data['contents'][$i]['website_url'],
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

    /**
     *  @brief APIの情報をarticles_tblに格納
     *  @date 2018/05/21
     *  @note
     *
     *  @param array $api_data
     */
    public function insert_article($api_data)
    {
        $sql = 'INSERT INTO articles_tbl(post_id, url, title,
        body, permanent_id, likes_count,
        private, page_views_count,
        comments_count, reactions_count,
        coediting, created_at, updated_at
        )
        VALUES(:post_id, :url, :title,
        :body, :permanent_id, :likes_count,
        :private, :page_views_count,
        :comments_count, :reactions_count,
        :coediting, :created_at, :updated_at)
        ON DUPLICATE KEY UPDATE
        likes_count = :likes_count';
        //nameなどの記事情報をSQL文にバインドして、articles_tblにバインドする
        for ($i = 0; $i < count($api_data['contents']); ++$i) {
            $params = [':post_id' => $api_data['contents'][$i]['item_id'],
                ':url' => $api_data['contents'][$i]['link'],
                ':title' => $api_data['contents'][$i]['title'],
                ':body' => $api_data['contents'][$i]['body'],
                ':permanent_id' => $api_data['contents'][$i]['permanent_id'],
                ':likes_count' => $api_data['contents'][$i]['likes_count'],
                ':private' => $api_data['contents'][$i]['private'],
                ':page_views_count' => $api_data['contents'][$i]['page_views_count'],
                ':comments_count' => $api_data['contents'][$i]['comments_count'],
                ':reactions_count' => $api_data['contents'][$i]['reactions_count'],
                ':coediting' => $api_data['contents'][$i]['coediting'],

                ':created_at' => $api_data['contents'][$i]['published']->format(DateTime::ATOM),
                ':updated_at' => $api_data['contents'][$i]['updated']->format(DateTime::ATOM),
                //               ':record_created_at'=>$data2['contents'][$i]['record_created_at']
            ];
            $sth = $this->pdo->prepare($sql);
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

    /**
     *  @brief APIの情報をarticles_tblに格納
     *  @date 2018/05/21
     *  @note
     *
     * @param mixed $api_data
     */
    public function insert_rss_history($api_data)
    {
        $sql = 'SELECT crawl_id FROM crawl_history
        ORDER BY record_crated_at DESC
        LIMIT 1';

//        $sql = 'INSERT INTO rss_history(post_id) VALUES(:post_id)
//        ';
        $sth = $this->pdo->prepare($sql);
        $sth->execute();
        $crawl = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sql2 = 'INSERT INTO rss_history(post_id, crawl_history_id) VALUES(:post_id, :crawl_history_id)';
        $sth2 = $this->pdo->prepare($sql2);
        for ($i = 0; $i < count($api_data['contents']); ++$i) {
            $params = [':post_id' => $api_data['contents'][$i]['item_id'],
            ];
            $sth2->bindParam(':post_id', $params[':post_id'], PDO::PARAM_STR);
            $sth2->bindParam(':crawl_history_id', $crawl[0]['crawl_id'], PDO::PARAM_INT);
            $sth2->execute();
        }
    }

    /**
     *  @brief APIの情報をtags_tblに格納
     *  @date 2018/05/21
     *  @note
     *
     * @param mixed $api_data
     */
    public function insert_tags($api_data)
    {
        //タグがすでにタグ管理テーブルに登録されていたらIDを返す
        $sql = 'select tag_id from tags_tbl where tag_name=(:tags)';
        $sql2 = 'insert into tags_tbl(tag_name) values(:tags)';
        $sql3 = 'insert into qiita_page_tags(post_id,tag_id)
                    values(:post_id,:tag_id)';
        $sth = $this->pdo->prepare($sql);
        $sth2 = $this->pdo->prepare($sql2);
        $sth3 = $this->pdo->prepare($sql3);
        //人気記事20件のタグ情報をtags_tblに登録する
        //その後に、qiita_page_tagsにtag_idを登録する
        for ($i = 0; $i < count($api_data['contents']); ++$i) {
            for ($j = 0; $j < count($api_data['contents'][$i]['tags']); ++$j) {
                $params = [':tags' => $api_data['contents'][$i]['tags'][$j],
                    ':post_id' => $api_data['contents'][$i]['item_id'],
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

    /**
     *  @brief クロールした日時をcrawl_historyに登録する
     *  @date 2018/05/21
     *  @note
     *
     * @param mixed $rss_data
     */
    public function insert_crawl_history($rss_data)
    {
        $sql = 'INSERT INTO crawl_history(rss_updated) VALUES (:created)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':created', $rss_data['updated'], PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
     *  @brief 人気記事20件のいいね数をlikescount_historyに登録する
     *  @date 2018/05/21
     *  @note
     */
    public function insert_likescount_history()
    {
        $sql = 'INSERT INTO likes_history(likes_count, rss_history_id)
                SELECT articles_tbl.likes_count ,rss_history.id
                FROM articles_tbl, rss_history
                WHERE articles_tbl.post_id = rss_history.post_id
                ORDER BY record_crated_at DESC
                LIMIT 20';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

    /**
     *  @brief 人気記事20件の閲覧数をpage_views_count_historyに登録する
     *  @date 2018/05/21
     *  @note
     */
    public function insert_page_views_count_history()
    {
        $sql = 'INSERT INTO page_views_count_history(page_views_count, rss_history_id)
                SELECT articles_tbl.page_views_count ,rss_history.id
                FROM articles_tbl, rss_history
                WHERE articles_tbl.post_id = rss_history.post_id
                ORDER BY record_crated_at DESC
                LIMIT 20';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

    public function get_rss_updated()
    {
        $sql = 'SELECT rss_updated FROM crawl_history
        ORDER BY record_crated_at DESC
        LIMIT 1';
        $sth = $this->pdo->prepare($sql);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}
