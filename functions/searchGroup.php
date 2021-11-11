<?php

// Request voi oookie
function requestWithCookie($url, $cookie)
{
    $ch = curl_init();

    CURL_SETOPT_ARRAY($ch, [
        CURLOPT_URL => $url,
        //CURLOPT_USERAGENT => 'Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14',
        // CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
        CURLOPT_USERAGENT => 'Nokia5250/10.0.011 (SymbianOS/9.4; U; Series60/5.0 Mozilla/5.0; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/525 (KHTML, like Gecko) Safari/525 3gpp-gba',
        // CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.54 Safari/537.36 Edg/95.0.1020.40',

        CURLOPT_ENCODING => '',
        CURLOPT_COOKIE => $cookie,
        CURLOPT_HTTPHEADER => [
            'Connection: keep-alive',
            'Keep-Alive: 300',
            'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
            'Accept-Language: en-us,en;q=0.5'
        ],
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_CONNECTTIMEOUT => 60,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_HTTPHEADER => [
            'Expect:',
        ]
    ]);
    $excec = curl_exec($ch);
    curl_close($ch);
    return $excec;
}

function getUrl($url, $cookie, $query)
{
    $url = $url . urlencode($query);
    $pageHtml =  requestWithCookie($url, $cookie);
    $xml = simplexml_load_string($pageHtml) or die("Error: Cannot create object");
    // print_r($xml);

    $json = stripcslashes(json_encode($xml, JSON_PRETTY_PRINT));

    preg_match_all('((https?|ftp|gopher|telnet|file|notes|ms-help):((\/\/)m.facebook.com|(\\\))+[\w\d:#@%\/;$()~_?\+-=\\\.&]*)
    ', $json, $result, PREG_PATTERN_ORDER);

    $urlArr = array();

    //chỉ có array index 0 có link
    $urls = $result[0];

    // return $result vao array;
    foreach ($urls as $url) {
        $url = strtok($url, "?");
        $urlArr[] = $url;
    }

    // Lọc array ko trùm lặp link1
    foreach (array_unique($urlArr) as $url) {
        $startString = 'https://m.facebook.com/groups/';
        if (substr($url, 0, strlen($startString)) === $startString) {
            $urlArr[] = $url;
            return $urlArr;
        }
    }
    // echo $urlArr[0] . "\n";
}

function getJoinLink($url, $cookie, $query)
{
    $url = $url . urlencode($query);
    $pageHtml =  requestWithCookie($url, $cookie);
    $xml = simplexml_load_string($pageHtml) or die("Error: Cannot create object");
    // print_r($xml);
    $json = stripcslashes(json_encode($xml, JSON_PRETTY_PRINT));
    preg_match_all('/\/a\/group\/join\/\?[^\s]+/', $json, $result, PREG_PATTERN_ORDER);
    $linksJoin = array();
    //chỉ có array index 0 có link
    $urlsJoin = $result[0];

    // return $result vao array;
    foreach ($urlsJoin as $url) {
        $joinUrl = "https://mbasic.facebook.com" . $url;
        $url = str_replace('",', '', $joinUrl);
        $linksJoin[] = $url;
    }
    return $linksJoin;
}

$cookie = $_POST["cookie"];
$query = $_POST["groupName"];

@getJoinLink("https://m.facebook.com/search/groups/?q=", $cookie, $query);


if (isset($_POST["submit"])) {
    $query = $_POST["groupName"];
    $cookie = $_POST["cookie"];
} else {
    header('Location: ../index.php');
    exit;
}
