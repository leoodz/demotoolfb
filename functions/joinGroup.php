<?php

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

if (isset($_POST['checkbox'])) {
    session_start();
    $cookie = $_SESSION['cookie'];
} else {
    header('Location: ../index.php');
    exit;
}

$checkboxes = isset($_POST['checkbox']) ? $_POST['checkbox'] : array();

foreach ($checkboxes as $value) {
    requestWithCookie($value, $cookie);
    sleep(10);
}
header('Location: ../index.php?success=' . 'Đã tham gia');
exit;
