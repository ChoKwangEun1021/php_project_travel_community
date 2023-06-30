<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/db/db_connect.php";

$send_id = (isset($_POST['send_id']) && $_POST['send_id'] != '') ? $_POST['send_id'] : '';
$rv_id = (isset($_POST['rv_id']) && $_POST['rv_id'] != '') ? $_POST['rv_id'] : '';
$subject = (isset($_POST['subject']) && $_POST['subject'] != '') ? $_POST['subject'] : '';
$content = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content'] : '';

if ($send_id == "" or $rv_id == "" or $subject == "" or $content == "") {
  die("
  <script>
  alert('모든 항목을 입력해주세요!');
  history.go(-1);
  </script>
  ");
}

//중요함
$subject = htmlspecialchars($subject, ENT_QUOTES);
$content = htmlspecialchars($content, ENT_QUOTES);
$regist_day = date('Y-m-d H:i:s');

// sql 침입방지
$sql = "select * from members where id = :rv_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':rv_id', $rv_id);
$result = $stmt->execute();
$rows = $stmt->rowCount();



if (!$result) {
  echo ("<h2 style='text-align: center'>아이디 쿼리문 오류: {mysqli_error($conn)}</h2>");
  die("<script>
      history.go(-1);
    </script>");
}

if ($rows) {
  // sql 침입방지
  $sql = "insert into message(send_id, rv_id, subject, content, regist_day) values (:send_id, :rv_id, :subject, :content, :regist_day)";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':send_id', $send_id);
  $stmt->bindParam(':rv_id', $rv_id);
  $stmt->bindParam(':subject', $subject);
  $stmt->bindParam(':content', $content);
  $stmt->bindParam(':regist_day', $regist_day);
  $stmt->execute();
} else {
  echo ("<h2 style='text-align: center'>수신 아이디가 잘못되었습니다!</h2>");
  die("<script>
       history.go(-1);
      </script>");
}


echo "
<script>
self.location.href = 'http://{$_SERVER['HTTP_HOST']}/php_source/project/message/message_box.php?mode=send'</script>
";
