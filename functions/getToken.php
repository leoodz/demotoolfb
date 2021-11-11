<?php

function requestRaw($url)
{
    $ch = curl_init();

    CURL_SETOPT_ARRAY($ch, [
        CURLOPT_URL => $url,
        CURLOPT_USERAGENT => 'Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14',
        CURLOPT_ENCODING => '',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_CONNECTTIMEOUT => 60,
        CURLOPT_FOLLOWLOCATION => TRUE,
    ]);
    $excec = curl_exec($ch);
    curl_close($ch);
    return $excec;
}

$username = $_POST["username"];
$password = $_POST["password"];

function getCookie($username, $password)
{
    $endpoint = "https://b-graph.facebook.com/auth/login?generate_session_cookies=1&email=" . $username . "&password=" . $password . "&access_token=EAAAAAYsX7TsBAL0v91d3DMGuprZAJM4lKmQZAJtZBJW1DDAwrgYrRPTayKL97ZBqqYZC0SBEjEhQgGBMGD2f2T47oNDvzZBEHcfJFAAHp46mEvAWx4ih3Wk8M2RgPwwTD3uCJA0x5RJZA3ND2hPhGdXBfZADWRDmVF3imRa8y2jkhb5mKdAYdE2YH0EGxit3yShR1TxXW89ZA2k2UuBGmjZBtKMzFdQMAyZAogZD&method=POST";
    $get = json_decode(requestRaw($endpoint), TRUE);
    if (isset($get['error']['message'])) {
        return json_encode([
            'type' => 'error',
            'code' => 201,
            'msg' => $get['error']['message']
        ]);
    } else {
        if (isset($get['access_token'])) {
            $cookie = '';
            foreach ($get['session_cookies'] as $key => $item) {
                $cookie .= $item['name'] . '=' . $item['value'] . ';';
            }
            return json_encode([
                'type' => 'success',
                'code' => 200,
                'msg' => 'Thành công',
                'cookie' => $cookie,
                'token' => $get['access_token']
            ]);
        } else {
            return json_encode([
                'type' => 'error',
                'code' => 201,
                'msg' => 'Đã có lỗi xảy ra!'
            ]);
        }
    }
}
if (isset($_POST["submit"])) {
    $obj = json_decode(getCookie($username, $password), true);
    if (isset($obj["token"])) {
        $obj = json_decode(getCookie($username, $password), true);
?>
        <?php
        require('includes\header.php');
        require('includes\navbar.php');
        ?>
        <br>
        <br>
        <div class="container">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Token của bạn</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" value="<?= $obj["token"]; ?>">
                </div>
                <label class="col-sm-2 col-form-label">Cookie của bạn</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" value="<?= $obj["cookie"]; ?>">
                </div>
            </div>
        </div>
        </body>
<?php
        // echo '<input class="form-control" type="text" placeholder="' . $obj["cookie"] . '" readonly>';
        // echo '<input class="form-control" type="text" placeholder="' . $obj["token"] . '" readonly>';
    } else {
        header('Location: ../index.php?error=' . urlencode($obj["msg"]));
        exit;
    }
} else {
    header('Location: ../index.php?error=Đã có lỗi xảy ra!');
    exit;
}
?>