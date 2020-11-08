<?php
if (empty($_POST['login_id']) || empty($_POST['password'])) {
  header("HTTP/1.1 302 Found");
  header("Location: ./register.php");
  return;
}

// データベースハンドラ作成
require_once '../script/db_func.php';
$db_func = new db_func();
$dbh = $db_func -> acc_db();
// 会員テーブル users に1行insert
$insert_sth = $dbh->prepare("INSERT INTO users (login_id, password) VALUES (:login_id, :password)");
$insert_sth->execute([
    ':login_id' => $_POST['login_id'],
    // パスワードは暗号化して保存
    ':password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
]);

// 登録完了したら会員登録完了画面に飛ばす
header("HTTP/1.1 302 Found");
header("Location: ./register_finish.php");
return;
?>
