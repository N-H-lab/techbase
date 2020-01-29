<DOCTYPE html>
<html lang = "ja">
<head>
  <meta charset="utf-8">
  <title>入力フォーム</title>
</head>
<body>
  <form action = "mission_3-3.php" method = "post">
    <p>名前:<input type = "text" name = "name"></p>
    <p>コメント:<input type = "text" name = "comment"></p>
    <p><input type = "submit" value = "送信"></p>
    <p>削除番号指定用フォーム:<input type = "text" name = "delete_num"></p>
    <p><input type = "submit" value = "削除"></p>
  </form>
</body>
</html>
<?php
$filename = "mission_3-3.txt";
if(!empty($_POST["name"]) and !empty($_POST["comment"])){ //「送信」ボタンが押された場合
  $post_num = 1;
  $name = $_POST["name"];
  $comment = $_POST["comment"];
  $date = date("Y/m/d H:i:s");
  if(file_exists($filename)){ //テキストファイルが存在する場合
    $Contents = file($filename);  //テキストファイルの中身を配列として取得
    $Last_Line = end($Contents);  //テキストファイルの最後の行=最後の投稿を取得
    $splited_Last_Line = explode("<>",$Last_Line);  //最後の行を"<>"で分割し配列として取得
    $post_num_last = current($splited_Last_Line);  //currentは配列の最初の要素を返す関数。最後の投稿の投稿番号を読み込む。
    $post_num = $post_num_last + 1;  //現在の投稿番号=最後の投稿番号+1
    //echo $post_num; //デバッグ用
    $Contents = $post_num."<>".$name."<>".$comment."<>".$date;  //文字列を結合し新規投稿とする
    $fp = fopen($filename,"a");
    fwrite($fp,$Contents."\n"); //新規投稿を改行コードとともに追加書き込み
    fclose($fp);
  } else { //最初の投稿の時=テキストファイルが空のとき、最初の投稿をファイルに書き込み
    $Contents = $post_num."<>".$name."<>".$comment."<>".$date;  //文字列を結合し新規投稿として取得
    $fp = fopen($filename,"a");
    fwrite($fp,$Contents."\n"); //新規投稿を改行コードとともに追加書き込み
    fclose($fp);
  }
} elseif(!empty($_POST["delete_num"])){ //「削除」ボタンが押された場合
    $delete_num = $_POST["delete_num"]; //削除する投稿の番号
    if(file_exists($filename)){ //テキストファイルが存在する場合
      $Contents = file($filename); //ファイルの中身を配列として取得
      $fp = fopen($filename,"w"); //ここでファイルを上書き書き込みで開いておく
      foreach($Contents as $value){ //各投稿について
        $splited_post = explode("<>",$value); //一つの投稿を"<>"で区切って分割
        $post_num = current($splited_post); //一番目の要素=投稿番号を取得
        if($post_num != $delete_num){ //削除する投稿の番号と投稿番号が等しくないときだけその行=投稿を保存する
          fwrite($fp,$value);
        } else { //削除対象の時は配列に追加せず次のループに飛ぶ
          continue;
        }
      }
      fclose($fp);
    }
}
if(file_exists($filename)){ //テキストファイルが存在する場合
  $Contents = file($filename);  //再びファイルを開き、配列として受け取る
  foreach($Contents as $value){ //各投稿について
    $splited_post = explode("<>",$value);  //1行ごとに読み込み、<>で区切って配列にする
    foreach($splited_post as $element){ //それぞれの要　素について
      echo $element." ";  //区切った要素ごとに表示、コンマで区切る
    }
    echo "<br>";  //投稿ごとに改行して表示する
  }
}
?>
