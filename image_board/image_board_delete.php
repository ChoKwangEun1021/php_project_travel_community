<?php
session_start();
$userid = (isset($_SESSION["userid"]) && $_SESSION["userid"] != '') ? $_SESSION["userid"] : '';
$username = (isset($_SESSION["username"]) && $_SESSION["username"] != '') ? $_SESSION["username"] : '';
$num = (isset($_POST["num"]) && $_POST["num"] != '') ? $_POST["num"] : '';
$page = (isset($_POST["page"]) && $_POST["page"] != '') ? $_POST["page"] : '';
$mode = (isset($_GET['mode']) && $_GET['mode'] != '') ? $_GET['mode'] : '';


include_once $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/db/db_connect.php";
if (isset($_POST["mode"]) && $_POST["mode"] === "delete") {
	$sql = "select * from image_board where num = :num";
	$stmt = $conn->prepare($sql);
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$stmt->bindParam(":num", $num);
	$row = $stmt->fetch();
	$stmt->execute();
	$writer = $row["id"];
	if (!isset($userid) || ($userid !== $writer && $userlevel !== '1')) {
		alert_back("수정권한이 없습니다.");
		exit;
	}
	$copied_name = $row["file_copied"];

	if ($copied_name) {
		$file_path = "./data/" . $copied_name;
		unlink($file_path);
	}

	$sql = "delete from image_board where num = :num";
	$stmt = $conn->prepare($sql);
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$stmt->bindParam(":num", $num);
	$stmt->execute();
	echo "
	     <script>
	         location.href = 'board_list.php?page=$page&mode=$mode';
	     </script>
	   ";
}

// $sql = "select * from image_board where num = :num";
// $stmt = $conn->prepare($sql);
// $stmt->setFetchMode(PDO::FETCH_ASSOC);
// $stmt->bindParam(':num', $num);
// $stmt->execute();
// $row = $stmt->fetch();

// $copied_name = $row["file_copied"];

// if ($copied_name) {
// 	$file_path = "./data/" . $copied_name;
// 	unlink($file_path);
// }

// $sql2 = "delete from image_board where num = :num";
// $stmt2 = $conn->prepare($sql2);
// $stmt2->bindParam(':num', $num);
// $stmt2->execute();


// echo "
// 	     <script>
// 	         location.href = 'image_board_list.php?page=$page';
// 	     </script>
// 	   ";
