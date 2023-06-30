<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/db/db_connect.php";
session_start();
$userLevel = (isset($_SESSION['userlevel']) && $_SESSION['userlevel'] != '') ? $_SESSION['userlevel'] : '';

if ($userLevel != 1) {
  die("
      <script>
        alert('관리자가 아닙니다! 회원 삭제는 관리자만 가능합니다!');
        history.go(-1)
     </script>
        ");
}

$num   = (isset($_GET['num']) && $_GET['num'] != '') ? $_GET['num'] : '';

$sql = "delete from members where num =:num";
$stmt = $conn->prepare($sql);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$stmt->bindParam(':num', $num);
$result = $stmt->execute();

echo "
	     <script>
	         location.href = 'admin.php';
	     </script>
	   ";
