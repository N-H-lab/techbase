<?php
$filename = "mission_3-4.txt"; //ここで宣言しておく
$name_disp = ""; //フォームに表示する名前
$comment_disp = "";  //フォームに表示するコメント
$edit_num = "";
$flag_num = "";
if(!empty($_POST["flag"])){
  $flag_num = $_POST["flag"];
}
if(!empty($_POST["edit_num"])){ //編集ボタンが押された時
  $edit_num = $_POST["edit_num"]; //編集対象番号を取得
  //echo $edit_num."番の投稿を編集します"."<br>";
  if(file_exists($filename)){
    $Contents = file($filename);
    foreach($Contents as $value){
      $splited_post = explode("<>",$value);
      $post_num = current($splited_post);
      if($post_num == $edit_num){ //投稿番号と編集対象番号が等しい時
        $name_disp = $splited_post[1]; //フォームに表示する名前
        $comment_disp = $splited_post[2]; //フォームに表示するコメント
      }
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
  <form action = "mission_3-4.php" method = "post">
    <p>名前:<input type = "text" name = "name" value = "<?php echo $name_disp ?>"></p>
    <p>コメント:<input type = "text" name = "comment" value = "<?php echo $comment_disp ?>"></p>
    <p><input type = "submit" value = "送信"></p>
    <p>削除番号指定用フォーム:<input type = "text" name = "delete_num"></p>
    <p><input type = "submit" value = "削除"></p>
    <p>編集番号指定用フォーム:<input type = "text" name = "edit_num"></p>
    <p><input type = "submit" value = "編集"></p>
    <p><input type = "hidden" value = "<?php echo $edit_num ?>" name = "flag"></p><!-- フラグ用。編集対象番号が表示される -->
  </form>
</body>
</html>
<?php
if(!empty($_POST["name"]) and !empty($_POST["comment"])){ //「送信」ボタンが押された場合
  $post_num = 1;
  $name = $_POST["name"];
  $comment = $_POST["comment"];
  $date = date("Y/m/d H:i:s");
  if(file_exists($filename)){ //最初の投稿ではない=テキストファイルが空でないとき
    if(!empty($flag_num)){ //編集フラグが立っている場合
      //echo "編集モードです"."<br>";
      $Contents = file($filename);
      $fp = fopen($filename,"w");
      foreach($Contents as $value){
        $splited_post = explode("<>",$value);
        $post_num = current($splited_post);
        if($post_num == $flag_num){ //編集する投稿の番号と投稿番号が等しい時
          $edited_post = $post_num."<>".$name."<>".$comment."<>".$date."\n";  //編集データに差し替えて保存。file関数で開いた$valueと違い、末尾に改行コードを手動でつけないといけない
          fwrite($fp,$edited_post);
        } else { //編集対象でない時
          fwrite($fp,$value); //配列に編集対象でない投稿を格納。なお$valueはfile関数で開いた配列$Contentsの要素なので、末尾に改行コードが自動でつけられる。
        }
      }
      fclose($fp);
      //var_dump($array_edit);
    }else{ //編集フラグが立っていない、通常の新規投稿の場合
      //echo "新規投稿モードです"."<br>";
      $Contents = file($filename);  //テキストファイルの中身、配列として
      $Last_Line = end($Contents);  //テキストファイルの最後の行=最後の投稿
      $splited_Last_Line = explode("<>",$Last_Line);  //最後の行を"<>"で分割する
      $post_num_last = current($splited_Last_Line);  //currentは配列の最初の要素を返す関数。最後の投稿の投稿番号を読み込む。
      $post_num = $post_num_last + 1;  //現在の投稿番号=最後の投稿番号+1
      //echo $post_num; //デバッグ用
      $Contents = $post_num."<>".$name."<>".$comment."<>".$date;  //文字列を結合
      $fp = fopen($filename,"a");
      fwrite($fp,$Contents."\n");
      fclose($fp);
   }
 }else{ //テキストボックスが空、つまり最初の投稿の時
   $Contents = $post_num."<>".$name."<>".$comment."<>".$date;  //文字列を結合
   $fp = fopen($filename,"w");
   fwrite($fp,$Contents."\n");
   fclose($fp);
 }
}elseif(!empty($_POST["delete_num"])){ //「削除」ボタンが押された場合
    $delete_num = $_POST["delete_num"]; //削除する投稿の番号
    //echo "削除番号=".$delete_num."<br>";
    $Contents = file($filename);
    $fp = fopen($filename,"w");
    foreach($Contents as $value){
      $splited_post = explode("<>",$value);
      //var_dump($splited_post);
      //echo "<br>";
      $post_num = current($splited_post);
      //echo "post_num=".$post_num."<br>";
      if($post_num != $delete_num){ //削除する投稿の番号と投稿番号が等しくないときだけその行=投稿を配列に加える
        //echo "value=".$value;
        fwrite($fp,$value);
      }else{
        continue; //次の投稿に進む
      }
    }
    fclose($fp);
}

if(file_exists($filename)){
  $disp_Contents = file($filename);  //再びファイルを開き、配列として受け取る。array_filterで空白文字を除去
  foreach($disp_Contents as $value){
   $splited_post = explode("<>",$value);  //1行ごとに読み込み、<>で区切って配列にする
    foreach($splited_post as $element){
      echo $element."\n";  //区切った要素ごとに表示、コンマで区切る
      }
    echo "<br>";  //投稿ごとに改行して表示するため
  }
}
?>
