<?php

require_once 'searchGroup.php';
$cookie = $_POST["cookie"];
$query = $_POST["groupName"];
$token = $_POST["token"];
session_start();

$_SESSION['cookie'] = $cookie;
$joinUrl = (@getJoinLink("https://m.facebook.com/search/groups/?q=", $cookie, $query));

?>

<?php

foreach ($joinUrl as $item) {
    $n = 0;
    preg_match_all('/\group_id=+.{15}/', $item, $grUrlArr, PREG_PATTERN_ORDER);
    $linkgr = $grUrlArr[0];
    $link = str_replace('group_id=', '', $linkgr);
    // $groupId = $link;
    $json = requestWithCookie("https://graph.facebook.com/v12.0/" . $link[$n] . "?access_token=" . $token, $cookie);
    $grName = json_decode($json, true);
?>
    <form method="post" action="joinGroup.php">
        <div class="container">
            <br>
            <h2><label>Group với từ khóa bạn đã tìm</label></h2>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="<?php $item; ?>" id="flexCheckDefault" name="checkbox[]">
                <label class="form-check-label" for="flexCheckDefault">
                    <?php
                    if (isset($grName["name"])) {
                        echo $grName["name"];
                    } else {
                        echo "Tên group không thể được hiển thị.";
                    } ?>
                </label>
            </div>
            <input type="submit" name="submit">
        </div>
    </form>
<?php
    $n++;
}
echo '<input type="submit" name="submit">';
echo '</form>';

if (empty($joinUrl)) {
    header('Location: ../index.php?error=Vui lòng kiểm tra lại tài khoản của bạn');
    exit;
}

?>