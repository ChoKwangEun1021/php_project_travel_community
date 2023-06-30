<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/php_source/khs/common/db_connect.php";
session_start();
$userid    = (isset($_SESSION['userid']) && $_SESSION['userid'] != "") ? $_SESSION['userid'] : "";
$mode = (isset($_GET['mode']) && $_GET['mode'] != '') ? $_GET['mode'] : '';
switch ($mode) {
  case 'send': {
      $name = "message_send";
      $md = "받은이";
      $writeFlag = 0;
      break;
    }
  case 'rv': {
      $name = "message_rv";
      $md = "보낸이";
      $writeFlag = 1;
      break;
    }
}

header("Content-type: application/vnd.ms-excel; charset=utf-8");
//filename = 저장되는 파일명을 설정합니다.
header("Content-Disposition: attachment; filename =$name.xls");
header("Content-Description: PHP4 Generated Data");

//엑셀 파일로 만들고자 하는 데이터의 테이블을 만듭니다.
$EXCEL_FILE = "
<table border='1'>
    <tr>
    <td>번호</td>
            <td>제목</td>
            <td>$md</td>
            <td>등록일</td>
    </tr>
";

if ($mode == "send")
  $sql = "select * from message where send_id=:userid";
else
  $sql = "select * from message where rv_id=:userid";

$stmt = $conn->prepare($sql);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$stmt->bindParam(':userid', $userid);
$result = $stmt->execute();
if (!$result) {
  die("
  <script>
  alert('데이터 로딩 오류');
  </script>");
}
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$rowArray = $stmt->fetchAll();

foreach ($rowArray as $row) {
  $num = $row["num"];
  $subject = $row["subject"];
  $regist_day = $row["regist_day"];

  if ($mode == "send")
    $msg_id = $row["rv_id"];
  else
    $msg_id = $row["send_id"];

  $sql2 = "select name from members where id='$msg_id'";
  $stmt2 = $conn->prepare($sql2);
  $stmt2->setFetchMode(PDO::FETCH_ASSOC);
  $stmt2->execute();
  $record = $stmt2->fetch();
  $msg_name = $record["name"];

  $EXCEL_FILE .= "
<tr>
<td>{$num}</td>
<td>{$subject}</td>
<td>{$msg_name}{($msg_id)}</td>
<td>{$regist_day}</td>
</tr>
";
}

$EXCEL_FILE .= "</table>";

// 만든 테이블을 출력해줘야 만들어진 엑셀파일에 데이터가 나타납니다.
echo "
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
echo $EXCEL_FILE;
