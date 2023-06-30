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

if (isset($_POST["item"]))
    $num_item = count($_POST["item"]);
else
    echo ("
                    <script>
                    alert('삭제할 게시글을 선택해주세요!');
                    history.go(-1)
                    </script>
        ");

for ($i = 0; $i < count($_POST["item"]); $i++) {
    $sql = "select * from image_board where num = :num";
    $stmt = $conn->prepare($sql);
    $num = $_POST["item"][$i];
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->bindParam(":num", $num);
    $result = $stmt->execute();
    $row =  $stmt->fetch();

    $copied_name = $row["file_copied"];

    if ($copied_name) {
        $file_path = $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/image_board/data/" . $copied_name;
        unlink($file_path);
    }

    $sql = "delete from image_board where num = :num";
    $stmt = $conn->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->bindParam(":num", $num);
    $result = $stmt->execute();
}

echo "
	     <script>
	         location.href = 'admin.php';
	     </script>
	   ";
