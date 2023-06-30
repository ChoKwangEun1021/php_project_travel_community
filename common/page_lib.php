<?php
function pagination($total, $limit, $set_page_limit, $page, $mode)
{
  // 페이지보여주기
  //1. 전체페이지
  $total_page = ceil($total / $limit);
  $total_page = ($total == 0) ? 1 : $total_page;
  //2. 보여줄 페이지수
  $page_limit = $set_page_limit;
  //3. 페이징 보여주기
  $start_page = floor(($page - 1) / $page_limit) * $page_limit + 1;
  $end_page = $start_page + $page_limit - 1;
  $end_page = ($end_page > $total_page) ? $total_page : $end_page;
  $prev_page = (($page - 1) < 1) ? 1 : $page - 1;
  //4. 이전페이지
  $prev_page = $page - 1;
  if ($prev_page < 1) {
    $prev_page = 1;
  }



  $page_str = "<main class='d-flex justify-content-center'>";
  $page_str .= "<nav aria-label='Page navigation example'>";
  $page_str .= "<ul class='pagination'>";
  $page_str .= "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page=1&mode=$mode'>FIRST</a></li>";
  if ($page > 1) {
    $page_str .= "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page={$prev_page}&mode=$mode'>PREV</a></li>";
  }
  for ($i = $start_page; $i <= $end_page; $i++) {
    if ($i == $page) {
      $page_str .= "<li class='page-item active'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page={$i}&mode=$mode'>{$i}</a></li>";
    } else {
      $page_str .= "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page={$i}&mode=$mode'>{$i}</a></li>";
    }
  }
  $next_page = (($page + 1) > $total_page) ? $total_page : $page + 1;
  if ($page < $total_page) {
    $page_str .= "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page={$next_page}&mode=$mode'>NEXT</a></li>";
  }
  $page_str .= "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page={$total_page}&mode=$mode'>LAST</a></li>";
  $page_str .= "</ul></nav>";
  $page_str .= "</main>";

  return $page_str;
}
