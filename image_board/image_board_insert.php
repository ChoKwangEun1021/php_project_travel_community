<?php
session_start();
$userid = (isset($_SESSION["userid"]) && $_SESSION["userid"] != '') ? $_SESSION["userid"] : '';
$name = (isset($_SESSION["username"]) && $_SESSION["username"] != '') ? $_SESSION["username"] : '';
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
	         location.href = 'board_list.php?page=$page';
	     </script>
	   ";
} elseif (isset($_POST["mode"]) && $_POST["mode"] === "insert") {

  include_once $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/db/db_connect.php";
  //세션값확인

  if (!$userid) {
    echo ("
		<script>
		alert('게시판 글쓰기는 로그인 후 이용해 주세요!');
		history.go(-1)
		</script>    
        ");
    exit;
  }

  if ($mode == "image_board") {
    $writeFlag = 0;
  } else if ($mode == "notice") {
    $writeFlag = 1;
  }

  $subject = $_POST["subject"];
  $content = $_POST["content"];

  $subject = input_set($subject);
  $content = input_set($content);

  $regist_day = date("Y-m-d (H:i)");  // 현재의 '년-월-일-시-분'을 저장

  $upload_dir = "./data/";

  $upfile_name = $_FILES["upfile"]["name"];
  $upfile_tmp_name = $_FILES["upfile"]["tmp_name"];
  $upfile_type = $_FILES["upfile"]["type"];
  $upfile_size = $_FILES["upfile"]["size"];  // 안되면 php init 에서 최대 크기 수정!
  $upfile_error = $_FILES["upfile"]["error"];

  if ($upfile_name && !$upfile_error) { // 업로드가 잘되었는지 판단
    $file = explode(".", $upfile_name); // trim과 같다. (memo.sql)
    $file_name = $file[0]; //(memo)
    $file_ext = $file[1]; //(sql)

    $new_file_name = date("Y_m_d_H_i_s");
    $new_file_name = $new_file_name . "_" . $file_name;
    $copied_file_name = $new_file_name . "." . $file_ext; // 2020_09_23_11_10_20_memo.sql
    $uploaded_file = $upload_dir . $copied_file_name; // ./data/2020_09_23_11_10_20_memo.sql 다 합친것

    if ($upfile_size > 1000000) {
      echo ("
				<script>
				alert('업로드 파일 크기가 지정된 용량(1MB)을 초과합니다!<br>파일 크기를 체크해주세요! ');
				history.go(-1)
				</script>
				");
      exit;
    }

    if (!move_uploaded_file($upfile_tmp_name, $uploaded_file)) {
      echo ("
					<script>
					alert('파일을 지정한 디렉토리에 복사하는데 실패했습니다.');
					history.go(-1)
					</script>
				");
      exit;
    }
  } else {
    $upfile_name = "";
    $upfile_type = "";
    $copied_file_name = "";
  }

  $sql = "insert into image_board (id, name, subject, content, regist_day, hit, file_name, file_type, file_copied, notice_flag) ";
  $sql .= "values(:userid, :name, :subject, :content, :regist_day, 0, ";
  $sql .= ":upfile_name, :upfile_type, :copied_file_name, :writeFlag )";
  $stmt = $conn->prepare($sql);
  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  $stmt->bindParam(':userid', $userid);
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':subject', $subject);
  $stmt->bindParam(':content', $content);
  $stmt->bindParam(':regist_day', $regist_day);
  $stmt->bindParam(':upfile_name', $upfile_name);
  $stmt->bindParam(':upfile_type', $upfile_type);
  $stmt->bindParam(':copied_file_name', $copied_file_name);
  $stmt->bindParam(':writeFlag', $writeFlag);
  $stmt->execute();

  // 포인트 부여하기
  $point_up = 100;
  $sql2 = "select point from members where id=:userid";

  $stmt2 = $conn->prepare($sql2);
  $stmt2->setFetchMode(PDO::FETCH_ASSOC);
  $stmt2->bindParam(':userid', $userid);
  $stmt2->execute();
  $row = $stmt2->fetch();

  $new_point = $row["point"] + $point_up;

  $sql3 = "update members set point=:new_point where id=:userid";

  $stmt3 = $conn->prepare($sql3);
  $stmt3->bindParam(':new_point', $new_point);
  $stmt3->bindParam(':userid', $userid);
  $stmt3->execute();

  echo "
	   <script>
	    location.href = 'image_board_list.php?mode=$mode';
	   </script>
	";
} elseif (isset($_POST["mode"]) && $_POST["mode"] === "modify") {
  $num = $_POST["num"];
  $page = $_POST["page"];

  $subject = $_POST["subject"];
  $content = $_POST["content"];
  $file_delete = (isset($_POST["file_delete"])) ? $_POST["file_delete"] : 'no';

  $sql = "select * from image_board where num = :num";
  $stmt2 = $conn->prepare($sql);
  $stmt2->setFetchMode(PDO::FETCH_ASSOC);
  $stmt2->bindParam(':num', $num);
  $stmt2->execute();
  $row = $stmt2->fetch();

  $copied_name = $row["file_copied"];

  $upfile_name = $row["file_name"];
  $upfile_type = $row["file_type"];
  $copied_file_name = $row["file_copied"];
  if ($file_delete === "yes") {
    if ($copied_name) {
      $file_path = "./data/" . $copied_name;
      unlink($file_path);
    }
    $upfile_name = "";
    $upfile_type = "";
    $copied_file_name = "";
  } else {
    if (isset($_FILES["upfile"])) {
      if ($copied_name) {
        $file_path = "./data/" . $copied_name;
        unlink($file_path);
      }

      $upload_dir = "./data/";

      $upfile_name = $_FILES["upfile"]["name"];
      $upfile_tmp_name = $_FILES["upfile"]["tmp_name"];
      $upfile_type = $_FILES["upfile"]["type"];
      $upfile_size = $_FILES["upfile"]["size"];  // 안되면 php init 에서 최대 크기 수정!
      $upfile_error = $_FILES["upfile"]["error"];
      if ($upfile_name && !$upfile_error) { // 업로드가 잘되었는지 판단
        $file = explode(".", $upfile_name); // trim과 같다. (memo.sql)
        $file_name = $file[0]; //(memo)
        $file_ext = $file[1]; //(sql)

        $new_file_name = date("Y_m_d_H_i_s");
        $new_file_name = $new_file_name . "_" . $file_name;
        $copied_file_name = $new_file_name . "." . $file_ext; // 2020_09_23_11_10_20_memo.sql
        $uploaded_file = $upload_dir . $copied_file_name; // ./data/2020_09_23_11_10_20_memo.sql 다 합친것

        if ($upfile_size > 1000000) {
          echo ("
				<script>
				alert('업로드 파일 크기가 지정된 용량(1MB)을 초과합니다!<br>파일 크기를 체크해주세요! ');
				history.go(-1)
				</script>
				");
          exit;
        }

        if (!move_uploaded_file($upfile_tmp_name, $uploaded_file)) {
          echo ("
					<script>
					alert('파일을 지정한 디렉토리에 복사하는데 실패했습니다.');
					history.go(-1)
					</script>
				");
          exit;
        }
      } else {
        $upfile_name = $row["file_name"];
        $upfile_type = $row["file_type"];
        $copied_file_name = $row["file_copied"];
      }
    }
  }

  $sql = "update image_board set subject=:subject, content=:content,  file_name=:upfile_name, file_type=:upfile_type, file_copied= :copied_file_name";
  $sql .= " where num=:num";

  $stmt2 = $conn->prepare($sql);
  $stmt2->setFetchMode(PDO::FETCH_ASSOC);
  $stmt2->bindParam(':subject', $subject);
  $stmt2->bindParam(':content', $content);
  $stmt2->bindParam(':upfile_name', $upfile_name);
  $stmt2->bindParam(':upfile_type', $upfile_type);
  $stmt2->bindParam(':copied_file_name', $copied_file_name);
  $stmt2->bindParam(':num', $num);
  $stmt2->execute();
  $row = $stmt2->fetch();

  echo "
	      <script>
	          location.href = 'image_board_list.php?page=$page';
	      </script>
	  ";
} else if (isset($_POST["mode"]) && $_POST["mode"] == "insert_ripple") {
  if (empty($_POST["ripple_content"])) {
    echo "<script>alert('내용입력요망!');history.go(-1);</script>";
    exit;
  }
  //"덧글을 다는사람은 로그인을 해야한다." 말한것이다.
  $userid = $_SESSION['userid'];

  $sql = "select * from members where id = :userid";
  $stmt = $conn->prepare($sql);
  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  $stmt->bindParam(':userid', $userid);
  $result = $stmt->execute();
  if (!$result) {
    die('Error');
  }
  $rowcount = $stmt->rowCount();

  if (!$rowcount) {
    echo "<script>alert('없는 아이디!!');history.go(-1);</script>";
    exit;
  } else {
    $content = input_set($_POST["ripple_content"]);
    $page = input_set($_POST["page"]);
    $parent = input_set($_POST["parent"]);
    $hit = input_set($_POST["hit"]);
    $q_usernick = isset($_SESSION['usernick']) ? mysqli_real_escape_string($con, $_SESSION['usernick']) : "null";
    $q_username = $_SESSION['username'];
    $q_content = $content;
    $q_parent = $parent;
    $regist_day = date("Y-m-d (H:i)");

    $sql = "INSERT INTO `image_board_ripple` VALUES (null,:q_parent, :q_userid,:q_username, :q_usernick,:q_content,:regist_day)";
    $stmt = $conn->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->bindParam(':q_parent', $q_parent);
    $stmt->bindParam(':q_userid', $userid);
    $stmt->bindParam(':q_username', $q_username);
    $stmt->bindParam(':q_usernick', $q_usernick);
    $stmt->bindParam(':q_content', $q_content);
    $stmt->bindParam(':regist_day', $regist_day);
    $result = $stmt->execute();

    if (!$result) {
      die('Error');
    }
    echo "<script>location.href='./image_board_view.php?num=$parent&page=$page&hit=$hit';</script>";
  } //end of if rowcount
} else if (isset($_POST["mode"]) && $_POST["mode"] == "delete_ripple") {
  $page = input_set($_POST["page"]);
  $hit = input_set($_POST["hit"]);
  $num = input_set($_POST["num"]);
  $parent = input_set($_POST["parent"]);

  $sql = "DELETE FROM `image_board_ripple` WHERE num=:num";
  $stmt = $conn->prepare($sql);
  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  $stmt->bindParam(':num', $num);
  $result = $stmt->execute();

  if (!$result) {
    die('Error');
  }
  echo "<script>location.href='./image_board_view.php?num=$parent&page=$page&hit=$hit';</script>";
}
