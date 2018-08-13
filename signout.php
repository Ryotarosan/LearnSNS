<?php 

session_start();
// セッション変数の破棄

$_SESSION=[];
// サーバー内のセッション変数のクリア
session_destroy();

//signin.phpに移動
header("Location:signin.php");
exit();


 ?>
 sigoutだよ