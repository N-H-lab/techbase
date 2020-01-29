<DOCTYPE html>
<html lang = "ja">
<head>
<meta charset = "UTF-8">
<title>入力フォーム</title>
</head>
<body>
<h1>入力フォーム</h1>
<form action = "mission_2-3.php" method = "post">
<input type = "text" name = "comment" value = "コメント">
<input type = "submit" value = "送信">
</form>
</body>
</html>
<?php
$filename = "mission_2-3.txt";
if(!empty($_POST["comment"])){ //コメントフォームが埋まっている場合
	$comment = $_POST["comment"]; //コメントを取得
	$fp = fopen($filename,"a");
	fwrite($fp,$comment."\n");  //改行コードを最後に付け加える。シングルクオーテーションの場合、そのまま\nと表示されてしまうので注意。
	fclose($fp);
}
?>
