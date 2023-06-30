document.addEventListener("DOMContentLoaded",()=>{
  const login = document.querySelector("#login")
  login.addEventListener("click",()=>{
    if (!document.login_form.id.value) {
          alert("아이디를 입력하세요!");
          document.login_form.id.focus();
          return;
        }
        if (!document.login_form.pass.value) {
          alert("비밀번호를 입력하세요!");
          document.login_form.pass.focus();
          return;
        }
        // alert("확인")
        document.login_form.submit();
  })
})