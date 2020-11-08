
<?php
// データベースハンドラ作成 db名/ユーザー名/パスワードを独自のものに設定している場合は書き換えてください。
require_once '../script/db_func.php';
$db_func = new db_func();
$dbh = $db_func ->acc_db();


// 全行取得 id降順(新しい投稿が上にくる)
$select_sth = $dbh->prepare('SELECT name, body, created_at, textcolor FROM bbs_entries ORDER BY id DESC');
$select_sth->execute();
$rows = $select_sth->fetchAll();

?>
<!DOCTYPE html>
<head>
  <title>掲示板</title>
</head>
<body>
  <h1>掲示板</h1>
  <!--
    投稿用フォーム
    読み込みページに投稿用フォームを用意してあげると利用者は便利です。
  -->
  <?php if(!empty($_COOKIE["login_id"])): ?>
  <form method="POST" action="./write.php" style="margin: 2em;">
    <div>
	名前(ログインid): <?= htmlspecialchars($_COOKIE["login_id"]) ?>
    </div>

    <div>
    <div>
      <input type="color" id="color" name="textcolor">
    </div>
      <textarea name="body" rows="5" cols="100" required></textarea>
    </div>
    <button type="submit">投稿</button>
  </form>
  <?php else: ?>
  投稿するには<a href="/users/login.php">ログイン</a>してください。
  <?php endif; ?>
  <!-- 投稿用フォームここまで -->
  <hr>

  <?php foreach ($rows as $row) : ?>
  <div style="margin: 2em;">
    <span><?= htmlspecialchars($row['name']) ?>さんの投稿</span>
    <span>(投稿日: <?= $row['created_at'] ?>)</span>
    <?php
    echo "<div style=color:".$row['textcolor'].">";
	?>
    <div style="margin-top: 0.5em;"><?= nl2br(htmlspecialchars($row['body'])) ?></div>
   </div>
  </div>
  <hr>
  <?php endforeach; ?>
  <!-- 投稿の表示ここまで -->

</body>
