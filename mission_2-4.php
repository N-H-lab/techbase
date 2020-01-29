<DOCTYPE html>
<html lang = "ja">
<head>
<meta charset = "UTF-8">
<title>入力フォーム</title>
</head>
<body>
<h1>入力フォーム</h1>
<form action = "mission_2-4.php" method = "post">
<input type = "text" name = "comment">
<input type = "submit" value = "送信">
</form>
</body>
</html>
<?php
$filename = "mission_2-4.txt"; //テキストファイル名を指定
if(!empty($_POST["comment"])){ //コメントフォームが埋まっている場合
  $comment = $_POST["comment"]; //コメントを取得
  $fp = fopen($filename,"a");
  fwrite($fp,$comment."\n"); //新規投稿を改行コードとともに追加書き込み
  fclose($fp);
}
if(file_exists($filename)){
  $Contents = file($filename); //投稿を配列として取得
  foreach($Contents as $value){ //それぞれの投稿について
    echo $value."<br>"; //投稿を改行して表示
  }
}
?>
