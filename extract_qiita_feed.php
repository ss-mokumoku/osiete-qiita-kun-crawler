<?php

//Qiitaの人気記事のRSSを取得するコードです

function extract_qiita_feed($file)
{
    $xmlstr = file_get_contents($file);
    $feed = new SimpleXMLElement($xmlstr);

    $feedtitle = (string) $feed->title;
    $feedupdated = (string) $feed->updated;
    $contents = [];

    //タイトル、著者、投稿日時、更新日時、リンク、item_idをRSSから抽出します
    foreach ($feed->entry as $entry) {
        $published = DateTime::createFromFormat(DateTime::ATOM, (string) $entry->published);
        $updated = DateTime::createFromFormat(DateTime::ATOM, (string) $entry->updated);
        $link = (string) $entry->link['href'];
        $link = urldecode($link);
        $url = parse_url($link);
        $item = basename($url['path']);

        $entrydata = [
            'title' => (string) $entry->title,
            'author' => (string) $entry->author->name,
            'published' => $published,
            'updated' => $updated,
            'link' => $link,
            'item_id' => $item,
        ];

        $contents[] = $entrydata;
    }
    //RSSのタイトル「Qiita - 人気の投稿」とRSSの更新日時と記事ごとのデータをリターンします
    return ['title' => $feedtitle, 'updated' => $feedupdated, 'contents' => $contents];
}

/**
 * データを抽出し、画面に表示する.
 *
 * @param mixed $filepath
 */
function extract_and_print($filepath)
{
    $data = extract_qiita_feed($filepath);
    myprint($data);
}
