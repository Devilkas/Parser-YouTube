<?php
header("Content-Type: application/json");
if (isset($_GET['url']) && isset($_GET['list'])) {
    $url = $_GET['url'];
    $list = $_GET['list'];
    $fullUrl = $url . '&list=' . $list;
    $page = file_get_contents($fullUrl);
    $allArr = array();
    $countImg = 0;
    $doc = new DOMDocument();
    $doc->loadHTML($page);
    $xpath = new DOMXpath($doc);
    $expression = '//ol/li/a';
    $nodes = $xpath->query($expression);
    $nodeTitle = $xpath->query('//h3[@class="playlist-title"]');
    $title = utf8_decode($nodeTitle->item(0)->nodeValue);
    $arr = array();
    $countVideo = 0;
    $arr['channel_title'] = $title;
    foreach ($nodes as $node) {
        $expression = './div/h4';
        $newVideoTitle = utf8_decode($xpath->query($expression, $node)->item(0)->nodeValue);
        $arr['playlist_youtube_video'][$countVideo]['title'] = $newVideoTitle;
        $countImg++;
        $expression = './span/span/span/img';
        if ($countImg <= 7) {
            $imgSrc = $xpath->query($expression, $node)->item(0)->getAttribute('src');
            $newImgSrc = explode('?', $imgSrc);
            $newImgSrc = $newImgSrc[0];
            $arr['playlist_youtube_video'][$countVideo]['img'] = $newImgSrc;
        } else {
            $imgThumb = $xpath->query($expression, $node)->item(0)->getAttribute('data-thumb');
            $newImgThumb = explode('?', $imgThumb);
            $newImgThumb = $newImgThumb[0];
            $arr['playlist_youtube_video'][$countVideo]['img'] = $newImgThumb;
        }
        $videoUrl = 'https://www.youtube.com' . $node->getAttribute('href');
        $videoUrl2 = $node->getAttribute('href');
        $newVideoUrl = explode('=', $videoUrl2);
        $newVideoUrl = $newVideoUrl[1];
        $idVideoUrl = explode('&', $newVideoUrl);
        $idVideoUrl = $idVideoUrl[0];
        $arr['playlist_youtube_video'][$countVideo]['url'] = $videoUrl;
        $arr['playlist_youtube_video'][$countVideo]['id_video'] = $idVideoUrl;
        $countVideo++;
    }
    $jsonData = json_encode($arr);
    echo $jsonData;
} else {
    $arr['access'] = 'false';
    $jsonData = json_encode($arr);
    echo $jsonData;
}
