<DOCTYPE html>
<html lang = "ja">
<head>
<meta charset = "UTF-8">
<title>入力フォーム</title>
</head>
<body>
<h1>入力フォーム</h1>
<form action = "mission_2-1.php" method ="post">
<!--
actionで送信先のファイルを指定、methodは"get"(urlに情報を含めて送信する)か"post"(urlではなくbodyに含めて送信する)
-->
<input type = "text" value = "コメント" name = "comment">
<!--
nameで名前を指定して、phpのスーパーグローバル変数$_POST[]であとで呼び出せるようにしておく
-->
<input type = "submit" value = "送信">
<!-- 送信ボタン -->
</form>
</body>
</html>
<?php
if(!empty($_POST["comment"])){//初回アクセス時、$_POST["comment"]が未定義の状態で「Notice: Undefined index」エラーが発生するのを防ぐために、!emptyで中身が空でないことを確認する。
  $fixed_phrase = "を受け付けました";//受け取ったコメントの末尾につける定型文
  $comment = $_POST["comment"];//$_GETもあるが、<form>で指定したmethodと一致させないとエラーが出る
  echo $comment.$fixed_phrase;
}
?>
