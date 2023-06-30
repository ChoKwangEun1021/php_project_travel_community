<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/db/db_connect.php";
$num = (isset($_GET["num"]) && $_GET["num"] != '') ? $_GET["num"] : '';
$page = (isset($_GET["page"]) && $_GET["page"] != '') ? $_GET["page"] : '';
$mode = (isset($_GET['mode']) && $_GET['mode'] != '') ? $_GET['mode'] : '';
if ($num == '' && $page == '') {
  die("
	<script>
    alert('해당되는 정보가 없습니다.');
    history.go(-1)
    </script>           
   ");
}

$subject = (isset($_POST["subject"]) && $_POST["subject"] != '') ? $_POST["subject"] : '';
$content = (isset($_POST["content"]) && $_POST["content"] != '') ? $_POST["content"] : '';
$upload_dir = "./data/";
$upfile_name = $_FILES["upfile"]["name"];
$upfile_tmp_name = $_FILES["upfile"]["tmp_name"];
$upfile_type = $_FILES["upfile"]["type"];
$file_copied = date("Y_m_d_H_i_s") . $file_name;
$uploaded_file = $upload_dir . $file_copied; // ./data/2020_09_23_11_10_20_memo.sql 다 합친것

if (!move_uploaded_file($upfile_tmp_name, $uploaded_file)) {
  echo ("
  <script>
  alert('파일을 지정한 디렉토리에 복사하는데 실패했습니다.');
  history.go(-1)
  </script>
");
  exit;
}


$sql = "update image_board set subject=:subject, content=:content, file_name=:upfile_name, file_type=:upfile_type, file_copied= :copied_file_name";
$sql .= " where num=:num";

$stmt = $conn->prepare($sql);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$stmt->bindParam(':subject', $subject);
$stmt->bindParam(':content', $content);
$stmt->bindParam(':upfile_name', $upfile_name);
$stmt->bindParam(':upfile_type', $upfile_type);
$stmt->bindParam(':copied_file_name', $file_copied);
$stmt->bindParam(':num', $num);
$stmt->execute();
$row = $stmt->fetch();

echo "
	      <script>
	          location.href = 'image_board_list.php?page=$page&mode=$mode';
	      </script>
	  ";
