<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>로그인</title>
  <!-- 슬라이드 스크립트 -->
  <script src="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/js/slide.js?v=<?= date('Ymdhis') ?>" defer></script>
  <!-- 로그인 js -->
  <script src="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/login/js/login.js?v=<?= date('Ymdhis') ?>"></script>
  <!-- bootstrap script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
  </script>
  <!-- 부트스트랩 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <!-- 공통, 슬라이드, 헤더 스타일 -->
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/css/slide.css?v=<?= date('Ymdhis') ?>">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/css/header.css?v=<?= date('Ymdhis') ?>">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/common/css/main.css?v=<?= date('Ymdhis') ?>">
</head>

<body>
  <header>
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/header.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/slide.php";
    ?>
  </header>
  <section>
    <div class="container w-50 border p-5 my-3 rounded-5">
      <h3> 로 그 인 </h3>
      <fieldset>
        <form name="login_form" method="post" action="login.php">
          <table class="table">
            <colgroup>
              <col width=15%>
              <col width=85%>
            </colgroup>
            <tr>
              <input type="hidden" name="id_chk" value="0">
              <td>
                <label for="id"><b>아이디 :</b></label>
              </td>
              <td>
                <input type="text" name="id" placeholder="아이디"></li><br>
              </td>
            </tr>
            <tr>
              <td>
                <label for="pass"><b>비밀번호 :</b></label>
              </td>
              <td>
                <input type="password" name="pass" id="pass" required="required">
              </td>
            </tr>
            <tr>
              <td><button type="button" id="login">로그인</button></td>
            </tr>
          </table>
        </form>
      </fieldset>
    </div>
  </section>
  <footer>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/footer.php"; ?>
  </footer>
</body>

</html>