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
$level   = (isset($_POST['level']) && $_POST['level'] != '') ? $_POST['level'] : '';
$point   = (isset($_POST['point']) && $_POST['point'] != '') ? $_POST['point'] : '';

$sql = "update members set level=:level, point=:point where num=:num";
$stmt = $conn->prepare($sql);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$stmt->bindParam(':num', $num);
$stmt->bindParam(':level', $level);
$stmt->bindParam(':point', $point);
$result = $stmt->execute();

echo "
	     <script>
	         location.href = 'admin.php';
	     </script>
	   ";
