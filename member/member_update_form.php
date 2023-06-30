<!DOCTYPE html>
<html lang="ko">

<head>
  <meta charset="utf-8">
  <title>부트스트랩을 이용한 페이징처리방법</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- 다음 스크립트 로딩(우편번호 찾기) -->
  <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
  <!-- 슬라이드 스크립트 -->
  <script src="http://<?= $_SERVER['HTTP_HOST'] . '/php_source/project/common/js/slide.js' ?>" defer></script>
  <!-- 회원 수정 js -->
  <script src="http://<?= $_SERVER['HTTP_HOST'] ?>/php_source/project/member/js/member_update.js?v=<?= date('Ymdhis') ?>"></script>
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
    include_once $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/db/db_connect.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/header.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/slide.php";
    $num = (isset($_SESSION['num']) && $_SESSION['num'] != "" && is_numeric($_SESSION['num'])) ? $_SESSION['num'] : "";
    if ($num == "") {
      die("<script>
            alert('로그인을 해주세요.');
            history.go(-1);
            </script>");
    }

    $sql = "select * from members where num=:num";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':num', $num);
    $result = $stmt->execute();

    if (!$result) {
      die("<script>
            alert('데이터 검색 오류');
            history.go(-1);
          </script>");
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $emailArray = explode("@", $row['email']);
    ?>
  </header>
  <div class="container w-50 border p-5 my-3 rounded-5">
    <h3> 회 원 수 정</h3>
    <form name="member_form" method="post" autocomplete="off" action="./member_update.php">
      <input type="hidden" name="num" value=<?= $row['num'] ?>>
      <fieldset>
        <table class="table">
          <colgroup>
            <col width=15%>
            <col width=85%>
          </colgroup>
          <tr>
            <td>
              <label for="id"><b>아이디</b></label>
            </td>
            <td>
              <input type="text" name="id" value=<?= $row['id'] ?> readonly>
            </td>
          </tr>
          <tr>
            <td>
              <label for="pass"><b>비밀번호</b></label>
            </td>
            <td>
              <input type="password" name="pass" id="pass" required="required" placeholder="변경하실 비밀번호를 입력해주세요">
            </td>
          </tr>
          <tr>
            <td>
              <label for="pass_confirm"><b>비밀번호 확인</b></label>
            </td>
            <td>
              <input type="password" name="pass_confirm" id="pass_confirm" required="required">
            </td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <table class="table">
          <colgroup>
            <col width=15%>
            <col width=85%>
          </colgroup>
          <tr>
            <td>
              <label class="private" for="name"><b>닉네임</b></label>
            </td>
            <td>
              <label id="instruction2">공백없이 한글,영문,숫자만 입력 가능(한글 2자, 영문4자 이상)<br />
                닉네임을 바꾸시면 앞으로 1일 이내에는 변경 할 수 없습니다.</label><br />
              <input type="text" name="name" value=<?= $row['name'] ?>>
            </td>
          </tr>
          <tr>
            <input type="hidden" name="email_chk" value="0">
            <td>
              <label class="private" for="mail"><b>E-mail</b></label>
            </td>
            <td>
              <input type="text" name="email1" value=<?= $emailArray[0] ?>>@
              <select name="email2">
                <option value="">-선택하세요-</option>
                <option value="naver.com">naver.com</option>
                <option value="google.com">google.com</option>
                <option value="daum.net">daum.net</option>
              </select>
              <button type="button" onclick="check_email()">중복확인</button>
            </td>
          </tr>
        </table>
      </fieldset>
      <tr>
        <td colspan="2" text-align="center">
          <button type="button" class="btn btn-primary" id="send">회원 수정</button>
          <button type="reset" class="btn btn-secondary" id="cancel">취소</button>
        </td>
      </tr>
      </table>
      </fieldset>
    </form>
  </div>
  <footer>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/common/footer.php"; ?>
  </footer>
  <script>
    const email2 = document.querySelector("#email2")
    email2.value = '<?= $emailArray[1] ?>'
  </script>
</body>

</html>