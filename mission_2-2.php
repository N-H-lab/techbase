<DOCTYPE html>
<html lang = "ja">
<head>
<meta charset = "UTF-8">
<title>入力フォーム</title>
</head>
<body>
<h1>入力フォーム</h1>
<form action = "mission_2-2.php" method = "post">
<input type = "text" name = "comment" value = "コメント">
<input type =  "submit" value = "送信">
</form>
</body>
</html>
 <?php
$filename = "mission_2-2.txt";  //ファイル名
if(!empty($_POST["comment"])) { //コメントが空白でない時
 	$comment = $_POST["comment"];  //フォームの内容を取得
 	$fp = fopen($filename,"w");  //書き込み専用(上書き)でファイルを開く
 	fwrite($fp,$comment);  //取得したコメントをファイルに書き込み
 	fclose($fp);  //ファイルを閉じる
 	$content = file_get_contents($filename);  //テキストファイルの中身を読み込み
  if($content == "完成!"){  //ファイルの中身が「完成!」の時
    echo "おめでとう!";    //おめでとう!を表示
  }else{  //「完成!」以外の文字列の時、そのまま表示
    echo $content;
  }
}
?>
