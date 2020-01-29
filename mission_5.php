<?php
//DB接続
$dsn = "mysql:dbname="データベース名";host=localhost";
$user = "ユーザー名";
$password = "パスワード";
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//初回TABLE作成
$sql = "CREATE TABLE IF NOT EXISTS table_ramen" //すでにテーブルが存在している時警告が発生するのを防ぐ。table_ramenはデータベース名
  ."("
	. "id INT AUTO_INCREMENT PRIMARY KEY," //プライマリーキーはデータと一対一対応の数字。
	. "name char(32),"
	. "comment TEXT,"
  . "created_at TEXT,"
  . "password TEXT"
	.");";
$stmt = $pdo -> query($sql);

//データの取得
$sql = "SELECT * FROM table_ramen";
$stml = $pdo -> query($sql);
$results = $stml -> fetchAll();

//フラグ、パスワードの取得
$name_disp = ""; //フォームに表示する名前
$comment_disp = "";  //フォームに表示するコメント
$edit_num = ""; //編集対象番号
$flag_num = ""; //フラグ番号
$delete_num = ""; //削除番号
$password_post = ""; //投稿フォームのパスワード
$password_delete = ""; //削除フォームのパスワード
$password_edit = ""; //編集フォームのパスワード
if(!empty($_POST["flag"])){
  $flag_num = $_POST["flag"];
}
if(!empty($_POST["delete_num"])){
  $delete_num = $_POST["delete_num"];
}
if(!empty($_POST["password_post"])){
  $password_post = $_POST["password_post"];
}
if(!empty($_POST["password_delete"])){
  $password_delete = $_POST["password_delete"];
}
if(!empty($_POST["password_edit"])){
  $password_edit = $_POST["password_edit"];
}

//編集モード時、フォームに投稿を表示
if(!empty($_POST["edit_num"])){ //編集ボタンが押された時
  $edit_num = $_POST["edit_num"]; //編集対象番号を取得
    foreach($results as $row){ //各投稿について
      $post_num = $row["id"]; //投稿番号=カラム「id」を取得
      $password_edit_correct = $row["password"]; //パスワード=カラム「password」を取得
      if($post_num == $edit_num and $password_edit_correct == $password_edit){ //投稿番号と編集対象番号が等しいかつパスワードが等しい時
        $name_disp = $row["name"]; //フォームに表示する名前
        $comment_disp = $row["comment"]; //フォームに表示するコメント
      }
    }
}
?>

<DOCTYPE html>
<html lang = "ja">
<head>
  <meta charset="utf-8">
  <title>入力フォーム</title>
</head>
<body>
  <div style="height:12px;">
  <span style="margin-left:8px; padding:6px 5px; background:white; font-weight:bold;border-radius:5px;">この掲示板のテーマ</span>
  </div>
  <div style="border:2px solid #000066; padding:25px 12px 10px; font-size:1em;border-radius:5px;">
    好きなラーメンの味を書き込んでください！<br>
    例)
    <ul>
      <li>しょうゆ</li>
      <li>みそ</li>
      <li>とんこつ</li>
    </ul>
  ※投稿する際は自分で決めたパスワードも入力してください。<br>
  ※投稿の削除・編集もやってみてください(._.)
  </div>
  <form action = "mission_5.php" method = "post">
    <p>名前:<input type = "text" name = "name" value = "<?php echo $name_disp ?>"></p>
    <p>コメント:<input type = "text" name = "comment" value = "<?php echo $comment_disp ?>"></p>
    <p>パスワード:<input type = "text" name = "password_post"></p>
    <p><input type = "submit" value = "送信"></p>
    <p>削除番号指定用フォーム:<input type = "text" name = "delete_num"></p>
    <p>パスワード:<input type = "text" name = "password_delete"></p>
    <p><input type = "submit" value = "削除"></p>
    <p>編集番号指定用フォーム:<input type = "text" name = "edit_num"></p>
    <p>パスワード:<input type = "text" name = "password_edit"></p>
    <p><input type = "submit" value = "編集"></p>
    <p><input type = "hidden" value = "<?php echo $edit_num ?>" name = "flag"></p><!-- フラグ用。編集対象番号が表示される。hiddenにしてある -->
  </form>
</body>
</html>
<?php
if(!empty($_POST["name"]) and !empty($_POST["comment"])){ //「送信」ボタンが押され、かつ名前、コメントが埋まっている時
  $name = $_POST["name"];
  $comment = $_POST["comment"];
  $date = date("Y/m/d H:i:s");
  if(!empty($flag_num)){ //編集フラグが立っている場合
    $sql = "update table_ramen set name=:name,comment=:comment,created_at=:created_at where id=:flag_num and password=:password_post";
    //↑idが編集対象番号と一致かつpasswordがパスワードと一致する行の、nameを:nameに、commentを:commentに書き換え
    $stmt = $pdo -> prepare($sql); //
    $stmt -> bindParam(":name",$name,PDO::PARAM_STR); //:nameに投稿者の名前=$nameを参照渡し
    $stmt -> bindParam(":comment",$comment,PDO::PARAM_STR); //:commentに投稿=$commentを参照渡し
    $stmt -> bindParam(":created_at",$date,PDO::PARAM_STR); //:created_atに更新日時=$dateを参照渡し
    $stmt -> bindParam(":flag_num",$flag_num,PDO::PARAM_INT); //:flag_numに編集対象番号=$flag_numを参照渡し
    $stmt -> bindParam(":password_post",$password_post,PDO::PARAM_STR); //:password_postに投稿パスワード=$password_postを参照渡し
    $stmt -> execute(); //$sqlを実行
  }elseif(!empty($password_post)){ //編集フラグが立っていない、通常の投稿(初回も含む)かつ、パスワードが入力されている場合
    $sql = "select count(*) as cnt from table_ramen";
    $stmt = $pdo -> query($sql);
    $row = $stmt -> fetch(PDO::FETCH_ASSOC);
    $count_rows = $row["cnt"];
    if($count_rows == 0){ //もしDBが空の場合
      $sql = "alter table table_ramen AUTO_INCREMENT = 1"; //通し番号を1にリセット
      $stmt = $pdo -> prepare($sql);
      $stmt -> execute();
    }
    $sql = "INSERT INTO table_ramen (name, comment, created_at, password) VALUES (:name, :comment, :created_at, :password_post)";
    //カラムname,comment,created_at,passwordに:name,:comment,created_at, :passwordを挿入
    $stmt = $pdo -> prepare($sql);
    $stmt -> bindParam(":name",$name,PDO::PARAM_STR);
    $stmt -> bindParam(":comment",$comment,PDO::PARAM_STR);
    $stmt -> bindParam(":created_at",$date,PDO::PARAM_STR);
    $stmt -> bindParam(":password_post",$password_post,PDO::PARAM_STR);
    $stmt -> execute();
  }
} elseif(!empty($delete_num)){ //「削除」ボタンが押された場合
    $sql = "delete from table_ramen where id=:delete_num and password=:password_delete";
    //投稿番号idが削除対象番号=:delete_numと等しいかつpasswordが削除パスワード=:password_deleteと等しい場合、投稿を削除
    $stmt = $pdo -> prepare($sql);
    $stmt -> bindParam(":delete_num",$delete_num,PDO::PARAM_INT);
    $stmt -> bindParam(":password_delete",$password_delete,PDO::PARAM_STR);
    $stmt -> execute();
}

//TABLEデータの取得
$sql = "SELECT * FROM table_ramen"; //table_ramenからすべての要素を取得
$stml = $pdo -> query($sql);
$results = $stml -> fetchAll();
foreach($results as $row){
  echo $row["id"]." ".$row["name"]." ".$row["comment"]." ".$row["created_at"];
  echo "<br>";
}
?>
