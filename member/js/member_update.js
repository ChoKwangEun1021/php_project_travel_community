document.addEventListener("DOMContentLoaded", () => {
  let name_regx = /^[가-힣]{2,6}$|^[A-z]{4,10}$/
  const send = document.querySelector("#send");
  const cancel = document.querySelector("#cancel");

  send.addEventListener("click", () => {
    if (document.member_form.pass_confirm.value != "") {
      if (document.member_form.pass.value == "") {
        alert("비밀번호를 입력하세요!");
        document.member_form.pass.focus();
        return false;
      }
    }

    if (document.member_form.pass.value != "") {
      if (document.member_form.pass_confirm.value == "") {
        alert("비밀번호 확인을 입력하세요!");
        document.member_form.pass_confirm.focus();
        return false;
      }
    }

    if (document.member_form.pass.value != "" && document.member_form.pass_confirm !="") {
      //패턴검색 진행을 해서 안맞으면 경고메세지
      if (
        document.member_form.pass.value != document.member_form.pass_confirm.value
      ) {
        alert("비밀번호가 일치하지 않습니다.\n다시 입력해 주세요!");
        document.member_form.pass.focus();
        document.member_form.pass_confirm.value = "";
        document.member_form.pass.select();
        return false;
      }
    }
    
    if (document.member_form.name.value == "") {
      alert("이름을 입력하세요!");
      document.member_form.name.focus();
      return false;
    }
    if(document.member_form.name.value.match(name_regx) == null){
      alert("이름 한글 2글자 이상 4글자 이하, 영문4자 이상 10자 이하");
      document.member_form.name.focus();
      return false;
    }
    if (document.member_form.email1.value == "") {
      alert("이메일 주소를 입력하세요!");
      document.member_form.email1.focus();
      return false;
    }
    if (document.member_form.email2.value == "") {
      alert("이메일 주소를 입력하세요!");
      document.member_form.email2.focus();
      return false;
    }
    document.member_form.submit();
  });
  cancel.addEventListener("click", () => {
    document.member_form.pass.value = "";
    document.member_form.pass_confirm.value = "";
    document.member_form.name.value = "";
    document.member_form.email1.value = "";
    document.member_form.email2.value = "";
    return;
  });
});

function check_email() {
  const form = document.member_form

  if (document.member_form.email1.value == "") {
    alert("이메일을 입력하세요!");
    document.member_form.email1.focus();
    return false;
  }
  if (document.member_form.email2.value == "") {
    alert("이메일을 입력하세요!");
    document.member_form.email2.focus();
    return false;
  }
  let email_regx = /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/

  let mail = document.member_form.email1.value+ "@"+ document.member_form.email2.value
  if (mail.match(email_regx) == null) {
    alert("이메일 주소가 올바르지 않습니다.");
    form.email1.focus();
    return false;
  }
  
  // AJAX
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "./member_check.php", true);
  // 전송할 데이터 생성
  const formdata = new FormData();
  formdata.append("email", mail);
  formdata.append("mode", "email_chk");

  xhr.send(formdata);
  // 서버에 JSON 데이터가 도착 완료
  xhr.onload = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      // json.parse = json객체를 javascript객체로 변환
      // {'result': 'success'} => {result: 'success'}
      const data = JSON.parse(xhr.responseText);
      switch (data.result1) {
        case "fail":
          alert("사용 불가능한 이메일입니다.");
          document.member_form.email1.value = "";
          document.member_form.email2.value = "";
          document.member_form.email_chk.value = "0"
          document.member_form.email1.focus();
          break;
        case "success":
          alert("사용 가능한 이메일입니다.");
          document.member_form.email1.focus();
          document.member_form.email_chk.value = "1"
          break;
        case "empty_email":
          alert("이메일를 입력해주세요.");
          document.member_form.email1.focus();
          break;
        default:
      }
    } else {
      alert("서버 통신 불가");
    }
  };
}