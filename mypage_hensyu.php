<?php
mb_internal_encoding("utf8");

session_start();


//mypage.phpからの導線以外はlogin_error.phpへリダイレクト
if(!isset($_POST['from_mypage'])) {
    header("Location:http://localhost/login_mypage/login_error.php");
    }

?>

<!doctype html>
<html lang ="ja">
    <head>
    <meta charset="utf-8">
    <title>マイページ登録</title>
    <link rel="stylesheet" type="text/css" href="mypage_hensyu.css">
    </head>
    
    
    <body>
        <header>
            <img src="4eachblog_logo.jpg">
            <div class="logout"><a href="log_out.php">ログアウト</a></div>
        </header>
        
        <main>
        <form action="mypage_update.php" method="post">
            <div class="form_contents">
            <h2>会員情報</h2>
                <div class="hello">
                こんにちは！
                <?php echo $_SESSION['name']; ?> さん
                </div>
                <div class="profile_pic">
                    <img src="<?php echo $_SESSION['picture']; ?>">
                </div>
            
                <div class="basic_info">
                    <p>氏名：<input type="text" size="30" value="<?php echo $_SESSION['name']; ?>" name="name"></p>
                
                    <p>メール：<input type="text" size="30" value="<?php echo $_SESSION['mail']; ?>" name="mail"></p>
                
                    <p>パスワード：<input type="text" size="30" value="<?php echo $_SESSION['password']; ?>" name="password">
                    <input type="hidden" value="<?php echo rand(1,10);?>" name="from_mypage_hensyu">
                    </p>
                
                </div>
                <textarea cols="80" rows="5" name="comments"><?php echo $_SESSION['comments']; ?></textarea>
                
                
                <div class="hensyubutton">
                    <input type="submit" class="submit_button" size="35" value="この内容に変更する">
                </div>
                
            
            </div>
        </form>
        </main>
        
        <footer>
        ©2018 InterNous inc. All rights reserved
        </footer>

        
    </body>



</html>