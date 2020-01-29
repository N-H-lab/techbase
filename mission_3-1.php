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
$filename = "mission_3-1.txt"; //テキストファイル名を指定
$date = date("Y/m/d H:i:s"); //日付を取得
if(!empty($_POST["name"]) and !empty($_POST["comment"])){ //名前フォームとコメントフォームの両方が埋まっている場合
	$postnumber = 1;
	$name = $_POST["name"]; //名前を取得
	$comment = $_POST["comment"]; //コメントを取得
	if(file_exists($filename)){ //テキストファイルが存在する場合
		$contents = file($filename);  //テキストファイルの中身を配列として取得
		$last_post = end($contents);  //テキストファイルの最後の行=最後の投稿を取得
		$splited_last_post = explode("<>",$last_post);  //最後の行を"<>"で分割する
		$postnumber_last = current($splited_last_post);  //currentは配列の最初の要素を返す関数。最後の投稿の投稿番号を読み込む。
		$postnumber = $postnumber_last + 1;  //現在の投稿番号=最後の投稿番号+1
		//echo $postnumber; //デバッグ用
		$contents = $postnumber."<>".$name."<>".$comment."<>".$date;  //文字列を結合して新規投稿とする
		$fp = fopen($filename,"a");
		fwrite($fp,$contents."\n"); //新規投稿を改行コードとともに追加書き込み
		fclose($fp);
	} else {  //最初の投稿の際の処理
		$contents = $postnumber."<>".$name."<>".$comment."<>".$date;
		$fp = fopen($filename,"a");
		fwrite($fp,$contents."\n"); //新規投稿を改行コードとともに追加書き込み
		fclose($fp);
	}
}
?>
