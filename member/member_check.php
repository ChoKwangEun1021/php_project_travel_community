<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/php_source/project/db/db_connect.php";


$mode = (isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : '';
if ($mode == '') {
  die(json_encode(['result' => 'empty']));
}
$id = (isset($_POST['id']) && $_POST['id'] != '') ? $_POST['id'] : '';
$email = (isset($_POST['email']) && $_POST['email'] != '') ? $_POST['email'] : '';
switch ($mode) {
  case 'id_chk': {
      if ($id == '') {
        die(json_encode(['result' => 'empty_id']));
      }
      $sql = "select * from members where id=:id";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':id', $id);
      $stmt->execute();
      $rows = $stmt->rowCount();

      if ($rows >= 1) {
        die(json_encode(['result' => 'fail']));
      } else {
        die(json_encode(['result' => 'success']));
      }
      break;
    }
  case 'email_chk': {
      if ($email == '') {
        die(json_encode(['result1' => 'empty_email']));
      }
      $sql = "select * from members where email=:email";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':email', $email);
      $stmt->execute();
      $rows = $stmt->rowCount();

      if ($rows >= 1) {
        die(json_encode(['result1' => 'fail']));
      } else {
        die(json_encode(['result1' => 'success']));
      }

      break;
    }
}
