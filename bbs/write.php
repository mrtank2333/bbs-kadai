<?php
if (empty($_POST['body'])) {
  header("HTTP/1.1 302 Found");
  header("Location: ./read.php");
  return;
}

// ログインしていなければ閲覧画面に戻す
if (empty($_COOKIE["login_id"])) {
  header("HTTP/1.1 302 Found");
  header("Location: ./read.php");
  return;
}

// データベースハンドラ作成
require_once '../script/db_func.php'; 
$db_func = new db_func(); 
$dbh = $db_func ->acc_db();
// 投稿保存用テーブル bbs_entries に1行insert
// SQLインジェクションを防ぐためにプレースホルダを使う
$insert_sth = $dbh->prepare("INSERT INTO bbs_entries (name, body,textcolor) VALUES (:name, :body,:textcolor)");
$insert_sth->execute([
    ':name' => $_COOKIE['name'],
    ':body' => $_POST['body'],
    ':textcolor' => $_POST['textcolor']
]);
//cookieでカラーを保存する
require_once '../script/cookies';

// 書き込み完了したら閲覧画面に戻す
header("HTTP/1.1 302 Found");
header("Location: ./read.php");
return;
?>
