<?php
function create_table($conn, $table_name)
{
  $createTableFlag = false;
  $sql = "show tables from userDB";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $stmt->setFetchMode(PDO::FETCH_NUM);
  $rowArray = $stmt->fetchAll();
  // 테이블 유무 확인
  foreach ($rowArray as $row) {
    if ($row[0] == $table_name) {
      $createTableFlag = true;
      break;
    }
  }
  if ($createTableFlag == false) {
    switch ($table_name) {
      case 'members':
        $sql = "CREATE TABLE `members` (
          `num` int(11) NOT NULL AUTO_INCREMENT,
          `id` char(15) NOT NULL,
          `pass` varchar(255) NOT NULL,
          `name` char(10) NOT NULL,
          `email` char(80) DEFAULT NULL,
          `zipcode` char(5) DEFAULT '',
          `addr1` varchar(255) DEFAULT '',
          `addr2` varchar(255) DEFAULT '',
          `regist_day` char(20) DEFAULT NULL,
          `level` int(11) DEFAULT '0',
          `point` int(11) DEFAULT '0',
          PRIMARY KEY (`num`),
          UNIQUE KEY `uk_id` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        break;
      case 'board':
        $sql = "CREATE TABLE `board` (
          `num` int(11) NOT NULL AUTO_INCREMENT,
          `id` char(15) NOT NULL,
          `name` char(10) NOT NULL,
          `subject` char(200) NOT NULL,
          `content` text NOT NULL,
          `regist_day` char(20) NOT NULL,
          `hit` int(11) NOT NULL,
          `file_name` char(40) DEFAULT NULL,
          `file_type` char(40) DEFAULT NULL,
          `file_copied` char(40) DEFAULT NULL,
          PRIMARY KEY (`num`)
        ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
        ";
        break;
      case 'image_board':
        $sql = "CREATE TABLE `image_board` (
                        `num` int NOT NULL AUTO_INCREMENT,
                        `id` char(15) NOT NULL,
                        `name` char(10) NOT NULL,
                        `subject` char(200) NOT NULL,
                        `content` text NOT NULL,
                        `regist_day` char(20) NOT NULL,
                        `hit` int NOT NULL, 
                        `file_name` char(40) NOT NULL,
                        `file_type` char(40) NOT NULL,
                        `file_copied` char(40) NOT NULL,
                        `notice_flag` int default 0,
                        PRIMARY KEY (`num`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          ";
        break;
      case 'image_board_ripple':
        $sql = "CREATE TABLE `image_board_ripple` (
                `num` int(11) NOT NULL AUTO_INCREMENT,
                `parent` int(11) NOT NULL,
                `id` char(15) NOT NULL,
                `name` char(10) NOT NULL,
                `nick` char(10) NOT NULL,
                `content` text NOT NULL,
                `regist_day` char(20) DEFAULT NULL,
                PRIMARY KEY (`num`)
              ) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
          ";
        break;
      case 'message':
        $sql = 'CREATE TABLE `message` (
          `num` int(11) NOT NULL AUTO_INCREMENT,
          `send_id` char(20) NOT NULL,
          `rv_id` char(20) NOT NULL,
          `subject` char(200) NOT NULL,
          `content` text NOT NULL,
          `regist_day` char(20) DEFAULT NULL,
          PRIMARY KEY (`num`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
        break;
      default:
        echo "<script>alert('해당 {$table_name}명이 없습니다.')</script>";
        break;
    }
    if ($sql != "") {
      $stmt = $conn->prepare($sql);
      $result = $stmt->execute();
      if ($result) {
        echo "<script>alert('해당 {$table_name}이 생성되었습니다..')</script>";
      } else {
        echo "<script>alert('해당 {$table_name} 생성이 실패했습니다.')</script>";
      }
    }
  }
}
