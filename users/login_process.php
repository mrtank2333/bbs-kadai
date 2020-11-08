<?php
if (empty($_POST['login_id']) || empty($_POST['password'])) {
  header("HTTP/1.1 302 Found");
  header("Location: ./login.php");
  return;
}

//データベースハンドラ作成
require_once '../script/db_func.php';
$db_func = new db_func();
$dbh = $db_func->acc_db();

//ログインIDが一致する1行だけ取得
$select_sth = $dbh->prepare('SELECT login_id, password FROM users WHERE login_id = :login_id LIMIT 1');
$select_sth->execute([
    ':login_id' => $_POST['login_id'],
]);
$row = $select_sth->fetch();

//ob_start();
//行を取得できなかった場合エラー
if (!$row) {
    print('ログインIDがみつかりませんでした。<a href="./login.php">戻る</a>');
    //sleep(5);
    //header("HTTP/1.1 302 Found");
    //header("Location: ./login.php");
    return;
}

// phpに用意されている password_verify() 関数を使ってパスワードをチェックします
if (!password_verify($_POST['password'], $row['password'])) {
    print('パスワードが間違っています。<a href="./login.php">戻る</a>');
   // sleep(5);
    //header("HTTP/1.1 302 Found");
  //  header("Location: ./login.php");
    return;
}
//ob_end_flush();

//パスワードがあっている，つまりログイン成功です。
//ログイン状態をCookieに保存します。
setcookie('login_id', $row['login_id'], 0, '/');
// /bbsでも使いたいcookieなので，パスを指定しています。

// ログイン完了したら会員登録完了画面に飛ばす
header("HTTP/1.1 302 Found");
header("Location: ./login_finish.php");
return;
?>
