<?php
date_default_timezone_set("Asia/Seoul");
$servername = 'localhost';
$dbuser = 'root';
$password = '123456';
$dbname = 'userdb';

try {
  $conn = new PDO("mysql:host={$servername};dbname={$dbname}", $dbuser, $password);
  // prepare statement를 지원하지 않는 경우 데이터베이스 기능을 사용하도록 설정
  $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  // 쿼리 버퍼링을 활성화
  $conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
  // PDO 객체가 에러를 처리하는 방식 정함
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "PDO DB 연결성공";
} catch (PDOException $e) {
  echo $e->getMessage();
}

function alert_back($message)
{
  echo ("
<script>
alert('$message');
history.go(-1)
</script>
");
}

function input_set($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
