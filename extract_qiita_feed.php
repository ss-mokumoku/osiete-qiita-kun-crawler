<?php

require_once("print.php");
require_once("qiita_json.php");

function extract_qiita_feed($file){

    $xmlstr = file_get_contents($file);
    $feed = new SimpleXMLElement($xmlstr);

    $feedtitle   =(string)$feed->title;
    $feedupdated =(string)$feed->updated;
    $contents = array();

    foreach($feed->entry as $entry){
        $published = DateTime::createFromFormat(DateTime::ATOM,(string)$entry->published);
        $updated = DateTime::createFromFormat(DateTime::ATOM,(string)$entry->updated);
        $link = (string)$entry->link['href'];
        $link = urldecode($link);
        $url = parse_url($link);
        $item = basename($url['path']);
//        $api = qiita_json($item);
        $entrydata = array(
            "title"     => (string)$entry->title,
            "author"    => (string)$entry->author->name,
            "published" => $published,
            "updated"   => $updated,
            "link"      => $link,
            "item_id"   => $item,
//            "post_id"   => (string)$entry->id
//            "API"      => $api
        );
        
        $contents[] = $entrydata;

    }
    return array("title" => $feedtitle,"updated" => $feedupdated,"contents" => $contents);
}

/**
 * データを抽出し、画面に表示する
 */
function extract_and_print($filepath) {
    $data = extract_qiita_feed($filepath);
    myprint($data);
}


