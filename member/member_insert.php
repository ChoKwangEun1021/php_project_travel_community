<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/db/db_connect.php";

$id = (isset($_POST["id"])) ? $_POST["id"] : '';
$pass = (isset($_POST["pass"])) ? $_POST["pass"] : '';
$name = (isset($_POST["name"])) ? $_POST["name"] : '';
$email1 = (isset($_POST["email1"])) ? $_POST["email1"] : '';
$email2 = (isset($_POST["email2"])) ? $_POST["email2"] : '';
$email = $email1 . "@" . $email2;
$zipcode = (isset($_POST["zipcode"])) ? $_POST["zipcode"] : '';
$addr1 = (isset($_POST["addr1"])) ? $_POST["addr1"] : '';
$addr2 = (isset($_POST["addr2"])) ? $_POST["addr2"] : '';
$regist_day = date('Y-m-d H:i:s');

if ($id == "" or $pass == "" or $name == "" or $email == "") {
  die("
  <script>
    alert('데이터 문제 발생');
  </script>");
}

//단방향 패스워드 방식으로 처리
$pass_hash = password_hash($pass, PASSWORD_DEFAULT);

$sql = "insert into members values(null, :id, :pass, :name, :email,:zipcode,:addr1,:addr2, :regist_day, 0, 0)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->bindParam(':pass', $pass_hash);
$stmt->bindParam(':name', $name);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':zipcode', $zipcode);
$stmt->bindParam(':addr1', $addr1);
$stmt->bindParam(':addr2', $addr2);
$stmt->bindParam(':regist_day', $regist_day);
$result = $stmt->execute();

if (!$result) {
  die("<script>
      alert('데이터 삽입 오류');
      self.location.href = 'http://{$_SERVER['HTTP_HOST']}/php_source/project/main.php'
    </script>");
}

echo "
  <script>
    self.location.href = 'http://{$_SERVER['HTTP_HOST']}/php_source/project/main.php'
  </script>
";
