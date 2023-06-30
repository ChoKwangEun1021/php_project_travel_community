<?php
$real_name = $_GET["real_name"];
$file_name = $_GET["file_name"];
$file_type = $_GET["file_type"];
$file_path = "./data/" . $real_name;

// $_SERVER['HTTP_USER_AGENT'] : 웹브라우저 정보 가져옴
// preg_match(data,target) : target에서 data정보를 찾아라
$ie = preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) ||
  (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0') !== false &&
    strpos($_SERVER['HTTP_USER_AGENT'], 'rv:11.0') !== false);

//IE인경우 한글파일명이 깨지는 경우를 방지하기 위한 코드 
if ($ie) {
  $file_name = iconv('utf-8', 'euc-kr', $file_name);
}

if (file_exists($file_path)) {
  $fp = fopen($file_path, "rb");  //read binary.
  Header("Content-type: application/x-msdownload");   //타입
  Header("Content-Length: " . filesize($file_path));  //사이즈
  Header("Content-Disposition: attachment; filename=" . $file_name);  //파일이름
  Header("Content-Transfer-Encoding: binary");    //인코딩
  Header("Content-Description: File Transfer");   //파일전송
  Header("Expires: 0");   //전송시간
}
//fp가 완료되면 false 출력
if (!fpassthru($fp))
  fclose($fp);
