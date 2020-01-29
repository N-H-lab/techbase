<?php
//4-1:データベースへの接続を行う
$dsn = "mysql:dbname='データベース名';host=localhost";
//dbnameを間違えると、No database selected となる。また$dsn内は空白を使えない。
$user = "ユーザー名";
$password = "パスワード";
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
/*
PDO:PHP Data Objectsの略。どのデータベースを使っているか隠蔽してくれる。どのデータベースにも使える。他のデータベースに適用したい場合はパラメータだけ変更すれば良い。
初期化されたクラス「PDO」のオブジェクトが$pdoに収納される。newはクラスを初期化するメソッド。
PDO(接続情報,ユーザー名,パスワード)
  接続情報:使用するドライバ、データベース名、文字コード、ホスト
arrayはデータベース操作で発生したエラーを警告として表示してくれる要素。
*/
echo "conneted successfully"."<br>";

//4-2:データベース内にcreateコマンドでテーブルを作成する。
$sql = "CREATE TABLE IF NOT EXISTS tbtest" //すでにテーブルが存在している時警告が発生するのを防ぐ。tbtestはデータベース名
  ."("
	. "id INT AUTO_INCREMENT PRIMARY KEY," //プライマリーキーはデータと一対一対応の数字。
	. "name char(32),"
	. "comment TEXT"
	.");";
/*
SQL...データベースへ指示を出すための言語。
CREATE TABLE...新規テーブルを作成するためのSQL文。
CREATE TABLE [データベース名].[テーブル名](
  [カラム名1] [データ型1] [オプション],
  [カラム名2] [データ型2] [オプション],
  [カラム名3] [データ型3] [オプション],
  ...);
※データベースが既に指定されている場合はテーブル名だけでオッケー
SQLのデータ型
・character(n),char(n):n文字分の文字列を収納できる。n文字を超える場合はエラーが出る
  長さが指定されないcharacter = character(1)
・character varying(n),varchar(n):n文字分の文字列を格納できる
・text:文字数制限なしの文字列を格納できる。

*/
$stmt = $pdo->query($sql);
/*
$pdoからquery()メソッドを呼び出す。
query()はSQL文を実行するためのメソッド。SQL文を即時実行、一回だけ実行するならquery()を使うといい。
*/
echo "created successfully"."<br>";

//4-3:テーブル一覧を表示するコマンドを使って作成ができたか確認する。
$sql = "SHOW TABLES";
$result = $pdo -> query($sql);
foreach($result as $row){
  echo $row[0];
  echo "<br>";
}
echo "<hr>";
/*
SHOW TABLES...作成済みのテーブル一覧を表示する
*/

//4-4テーブルの中身を確認するコマンドを使って、意図した内容のテーブルが作成されているか確認する。
$sql = "SHOW CREATE TABLE tbtest";
$result = $pdo -> query($sql);
foreach($result as $row){
  echo $row[1];
}
echo "<hr>";
/*
SHOW CREATE TABLE...指定したテーブル名を作成するのと同じテーブルを作成するためのCREATE TABLE文を表示するための方法。
デフォルトのストレージエンジンや文字セットなどの情報も表示される。
*/

//4-5:作成したテーブルに、insertを行ってデータを入力する。
$sql = $pdo -> prepare("INSERT INTO tbtest (name, comment) VALUES (:name, :comment)");
//nameカラムとcommentカラムに値を追加。指定するカラムの数とVALUESの数は当然一致しなきゃだめ。
$sql -> bindParam(":name",$name,PDO::PARAM_STR);
$sql -> bindParam(":comment",$comment,PDO::PARAM_STR);
$name = "N.H";
$comment = "こんにちは";
$sql -> execute();
/*
prepare()...SQL文を実行するためのメソッド。query()と違い、同じ処理で値が違うものを何度も繰り返すときに便利。クエリの解析、コンパイルなどにかかる時間も最初の一回で済む。
prepare(string,statement [, array driver_options])
引数のSQL文部分のパラメータ指定部分には、「:名前」か「?」のパラメータマークを書く。またパラメータを自動で引用符でくくってくれる。

bindParam()...prepare()で指定したパラメータに値をセットするメソッド。
bindParam("パラメータマーク",変数,オプション？)
bindParam()では第2引数に値ではなく変数を指定する。その変数は参照渡しされるので、以降変数を更新すれば値も変わる。
execute()で評価した時点での変数が表示されることになる。
*/

//4-6:入力したデータをselectによって表示する
$sql = "SELECT * FROM tbtest";
$stml = $pdo -> query($sql);
$results = $stml -> fetchAll();
foreach($results as $row){
  echo $row["id"]." ";
  echo $row["name"]." ";
  echo $row["comment"]."<br>";
  echo "<hr>";
}
/*
SELECT [取得したい要素] FROM [使用テーブル] WHERE [条件]...データベースからデータを取得する。
[取得したい要素]:特定の要素(name、levelなど)を指定できる。
  *...全部指定

fetchAll()...すべての結果行を含む配列を返す。
*/

//4-7:入力したデータをupdateによって編集する。編集できているかはselectで確認すること。
$id = 3;
$name = "yzk";
$comment = "むなむな";
$sql = "update tbtest set name=:name,comment=:comment where id=:id";
$stmt = $pdo -> prepare($sql);
$stmt -> bindParam(":name",$name,PDO::PARAM_STR);
$stmt -> bindParam(":comment",$comment,PDO::PARAM_STR);
$stmt -> bindParam(":id",$id,PDO::PARAM_INT);
$stmt -> execute();
/*
UPDATE [テーブル名] SET [更新処理]...データベースのデータを更新する。
[更新処理]には計算式を入れることもできる。
*/

//4-6:入力したデータをselectによって表示する
$sql = "SELECT * FROM tbtest";
$stml = $pdo -> query($sql);
$results = $stml -> fetchAll();
foreach($results as $row){
  echo $row["id"]." ";
  echo $row["name"]." ";
  echo $row["comment"]."<br>";
echo "<hr>";
}

//4-8:入力したデータをdeleteによって削除する。削除できているかはselectで確認すること。
$id = 3;
$sql = "delete from tbtest where id=:id";
$stmt = $pdo -> prepare($sql);
$stmt -> bindParam(":id",$id,PDO::PARAM_INT);
$stmt -> execute();
/*
DELETE FROM [テーブル名] WHERE [条件] LIMIT [制限回数]...レコードを削除する。
安全に使うために
  ・先に同じ条件でSELECTして条件に問題がないか確認する。
  ・条件を厳し目にする(id = "1" → id = "1" AND name = "山田")
  ・LIMITで制限回数を設ける(一行だけ削除する時はLIMIT 1)
*/

//4-6:入力したデータをselectによって表示する
$sql = "SELECT * FROM tbtest";
$stml = $pdo -> query($sql);
$results = $stml -> fetchAll();
foreach($results as $row){
  echo $row["id"]." ";
  echo $row["name"]." ";
  echo $row["comment"]."<br>";
echo "<hr>";
}
?>
