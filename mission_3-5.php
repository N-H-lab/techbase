<?php
$filename = "mission_3-5.txt"; //ここで宣言しておく
$name_disp = ""; //フォームに表示する名前
$comment_disp = "";  //フォームに表示するコメント
$edit_num = "";
$flag_num = "";
if(!empty($_POST["flag"])){
  $flag_num = $_POST["flag"];
}
//パスワードを最初にまとめて取得
$password_post = "";
$password_delete = "";
$password_edit = "";
if(!empty($_POST["password_post"])){
  $password_post = $_POST["password_post"];
}
if(!empty($_POST["password_delete"])){
  $password_delete = $_POST["password_delete"];
}
if(!empty($_POST["password_edit"])){
  $password_edit = $_POST["password_edit"];
}
if(!empty($_POST["edit_num"])){ //編集ボタンが押された時
  $edit_num = $_POST["edit_num"]; //編集対象番号を取得
  //echo $edit_num."番の投稿を編集します"."<br>";
  if(!empty(file($filename))){
    $Contents = file($filename);
    foreach($Contents as $value){
      $splited_post = explode("<>",$value);
      $post_num = current($splited_post);
      $password_edit_correct = $splited_post[4];
      if($post_num == $edit_num and $password_edit_correct == $password_edit){ //投稿番号と編集対象番号が等しいかつパスワードが等しい時
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
  <form action = "mission_3-5.php" method = "post">
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
  $post_num = 1;
  $name = $_POST["name"];
  $comment = $_POST["comment"];
  $date = date("Y/m/d H:i:s");
  if(!empty(file($filename))){ //最初の投稿ではない=テキストファイルが空でないとき
    if(!empty($flag_num)){ //編集フラグが立っている場合
      //echo "編集モードです"."<br>";
      $Contents = file($filename);
      $fp = fopen($filename,"w");
      foreach($Contents as $value){
        $splited_post = explode("<>",$value);
        $post_num = current($splited_post);
        $password_edit_correct =$splited_post[4];
        if($post_num == $flag_num and $password_edit_correct == $password_post){ //編集する投稿の番号と投稿番号が等しいかつ、パスワードも一致した場合
          $edited_post = $post_num."<>".$name."<>".$comment."<>".$date."<>".$password_post."<>"."\n";  //編集データに差し替えて保存。file関数で開いた$valueと違い、末尾に改行コードを手動でつけないといけない
          //var_dump($edited_post);
          //echo "<br>";
          fwrite($fp,$edited_post);
        } else { //編集対象でないか、パスワードが違う時
          fwrite($fp,$value);
        }
      }
      fclose($fp);
  } elseif(!empty($password_post)){ //編集フラグが立っていない、通常の新規投稿かつ、パスワードが入力されている場合
      //echo "新規投稿モードです"."<br>";
      $Contents = file($filename);  //テキストファイルの中身、配列として
      $Last_Line = end($Contents);  //テキストファイルの最後の行=最後の投稿
      $splited_Last_Line = explode("<>",$Last_Line);  //最後の行を"<>"で分割する
      $post_num_last = current($splited_Last_Line);  //currentは配列の最初の要素を返す関数。最後の投稿の投稿番号を読み込む。
      $post_num = $post_num_last + 1;  //現在の投稿番号=最後の投稿番号+1
      //echo $post_num; //デバッグ用
      $Contents = $post_num."<>".$name."<>".$comment."<>".$date."<>".$password_post."<>";  //文字列を結合
      $fp = fopen($filename,"a");
      fwrite($fp,$Contents."\n");
      fclose($fp);
   }
 } else { //テキストボックスが空、つまり最初の投稿の時
   if(!empty($password_post)){
     $Contents = $post_num."<>".$name."<>".$comment."<>".$date."<>".$password_post."<>";  //文字列を結合
     $fp = fopen($filename,"w");
     fwrite($fp,$Contents."\n");
     fclose($fp);
   }
 }
} elseif(!empty($_POST["delete_num"])){ //「削除」ボタンが押された場合
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
      $password_delete_correct = $splited_post[4];
      if($post_num == $delete_num and $password_delete_correct == $password_delete){ //削除する投稿の番号と投稿番号が等しいかつパスワードも一致する時だけその行=投稿を配列に加えない
        continue; //次の投稿に進む
      } else { //それ以外の時、投稿をそのまま配列に加える
        //echo "value=".$value;
        fwrite($fp,$value);
      }
    }
    fclose($fp);
}
if(file_exists($filename)){
  $disp_Contents = array_filter(file($filename),"strlen");  //再びファイルを開き、配列として受け取る。array_filterで空白文字を除去
  foreach($disp_Contents as $value){
   $splited_post = explode("<>",$value);  //1行ごとに読み込み、<>で区切って配列にする
   $n =0;
    foreach($splited_post as $element){
      $n++;
      if($n <= 4){ //foreachの回った数が4以下、つまりパスワードより前の要素の時
        echo $element."\n";  //区切った要素ごとに表示、コンマで区切る
      }
    }
   echo "<br>";  //投稿ごとに改行して表示するため
  }
}
?>
