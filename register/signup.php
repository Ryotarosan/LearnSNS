<?php
    session_start(); //セッション変数が使用可能
// エラー情報格納用変数
    $errors = array();


//書き直しボタンが押されて戻ってきたとき。
if (isset($_GET['action']) && $_GET['action'] == 'rewrite') {
  $_POST['input_name']=$_SESSION['register']['name']; 
  $_POST['input_email']=$_SESSION['register']['email'];
  $_POST['input_password']=$_SESSION['register']['password'];
  
// 書き直しのときにcheck.phpにリダイレクトされないように適当なのいれとく（trueじゃなくても何でも良い）
  $error['rewrite'] =true;
}

$name="";
$email="";


    //確認ボタンが押されたとき
     if (!empty($_POST)) {
        $name = $_POST['input_name'];
        $email = $_POST['input_email'];
        $password = $_POST['input_password'];


        // ユーザー名の空チェック
        if ($name == '') {
            $errors['name'] = 'blank';
        }
         // メールアドレスの空チェック
        if ($email == '') {
            $errors['email'] = 'blank';
        }

        $count = strlen($password); // hogehogeとパスワードを入力した場合、8が$countに代入される
        // パスワードの空チェック

        if ($password == '') {
            $errors['password'] = 'blank';
        }elseif ($count < 4 || 16 < $count) { // ||演算子を使って4文字未満または16文字より多き場合にエラー配列にlengthを代入
            $errors['password'] = 'length';
        }
    // var_dump($_FILES['input_img_name']['name']); // 画像名を取得
    // var_dump($_FILES['input_img_name']['tmp_name']); // 送信された画像データそのものを取得
    // 画像名を取得
        $file_name ='';
        if (!isset($_GET['action'])) {
        $file_name = $_FILES['input_img_name']['name'];
        }
        if (!empty($file_name)) {
            // 拡張子チェックの処理
          $file_type = substr($file_name, -4);
          $file_type = strtolower($file_type);
        if ($file_type != '.jpg' && $file_type != '.png' && $file_type != '.gif' && $file_type != 'jpeg') {
                $errors['img_name'] = 'type';
            }

        } else {
            $errors['img_name'] = 'blank';
        }
        // エラーがなかったときの処理
    if (empty($errors)) {
      //１．画像のアップデート
        $date_str = date('YmdHis');
        $submit_file_name = $date_str . $file_name;
        //アップロード済のtmpフォルダに入ってるファイルを、決めた名前で決めた場所に移動する。
        move_uploaded_file($_FILES['input_img_name']['tmp_name'], '../user_profile_img/' . $submit_file_name);
    
    //セッションへ送信データの保存（別な画面でも入力されたデータを扱いたいから。）
        $_SESSION['register']['name'] = $name;
        $_SESSION['register']['email'] = $email;
        $_SESSION['register']['password'] = $password ;
        $_SESSION['register']['img_name'] = $submit_file_name ;
    //check.phpへ移動
        header("Location: check.php");
        exit();//ここで終わり
      }


    }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Learn SNS</title>
  <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="../assets/font-awesome/css/font-awesome.css">
   <link rel="stylesheet" type="text/css" href="../assets/css/style.css"> <!-- 追加 -->

</head>
<body style="margin-top: 60px">
  <div class="container">
    <div class="row">
      <!-- ここにコンテンツ -->
      <div class="col-xs-8 col-xs-offset-2 thumbnail">
        <h2 class="text-center content_header">アカウント作成</h2>
        <form method="POST" action="signup.php" enctype="multipart/form-data">
          <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="input_name" class="form-control" id="name" placeholder="山田 太郎" value="<?php echo $name;?>">
            <!-- isset(変数)指定した変数が存在するかチェック　存在する=true 存在しない=false -->
            <?php if(isset($errors['name']) && $errors['name'] == 'blank') { ?>
              <p class="text-danger">ユーザー名を入力してください</p>
            <?php } ?>

          </div>
          <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com" value="<?php echo $email;?>">
            <?php if(isset($errors['email']) && $errors['email'] == 'blank') { ?>
              <p class="text-danger">メールアドレスを入力してください</p>
            <?php } ?>

          </div>
          <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード">
            <?php if(isset($errors['password']) && $errors['password'] == 'blank') { ?>
              <p class="text-danger">パスワードを入力してください</p>
            <?php } ?>

            <?php if(isset($errors['password']) && $errors['password'] == 'length') { ?>
              <p class="text-danger">パスワードは4 ~ 16文字で入力してください</p>
            <?php } ?>


          </div>
          <div class="form-group">
            <label for="img_name">プロフィール画像</label>
            <input type="file" name="input_img_name" id="img_name" accept="image/*">
             <?php if(isset($errors['img_name']) && $errors['img_name'] == 'blank') { ?>
              <p class="text-danger">画像を選択してください</p>
            <?php } ?>
            <?php if(isset($errors['img_name']) && $errors['img_name'] == 'type') { ?>
              <p class="text-danger">拡張子が「jpg」「jpeg」「png」「gif」の画像を選択してください</p>
            <?php } ?>


          </div>
          <input type="submit" class="btn btn-default" value="確認">
          <a href="../signin.php" style="float: right; padding-top: 6px;" class="text-success">サインイン</a>
        </form>
      </div>

    </div>
  </div>
  <script src="../assets/js/jquery-3.1.1.js"></script>
  <script src="../assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="../assets/js/bootstrap.js"></script>
</body>
</html>
