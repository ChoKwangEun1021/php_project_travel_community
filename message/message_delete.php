<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/db/db_connect.php";

$num = (isset($_GET['num']) && $_GET['num'] != '') ? $_GET['num'] : '';
$mode = (isset($_GET['mode']) && $_GET['mode'] != '') ? $_GET['mode'] : '';

$sql = "delete from message where num= :num";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":num", $num);
$result = $stmt->execute();

if (!$result) {
  die("<script>
      history.go(-1);
    </script>");
}

if ($mode == "send") {
  $url = "http://{$_SERVER['HTTP_HOST']}/php_source/project/message/message_box.php?mode=send";
} else {
  $url = "http://{$_SERVER['HTTP_HOST']}/php_source/project/message/message_box.php?mode=rv";
}

echo "
<script>
  location.href = '$url';
</script>
";
