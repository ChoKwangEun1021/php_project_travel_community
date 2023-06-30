document.addEventListener("DOMContentLoaded",()=>{
  const btn_excel_send = document.querySelector("#btn_excel_send")
  const btn_excel_rv = document.querySelector("#btn_excel_rv")
  if(btn_excel_send == null){
    const btn_excel_rv = document.querySelector("#btn_excel_rv")
    btn_excel_rv.addEventListener("click", ()=>{
      self.location.href = "./message_to_excel.php?mode=rv";
    })
  } 
  if(btn_excel_rv == null){
    const btn_excel_send = document.querySelector("#btn_excel_send")
    btn_excel_send.addEventListener("click", ()=>{
      self.location.href = "./message_to_excel.php?mode=send";
    })
  }
})