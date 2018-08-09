<?php
    // サインイン処理
// DB接続　外部ファイルから読み込み

require('dbconnect.php');

   // 初期化
    $errors = [];
if (!empty($_POST)) {
        // ①
        $email = $_POST['input_email'];
        $password = $_POST['input_password'];

        // if ($email != '' && $password != '') {
         if($email == '' || $password ==''){
            $errors['signin'] = 'blank';
        }else {
              // データベースとの照合処理
          $sql = 'SELECT * FROM `users` WHERE `email` =?';
          $data =[$email];
          $stmt = $dbh->prepare($sql);
          $stmt->execute($data);

          // データを配列として格納
          $record = $stmt->fetch(PDO::FETCH_ASSOC);
            // 一件も取得できなかったとき
          if ($record == false) {
            // 認証失敗（DBにemailが見つからなかった）
            $errors['signin']='failed';
            }else{
              // emailは登録されていた
              if(password_verify($password,$record['password'])){
                //認証成功
              }else{
                // 認証失敗
                $errors['signin'] = 'failed';
              }
            }

        }
    }
// password aaaaaaaa
    // var_dump($errors);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Learn SNS</title>
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body style="margin-top: 60px">
  <div class="container">
    <div class="row">
      <div class="col-xs-8 col-xs-offset-2 thumbnail">
        <h2 class="text-center content_header">サインイン</h2>
        <form method="POST" action="" enctype="multipart/form-data">
          <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com">
          </div>
            <div class="form-group">
              <label for="password">パスワード</label>
              <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード">
            </div>
          <?php if(isset($errors['signin']) && $errors['signin'] == 'blank'): ?>
              <p class="text-danger">メールアドレスとパスワードを正しく入力してください</p>
            <?php endif; ?>

            <?php if(isset($errors['signin']) && $errors['signin'] == 'failed'): ?>
              <p class="text-danger">サインインに失敗しました</p>
            <?php endif; ?>

          <input type="submit" class="btn btn-info" value="サインイン">


        </form>
      </div>
    </div>
  </div>



  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>


