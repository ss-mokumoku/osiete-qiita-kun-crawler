<?php

function myprint($data) {
    echo "==========================================\n";
    echo "Qiita Feed 出力結果\n";
    echo "==========================================\n";
    print_r($data);
    echo "\n\nFeed数: " . count($data["contents"]) . "\n";
}

