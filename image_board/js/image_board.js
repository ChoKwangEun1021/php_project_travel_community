document.addEventListener("DOMContentLoaded", () => {
  const complete = document.querySelector("#complete");
  complete.addEventListener("click", () => {
    if (!document.image_board_form.subject.value) {
      alert("제목을 입력하세요!");
      document.image_board_form.subject.focus();
      return;
    }
    if (!document.image_board_form.content.value) {
      alert("내용을 입력하세요!");
      document.image_board_form.content.focus();
      return;
    }
    document.image_board_form.submit();
  });
});
