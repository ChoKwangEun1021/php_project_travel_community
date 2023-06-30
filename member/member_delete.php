<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/db/db_connect.php";

$num = (isset($_GET["num"]) and is_numeric($_GET["num"])) ? (int)$_GET["num"] : '';
if ($num == '') {
  die("<script>
      alert('원하는 정보가 없습니다.');
      history.go(-1);
      </script>");
}
$sql = "DELETE from members where num=:num";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':num', $num);
$result = $stmt->execute();

if (!$result) {
  // die("데이터 삽입 오류 : " . mysqli_error($conn));
  die("<script>
      alert('데이터 삭제 오류 :{ mysqli_error($conn)}');
      history.go(-1);
    </script>");
}

echo "
  <script>
    self.location.href = 'http://{$_SERVER['HTTP_HOST']}/php_source/khs/member/member_list.php'
  </script>
";
