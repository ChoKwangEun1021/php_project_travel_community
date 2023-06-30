document.addEventListener("DOMContentLoaded", () => {
  const messageSend = document.querySelector("#message_send");
  messageSend.addEventListener("click", () => {
    if (!document.message_form.rv_id.value) {
      alert("수신 아이디를 입력하세요!");
      document.message_form.rv_id.focus();
      return;
    }
    if (!document.message_form.subject.value) {
      alert("제목을 입력하세요!");
      document.message_form.subject.focus();
      return;
    }
    if (!document.message_form.content.value) {
      alert("내용을 입력하세요!");
      document.message_form.content.focus();
      return;
    }
    document.message_form.submit();
  })
})
