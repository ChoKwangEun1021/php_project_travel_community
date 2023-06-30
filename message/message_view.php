<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>PHP 프로그래밍 입문</title>
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/message/css/message.css?v=<?= date('Ymdhis') ?>">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/css/slide.css?v=<?= date('Ymdhis') ?>">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/css/header.css?v=<?= date('Ymdhis') ?>">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/css/main.css?v=<?= date('Ymdhis') ?>">
  <script src="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/js/slide.js?v=<?= date('Ymdhis') ?>" defer></script>
  <script src="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/message/js/message.js?v=<?= date('Ymdhis') ?>" defer></script>
</head>

<body>
  <header>
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/header.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/slide.php";
    ?>
  </header>
  <section>
    <div id="message_box">
      <h3 class="title">
        <?php
        $mode = (isset($_GET['mode']) && $_GET['mode'] != '') ? $_GET['mode'] : '';
        $num = (isset($_GET['num']) && $_GET['num'] != '') ? $_GET['num'] : '';
        if ($mode == "" && $num == "") {
          die("
          <script>
          alert('경고');
          history.go(-1);
          </script>
          ");
        }

        include_once $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/db/db_connect.php";

        // sql 침입방지
        $sql = "select * from message where num = :num";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':num', $num);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rowArray = $stmt->fetchAll();
        foreach ($rowArray as $row) {
          $send_id = $row["send_id"];
          $rv_id = $row["rv_id"];
          $regist_day = $row["regist_day"];
          $subject = $row["subject"];
          $content = $row["content"];
        }

        $content = str_replace(" ", "&nbsp;", $content);
        $content = str_replace("\n", "<br>", $content);

        if ($mode == "send") {
          // sql 침입방지
          $sql = "select name from members where id=:rv_id";
          $stmt = $conn->prepare($sql);
          $stmt->bindParam(':rv_id', $rv_id);
          $stmt->execute();
        } else {
          // sql 침입방지
          $sql = "select name from members where id=:send_id";
          $stmt = $conn->prepare($sql);
          $stmt->bindParam(':send_id', $send_id);
          $stmt->execute();
        }

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rowArray = $stmt->fetchAll();
        foreach ($rowArray as $record) {
          $msg_name = $record["name"];
        }

        if ($mode == "send") {
          echo "송신 쪽지함 > 내용보기";
        } else {
          echo "수신 쪽지함 > 내용보기";
        }
        ?>
      </h3>
      <ul id="view_content">
        <li>
          <span class="col1"><b>제목 :</b> <?= $subject ?></span>
          <span class="col2"><?= $msg_name ?> | <?= $regist_day ?></span>
        </li>
        <li>
          <?= $content ?>
        </li>
      </ul>
      <ul class="buttons">
        <li><button onclick="location.href='message_box.php?mode=rv'">수신 쪽지함</button></li>
        <li><button onclick="location.href='message_box.php?mode=send'">송신 쪽지함</button></li>
        <li><button onclick="location.href='message_response_form.php?num=<?= $num ?>'">답변 쪽지</button></li>
        <li><button onclick="location.href='message_delete.php?num=<?= $num ?>&mode=<?= $mode ?>'">삭제</button></li>
      </ul>
    </div> <!-- message_box -->
  </section>
  <footer>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/footer.php"; ?>
  </footer>
</body>

</html>