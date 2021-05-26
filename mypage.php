<?php
mb_internal_encoding("utf8");
session_start();

if(empty($_SESSION['id'])){
    try{
        require "DB.php";
        $dbconnect = new DB();
        $pdo = $dbconnect->connect();
    } catch(PDOException $e) {
        die("<p>申し訳ございません。現在サーバーが混み合っており一時的にアクセスが出来ません。<br>しばらくたってから再度ログインしてください。</p>
        <a href='http://localhost/login_mypage/login.php'>ログイン画面へ</a>"
        );
    }

//プリペアードステートメントでSQL文（DBとPOSTデータ照合、SelectとWhere）
    $stmt = $pdo -> prepare($dbconnect->select());

//bindvalueメソッドでパラメータセット
    $stmt -> bindValue(1,$_POST["mail"]);
    $stmt -> bindValue(2,$_POST["password"]);

//executeでクエリ
    $stmt -> execute();
    
    $pdo = NULL;

//fetch,whileでデータ消去、session代入
    while ($row=$stmt->fetch()){
        $_SESSION['id']=$row['id'];
        $_SESSION['name']=$row['name'];
        $_SESSION['mail']=$row['mail'];
        $_SESSION['password']=$row['password'];
        $_SESSION['picture']=$row['picture'];
        $_SESSION['comments']=$row['comments'];
    }


//データ取得できずに（Empty使用して判定）Sessionがなければ、リダイレクト(エラー画面へ）
    if(empty($_session['id'])) {
        header("Location:login_error.php");
    }
   
    if(!empty($_POST['login_keep'])) {
        $_SESSION['login_keep']=$_POST['login_keep'];
    }

}

if(!empty($_SESSION['id']) && !empty($_SESSION['login_keep'])) {
    setcookie('mail',$_SESSION['mail'],time() + 60*60*24*7);
    setcookie('password',$_SESSION['password'],time() + 60*60*24*7);
    setcookie('login_keep',$_SESSION['login_keep'],time() + 60*60*24*7);
} else if(empty($SESSION['login_keep'])){
    setcookie('mail','',time()-1);
    setcookie('password','',time()-1);
    setcookie('login_keep','',time()-1);
}
   
?>


<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>マイページ</title>
    <link rel="stylesheet" type="text/css" href="mypage.css">
</head>
    
<body>
    <header>
        <img src="4eachblog_logo.jpg">
        <div class="logout"><a href="log_out.php">ログアウト</a></div>
    </header>
       
    <main>
        <div class="mypage">
        <div class="mypage_contents">
        <h2>会員情報</h2>
            <div class="hello">
                こんにちは！
                <?php echo $_SESSION['name']; ?> さん
            </div>
            
            <div class="profile_pic">
                <img src="<?php echo $_SESSION['picture']; ?>">
            </div>
            <div class="basic_info">
                <p>氏名:<?php echo $_SESSION['name']; ?></p>
                <p>メール:<?php echo $_SESSION['mail']; ?></p>   
                <p>パスワード:<?php echo $_SESSION['password']; ?></p>
            </div>
                
            <div class="comments">
                <?php echo $_SESSION['comments']; ?>
            </div>
            <form action="mypage_hensyu.php" method="post" class="form_center">
                <input type="hidden" value="<?php echo rand(1,10);?>" name="from_mypage">
                <div class="hensyubutton">
                    <input type="submit" class="submit_button" size="35" value="編集する">
                </div>
            </form>
            
            </div>
        </div>
    </main>
    
     <footer>
        ©2018 InterNous inc. All rights reserved
    </footer>

    </body>
</html>

