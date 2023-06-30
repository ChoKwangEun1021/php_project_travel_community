<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/db/db_connect.php";

$num = (isset($_POST["num"]) &&  $_POST["num"] != '' && is_numeric($_POST["num"])) ? $_POST["num"] : '';
$id = (isset($_POST["id"]) &&  $_POST["id"] != '') ? $_POST["id"] : '';
//정확하게 체크
$pass = (isset($_POST["pass"]) &&  $_POST["pass"] != '') ? $_POST["pass"] : '';
$name = (isset($_POST["name"]) &&  $_POST["name"] != '') ? $_POST["name"] : '';
$email1 = (isset($_POST["email1"]) &&  $_POST["email1"] != '') ? $_POST["email1"] : '';
$email2 = (isset($_POST["email2"]) &&  $_POST["email2"] != '') ? $_POST["email2"] : '';
$email = $email1 . "@" . $email2;

if ($num == '' or $id == '' or $name == '' or $email1 == '' or $email2 == '') {
  die("<script>
      alert('데이터 입력이 잘못되었습니다.\n 확인해주세요.');
      history.go(-1);
    </script>");
}

$sql = "UPDATE members set name=:name, email=:email ";

//패스워드 점검(패스워드를 입력안했다면 기존 패스워드를 사용하겠다는 의미)
if ($pass != '') {
  $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
  $sql .= " , pass=:pass where num=:num ";
} else {
  $sql .= " where num=:num ";
}

$stmt = $conn->prepare($sql);
$stmt->bindParam(":name", $name);
$stmt->bindParam(":email", $email);
if ($pass != '') {
  $stmt->bindParam(":pass", $pass_hash);
}
$stmt->bindParam(":num", $num);
$result = $stmt->execute();

// 세션설정
session_start();
$_SESSION["username"] =  $name;

if (!$result) {
  die("<script>
      alert('데이터 수정 오류');
      history.go(-1);
    </script>");
}

echo "
  <script>
    self.location.href = 'http://{$_SERVER['HTTP_HOST']}/php_source/project/main.php'
  </script>
";
