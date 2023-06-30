<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>메세지 박스</title>
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/message/css/message.css?v=<?= date('Ymdhis') ?>">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/css/slide.css?v=<?= date('Ymdhis') ?>">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/css/header.css?v=<?= date('Ymdhis') ?>">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/css/main.css?v=<?= date('Ymdhis') ?>">
  <!-- 부트스트랩 script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <!-- 부트스트랩 CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>
  <header>
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/header.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/slide.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/page_lib.php";
    ?>

  </header>
  <section>
    <div id="message_box" class="container w-100">
      <h3 class="container w-70">
        <?php
        include_once $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/db/db_connect.php";
        $page = (isset($_GET['page']) && $_GET["page"] != '') ? $_GET['page'] : 1;
        $mode = (isset($_GET['mode']) && $_GET["mode"] != '') ? $_GET['mode'] : '';

        if ($mode == "send")
          echo "송신 쪽지함 > 목록보기";
        else
          echo "수신 쪽지함 > 목록보기";
        ?>
      </h3>
      <div class="container w-70">
        <ul id="message">
          <li>
            <span class="col1">번호</span>
            <span class="col2">제목</span>
            <span class="col3">
              <?php
              if ($mode == "send")
                echo "받은이";
              else
                echo "보낸이";
              ?>
            </span>
            <span class="col4">등록일</span>
          </li>
          <?php
          if ($mode == "send")
            $sql = "select count(*) as cnt from message where send_id=:userid order by num desc";
          else
            $sql = "select count(*) as cnt from message where rv_id=:userid order by num desc";

          $stmt = $conn->prepare($sql);
          $stmt->setFetchMode(PDO::FETCH_ASSOC);
          $stmt->bindParam(':userid', $userid);
          $stmt->execute();
          $row = $stmt->fetch();
          // 전체 글 수
          $total_record = $row['cnt'];
          $scale = 10;



          // 전체 페이지 수($total_page) 계산 
          if ($total_record % $scale == 0)
            $total_page = floor($total_record / $scale);
          else
            $total_page = floor($total_record / $scale) + 1;

          // 표시할 페이지($page)에 따라 $start 계산  
          $start = ($page - 1) * $scale;
          $number = $total_record - $start;

          if ($mode == "send")
            $sql = "select * from message where send_id=:userid order by num desc limit {$start}, {$scale}";
          else
            $sql = "select * from message where rv_id=:userid order by num desc";

          $stmt = $conn->prepare($sql);
          $stmt->setFetchMode(PDO::FETCH_ASSOC);
          $stmt->bindParam(':userid', $userid);
          $stmt->execute();
          $rows = $stmt->fetchAll();


          // for ($i = $start; $i < $start + $scale && $i < $total_record; $i++) {
          foreach ($rows as $row) {
            // 하나의 레코드 가져오기
            $num = $row["num"];
            $subject = $row["subject"];
            $regist_day = $row["regist_day"];

            if ($mode == "send")
              $msg_id = $row["rv_id"];
            else
              $msg_id = $row["send_id"];

            $sql2 = "select name from members where id='$msg_id'";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->setFetchMode(PDO::FETCH_ASSOC);
            $stmt2->execute();
            $record = $stmt2->fetch();
            $msg_name = $record["name"];
          ?>
            <li>
              <span class="col1"><?= $number ?></span>
              <span class="col2"><a href="message_view.php?mode=<?= $mode ?>&num=<?= $num ?>"><?= $subject ?></a></span>
              <span class="col3"><?= $msg_name ?>(<?= $msg_id ?>)</span>
              <span class="col4"><?= $regist_day ?></span>
            </li>
          <?php
            $number--;
          }
          ?>
        </ul>
        <div class="container d-flex justify-content-center align-items-start gap-2 mb-3">
          <?php
          $page_limit = 5;
          echo pagination($total_record, 10, $page_limit, $page, $mode);
          if ($mode == "send") {
          ?>
            <button type="button" class="btn btn-outline-primary" id="btn_excel_<?= $mode ?>">엑셀로 저장</button>
          <?php
          } else if ($mode == "rv") {
          ?>
            <button type="button" class="btn btn-outline-primary" id="btn_excel_<?= $mode ?>">엑셀로 저장</button>

          <?php
          }
          ?>

        </div>

        <ul class="buttons">
          <li><button onclick="location.href='message_box.php?mode=rv'">수신 쪽지함</button></li>
          <li><button onclick="location.href='message_box.php?mode=send'">송신 쪽지함</button></li>
          <li><button onclick="location.href='message_form.php'">쪽지 보내기</button></li>
        </ul>
      </div> <!-- message_box -->
  </section>
  <footer>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/footer.php"; ?>
  </footer>
</body>

</html>