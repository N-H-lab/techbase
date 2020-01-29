<DOCTYPE html>
<html lang = "ja">
<head>
<meta charset = "UTF-8">
<title>入力フォーム</title>
</head>
<body>
<form type = "text" method = "post">
<p>
名前: <input type = "text" name = "name">
</p>
<p>
コメント: <input type = "text" name = "comment">
</p>
<p>
<input type = "submit" value = "送信">
</p>
</form>
</body>
</html>
<?php
$date = date("Y/m/d H:i:s"); //日付を取得
$filename = "mission_3-2.txt"; //テキストファイル名を指定
if(!empty($_POST["name"]) and !empty($_POST["comment"])){ //名前フォームとコメントフォームの両方が埋まっている場合
	$post_num = 1;
	$name = $_POST["name"]; //名前を取得
	$comment = $_POST["comment"]; //コメントを取得
	if(file_exists($filename)){ //テキストファイルが存在する場合
		$Contents = file($filename);  //テキストファイルの中身を配列として取得
		$Last_Line = end($Contents);  //テキストファイルの最後の行=最後の投稿を取得
		$splited_Last_Line = explode("<>",$Last_Line);  //最後の行を"<>"で分割する
		$post_num_last = current($splited_Last_Line);  //currentは配列の最初の要素を返す関数。最後の投稿の投稿番号を読み込む。
		$post_num = $post_num_last + 1;  //現在の投稿番号=最後の投稿番号+1
		//echo $post_num; //デバッグ用
		$Contents = $post_num."<>".$name."<>".$comment."<>".$date;  //文字列を結合
		$fp = fopen($filename,"a");
		fwrite($fp,$Contents."\n"); //新規投稿を改行コードとともに追加書き込み
		fclose($fp);
	} else {  //最初の投稿の際の処理
		$Contents = $post_num."<>".$name."<>".$comment."<>".$date;
		$fp = fopen($filename,"a");
		fwrite($fp,$Contents."\n"); //新規投稿を改行コードとともに追加書き込み
		fclose($fp);
	}
}
$Contents = file($filename);  //再びファイルを開き、配列として受け取る
foreach($Contents as $value){ //それぞれの投稿について
  $splited_post = explode("<>",$value);  //1行ごとに読み込み、<>で区切って配列にする
  foreach($splited_post as $element){ //投稿のそれぞれの要素について
    echo $element."";  //区切った要素ごとに表示、スペースで区切る
  }
  echo "<br>";  //投稿ごとに改行して表示する
}
?>
