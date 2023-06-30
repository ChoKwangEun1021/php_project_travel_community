<!DOCTYPE html>
<html lang="ko">

<head>
  <meta charset="utf-8">
  <title>별 보러갈래?</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/js/slide.js?v=<?= date('Ymdhis') ?>"></script>
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/css/slide.css?v=<?= date('Ymdhis') ?>">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/css/header.css?v=<?= date('Ymdhis') ?>">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/css/main.css?v=<?= date('Ymdhis') ?>">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/css/main2.css?v=<?= date('Ymdhis') ?>">
  <!-- 폰트 -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+KR:wght@200&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/6a2bc27371.js" crossorigin="anonymous"></script>
</head>

<body>
  <header>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/header.php"; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/slide.php"; ?>
  </header>
  <section style="height: calc(100vh - 450px);">
    <div id="main_content">
      <div id="latest">
        <h4>최근 게시글</h4>
        <ul>
          <!-- 최근 게시 글 DB에서 불러오기 -->
          <?php
          include_once $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/db/db_connect.php";
          $sql = "select * from image_board order by num desc limit 5";
          $stmt = $conn->prepare($sql);
          $stmt->setFetchMode(PDO::FETCH_ASSOC);
          $result = $stmt->execute();
          $rowArray = $stmt->fetchAll();

          if (!$result)
            echo "게시판 DB 테이블(board)이 생성 전이거나 아직 게시글이 없습니다!";
          else {
            // while ($row = $stmt->fetch()) {
            //   $regist_day = substr($row["regist_day"], 0, 10);
            //   $file_type_0 = $row['file_type'];
            //   $file_name_0 = $row['file_name'];
            //   $file_copied_0 = $row['file_copied'];
            //   $image_width = 100;
            //   $image_height = 100;
            foreach ($rowArray as $row) {
              $regist_day = substr($row["regist_day"], 0, 10);
              $num = $row["num"];
              $id = $row["id"];
              $name = $row["name"];
              $subject = $row["subject"];
              $regist_day = $row["regist_day"];
              $hit = $row["hit"];
              $file_name_0 = $row['file_name'];
              $file_copied_0 = $row['file_copied'];
              $file_type_0 = $row['file_type'];
              $image_width = 100;
              $image_height = 100;
          ?>
              <li>
                <span><?= $row["subject"] ?></span>
                <span><?= $row["name"] ?></span>
                <span><?= $regist_day ?></span>
              </li>
              <!-- <?php
                    if (strpos($file_type_0, "image") !== false) {
                      // echo "<img src='./data/$file_copied_0' width='$image_width' height='$image_height'><br>";
                      echo "<img src='" . $_SERVER['DOCUMENT_ROOT'] . "./php_source/project/image_board/data{$file_copied_0} width='$image_width' height='$image_height' '><br>";
                    } else {
                      echo "<img src='../img/user.jpg' width='$image_width' height='$image_height'><br>";
                    }
                  }
                }
                    ?> -->
      </div>
      <div id="point_rank">
        <h4>포인트 랭킹</h4>
        <ul>
          <!-- 포인트 랭킹 표시하기 -->
          <?php
          $rank = 1;
          $sql2 = "select * from members order by point desc limit 0, 5";
          $stmt2 = $conn->prepare($sql2);
          $stmt2->setFetchMode(PDO::FETCH_ASSOC);
          $result2 = $stmt2->execute();

          if (!$result2)
            echo "회원 DB 테이블(members)이 생성 전이거나 아직 가입된 회원이 없습니다!";
          else {
            while ($row2 = $stmt2->fetch()) {
              $name  = $row2["name"];
              $id    = $row2["id"];
              $point = $row2["point"];
              $name = mb_substr($name, 0, 1) . " * " . mb_substr($name, 2, 1);
          ?>
              <li>
                <span><?= $rank ?></span>
                <span><?= $name ?></span>
                <span><?= $id ?></span>
                <span><?= $point ?></span>
              </li>
          <?php
              $rank++;
            }
          }
          ?>
        </ul>
      </div>
    </div>
  </section>
  <footer>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/footer.php"; ?>
  </footer>
</body>

</html>