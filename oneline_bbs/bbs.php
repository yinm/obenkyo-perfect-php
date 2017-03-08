<?php

// データベースに接続・データベースを選択 (PHP7では、mysql関数が使えないので、PDOを使用する)
try {
    $dbh = new PDO(
        'mysql:host=localhost;dbname=oneline_bbs;charset=utf8',
        'root',
        '',
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        )
    );

    $validationErrors = array();
    // POSTなら保存処理実行
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 名前が正しく入力されているかチェック
        $name = null;
        if (!isset($_POST['name']) || !strlen($_POST['name'])) {
            $validationErrors['name'] = '名前を入力してください';
        } elseif (strlen($_POST['name']) > 40) {
            $validationErrors['name'] = '名前は40文字以内で入力してください';
        } else {
            $name = $_POST['name'];
        }
    }

    // ひとことが正しく入力されているかチェック
    $comment = null;
    if (!isset($_POST['comment']) || !strlen($_POST['comment'])) {
        $validationErrors['comment'] = 'ひとことを入力してください';
    } elseif (strlen($_POST['comment']) > 200) {
        $validationErrors['comment'] = 'ひとことは200文字以内で入力してください';
    } else {
        $comment = $_POST['comment'];
    }

    // エラーがなければ保存
    if (count($validationErrors) === 0) {
        // 保存するためのSQL文を作成
        $statement = $dbh->prepare("INSERT INTO post (name, comment, created_at) VALUES (:name, :comment, :created_at)");
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':comment', $comment, PDO::PARAM_STR);
        $statement->bindValue(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);

        // 保存する
        $statement->execute();

        $statement = null;

        header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    }

} catch (PDOException $e) {
    echo $e->getMessage();
}


    // 投稿された内容を取得するSQLを作成して、結果を取得
    $selectSql = 'SELECT * FROM `post` ORDER BY `created_at` DESC';
    $selectStatement = $dbh->query($selectSql);

    // 取得した結果を$postsに格納
    $posts = array();
    if ($selectStatement !== false && $selectStatement->rowCount()) {
        while ($post = $selectStatement->fetch(PDO::FETCH_ASSOC)) {
            $posts[] = $post;
        }
    }

    // 取得結果を解放して、接続を閉じる
    $selectStatement = null;
    $dbh = null;

include 'views/bbs_view.php';
