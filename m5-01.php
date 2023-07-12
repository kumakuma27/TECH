<?php
    //DB接続
    $dsn = '*******';
    $user = '*******';
    $password = '*******';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	//echo "接続成功";
	echo "<br>";
	
	//テーブル作成
	$sql = "CREATE TABLE IF NOT EXISTS five"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date TIMESTAMP,"
    . "password TEXT,"
    .");";
    //$stmt = $pdo->query($sql);
	    
    //編集＆投稿
    if(!empty($_POST["edit_number1"])) {
        $sql = "UPDETE five SET name = :name, comment = :comment, date = now(), password = :password WHERE id = :id";
        $stmt = $pdo -> prepare($sql);
        $stmt -> bindPaeam(":name", $name, POD::PARAM_STR);
        $stmt -> bindPaeam(":comment", $comment, POD::PARAM_STR);
        $stmt -> bindPaeam(":password", $password, POD::PARAM_STR);
        $stmt -> bindPaeam(":id", $edit_number, POD::PARAM_INT);
        
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $password = $_POST["password"];
        $edit_number = $_POST["edit_number1"];
        
        $stmt -> execute();
    
    //新規投稿
    } elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])) {
        $sql = $pod -> prepare("INSERT INTO five (name, comment, date, password) VALUES (:name, :comment, now(), :password)");
        $sql -> bindParam(":name, $name, PDO::PARAM_STR");
        $sql -> bindParam(":comment", $comment, PDO::PARAM_STR);
        //$sql -> bindParam(":date, $date, POD::PARAM_STR");
        $sql -> bindParam(":password", $password, PDO::PARAM_STR);
        
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        //$date = time();
        //$date = date("Y/m/d H:i:s");
        $password = $_POST["password"];
        
        $sql -> execute();
    }
    
    //削除
    if(!empty($_POST["delete_num"]) && !empty($_POST["password_del"])) {
        $delete_num = $_POST["delete_num"];
        $password_del = $_POST["password_del"];
        //削除したい番号のパスワードを取得
        $sql = "SELECT password FROM five WHERE id = :id";
        $stmt = $pod -> prepare($sql);
        $stmt -> bindParam(":id", $delete_num, PDO::PARAM_INT);
        $stmt -> execute();
        $password = $stmt -> fetchColumn();
        
        if($password == $password_del) {
            $sql = "delete from five WHERE id = :id";
            $stmt = $pod -> prepare($sql);
            $symt -> bindParam(":id", $delete_num, PDO::PARSM_INT);
            $stmt -> execute();
        } else {
            echo "パスワードが間違っています";
        }
    }
    
    //編集内容送信
    if(!empty($_POST["edit_number"]) && !empty($_POST["password_del"])) {
        $sql = "SELECT * FROM five WHERE id = :id";
        $stmt = $pod -> prepare($sql);  //差し替えるパラメータを含めて記述したSQL文を準備
        $edit_number -> bindParam (":id", $edit_number, PDO::PARAM_INT);    //差し替えるパラメータ値を指定
        $edit_number = $_POST["edit_number"];
        $stmt -> execute(); //SQLを実行
        
        $results = $stmt -> fetch(POD::FETCH_NUM);
        
        if($results[4] == $_POST["password_edit"]) {
            $edit_number = $edit_number1;
            $password_edit = $_POST["password_edit"];
            $edit_name = $resurts[1];
            $edit_comment = $results[2];
        } else {
            echo "パスワードが間違っています";
        }
    }
    
?>

<title>m5-01</title>
<h2>簡易掲示板</h2>
<from action = "" method = "POST">
    【投稿】
    <input type = "hidden" name = "edit_numbe1" value = "<?php if(isset($edit_number1)) { echo $edit_number1; } ?>">
    <br>
    <input type = "text" name = "name" value = "<?php if(isset($edit_name)) { echo $edit_name; } ?>" placeholder = "名前を入力">
    <br>
    <input type = "text" name = "comment" value = "<?php if(isset($edit_comment)) { echo $edit_comment; } ?>" placeholder = "コメントを入力">
    <br>
    <input type = "text" name = "password" value = "<?php if(isset($password_edit)) { echo $password_edit; } ?>" placeholder = "パスワードを入力">
    <input type = "submit" name = "submit" value = "送信">
    <br>
</from>

    【削除】
    <form action = "" method = "POST">
        <input type = "number" name = "delete_num" placeholder = "削除したい番号を入力">
        <br>
        <input type = "text" name = "password_del" placeholder = "パスワードを入力">
        <input type = "submit" name = "submit" value = "削除">
        <br>
    </form>
    
    【編集】
    <form action = "" method = "POST">
        <input type = "number" name = "edit_number" placeholder = "編集したい番号を入力">
        <br>
        <input type = "text" name = "password_edit" placeholder = "パスワードを入力">
        <input type = "submit" name = "submit" value = "編集">
        <br>
    </form>
    
＜投稿一覧＞
<br>
<?php
    $dsn = '*******';
    $user = '*******';
    $password = '*******';
    
    //$pod = new PDO($dsn, $user, $password, array(PDO::AFTER_ERRMODE => PDO::ERRMODE_WARNIMG));
    //レコード数取得
    /*$sql = "SELECT COUNT(* FROM five";
    $stmt = $pdo -> query($sql);
    $count = $stmt -> fetchColumn();*/
    
    //テーブルidを$ids配列に格納
    $sql = "SELECT id FROM five";
    $stmt = $pdo -> query($sql);
    $ids = $stmt -> fetchALL(PDO::FETCH_COLUMN);
    
    //$idをレコード数ループで取り出す
    foreach($ids as $id) {
        $sql = 'SELECT * FROM five WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll(); 
            foreach($results as $row) {
                //$rowの中にはカラム名
                echo $row["id"].",";
                echo $row["name"].",";
                echo $row["comment"].",";
                echo $row["date"].",";
                echo $row["password"]."<br>";
            }
    }
?>    
    
    
    
    
    
    
    