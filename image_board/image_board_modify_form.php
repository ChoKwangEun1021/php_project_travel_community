<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>이미지 게시글 수정</title>
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/image_board/css/image_board.css?v=<?= date('Ymdhis') ?>">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/css/slide.css?v=<?= date('Ymdhis') ?>">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/css/header.css?v=<?= date('Ymdhis') ?>">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/css/main.css?v=<?= date('Ymdhis') ?>">
  <script src="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/image_board/js/image_board.js?v=<?= date('Ymdhis') ?>" defer></script>
  <script src="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/js/slide.js?v=<?= date('Ymdhis') ?>" defer></script>
</head>

<body>
  <header>
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/header.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/slide.php";
    $mode = (isset($_GET['mode']) && $_GET['mode'] != '') ? $_GET['mode'] : '';
    switch ($mode) {
      case 'image_board': {
          $h3 = "게시판 > 수정하기";
          break;
        }
      case 'notice': {
          $h3 = "공지사항 > 수정하기";
          break;
        }
    }
    ?>
  </header>
  <section>
    <div id="board_box">
      <h3>
        <?= "$h3" ?>
      </h3>
      <?php
      include_once $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/db/db_connect.php";
      $num = (isset($_GET["num"]) && $_GET["num"] != '') ? $_GET["num"] : '';
      $page = (isset($_GET["page"]) && $_GET["page"] != '') ? $_GET["page"] : '';
      if ($num == '' && $page == '') {
        die("
	          <script>
            alert('해당되는 정보가 없습니다.');
            history.go(-1)
            </script>           
            ");
      }


      $sql = "select * from image_board where num=:num";
      $stmt = $conn->prepare($sql);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $stmt->bindParam(':num', $num);
      $stmt->execute();
      $row = $stmt->fetch();

      $name       = $row["name"];
      $subject    = $row["subject"];
      $content    = $row["content"];
      $file_name  = $row["file_name"];
      $file_type  = $row["file_type"];
      $file_copied  = $row["file_copied"];
      ?>
      <form name="image_board_form" method="post" action="image_board_insert.php?num=<?= $num ?>&page=<?= $page ?>&mode=<?= $mode ?>" enctype="multipart/form-data">
        <ul id="board_form">
          <li>
            <span class="col1">이름 : </span>
            <span class="col2"><?= $name ?></span>
          </li>
          <li>
            <span class="col1">제목 : </span>
            <span class="col2"><input name="subject" type="text" value="<?= $subject ?>"></span>
          </li>
          <li id="text_area">
            <span class="col1">내용 : </span>
            <span class="col2">
              <textarea name="content"><?= $content ?></textarea>
            </span>
          </li>
          <li>
            <span class="col1"> 첨부 파일 : </span>
            <input type="file" name="upfile" id="upfile">
            <span class="col2"><?= $file_name ?></span>
          </li>
        </ul>
        <ul class="buttons">
          <li><button type="button" id="complete">수정하기</button></li>
          <li><button type="button" onclick="location.href='image_board_list.php?mode=<?= $mode ?>'">목록</button></li>
        </ul>
      </form>
    </div> <!-- board_box -->
  </section>
  <footer>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/footer.php"; ?>
  </footer>
</body>

</html>