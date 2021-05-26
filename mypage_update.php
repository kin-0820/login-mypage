<?php
mb_internal_encoding("utf8");

session_start();

//DB接続
try{
    require "DB.php";
    
    $dbconnect = new DB();
    $pdo = $dbconnect->connect();
    } catch(PDOException $e) {
        die("<p>申し訳ございません。現在サーバーが混み合っており一時的にアクセスが出来ません。<br>しばらくたってから再度ログインしてください。</p>
        <a href = 'http://localhost/login_mypage/login.php'>ログイン画面へ</a>"
        );
    }

//プリペアードステートメントでSQLセット //BindValueでパラメーターセット
$stmt = $pdo->prepare($dbconnect->update());

$stmt->bindValue(1,$_POST['name']);
$stmt->bindValue(2,$_POST['mail']);
$stmt->bindValue(3,$_POST['password']);
$stmt->bindValue(4,$_POST['comments']);
$stmt->bindValue(5,$_SESSION['id']);

//executeでクエリ実行
$stmt->execute();

//プリペアードステートメント（更新された情報をDBからSelect文で取得）でSQLセット
$stmt = $pdo->prepare("select * from login_mypage where mail = ? && password = ?");
$stmt->bindValue(1,$_POST["mail"]);
$stmt->bindValue(2,$_POST["password"]);

//executeでクエリ実行
$stmt->execute();

//データベース切断
$pdo = NULL;


//fetch,while文でデータ取得し、Sessionに代入
while($row=$stmt->fetch()){
    $_SESSION['id']=$row['id'];
    $_SESSION['name']=$row['name'];
    $_SESSION['mail']=$row['mail'];
    $_SESSION['password']=$row['password'];
    $_SESSION['picture']=$row['picture'];
    $_SESSION['comments']=$row['comments'];
}
//mypage.phpへリダイレクト
    header("Location:http://localhost/login_mypage/mypage.php");
?>