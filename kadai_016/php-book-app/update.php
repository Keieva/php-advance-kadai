<?php

$dsn='mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user='root';
$password='root';

if(isset($_GET['id'])){

    try{
        
        $pdo= new PDO($dsn, $user,$password);

            // genresのテーブルからgenre_codeカラムを取得のSQL文取得のため変数に代入
    $sql_select_books = 'SELECT * FROM books WHERE id = :id';

    // SQL文の実行
    $stmt_select_book = $pdo->prepare($sql_select_books);

    // 割り当て
    $stmt_select_book->bindValue(':id',$_GET['id'], PDO::PARAM_INT);

    // SQL文実行
    $stmt_select_book->execute();

    // 実行結果を配列で取得
    $book = $stmt_select_book->fetch(PDO::FETCH_ASSOC);

    if($book === FALSE){
        exit('idパラメータが不正です。');
    }
        

        // genresのテーブルからgenre_codeカラムを取得のSQL文取得のため変数に代入
        $sql_select_genres = 'SELECT genre_code FROM genres';

        // SQL文の実行
        $stmt_select_genres = $pdo->query($sql_select_genres);

        // 配列の取得　
        $genre_codes = $stmt_select_genres->fetchAll(PDO::FETCH_COLUMN);

    } catch (PDOException $e){
            exit($e->getMessage());
    }

    } else {
        // idパラメータの値が存在しない場合
        exit('idパラメータの値が存在しません');
    }

    if(isset($_POST['submit'])){
        try{
        
            $pdo= new PDO($dsn, $user,$password);
    
            $sql_update='
                UPDATE  books
                SET book_cord =:book_cord,
                book_name = :book_name,
                price = :price,
                stock_quantity = :stock_quantity,
                genre_code = :genre_code
                WHERE id =:id
                ';
            $stmt_update = $pdo->prepare($sql_update);
    
            $stmt_update->bindValue(':book_cord', $_POST['book_cord'], PDO::PARAM_INT);
            $stmt_update->bindValue(':book_name', $_POST['book_name'], PDO::PARAM_STR);
            $stmt_update->bindValue(':price', $_POST['price'], PDO::PARAM_INT);
            $stmt_update->bindValue(':stock_quantity', $_POST['stock_quantity'], PDO::PARAM_INT);
            $stmt_update->bindValue(':genre_code', $_POST['genre_code'], PDO::PARAM_INT);
            $stmt_update->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    
            // 実行する
            $stmt_update->execute();
    
            $count= $stmt_update->rowCount();
    
            $message = "商品を{$count}件編集しました。";
    
            // メッセージをつけてリダイレクトさせる。
            header("Location: read.php?message={$message}");
        } catch(PDOException $e) {
            exit($e->getMessage());
        }
    }
    ?>

<!DOCTYPE html>
 <html lang="ja">
 
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>書籍編集</title>
     <link rel="stylesheet" href="css/style.css">
 
     <!-- Google Fontsの読み込み -->
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
 </head>
 <body>
    <header>
        <nav>
            <a href="index.php" >書籍管理アプリ</a>
        </nav>
    </header>
    <main>
        <article class="registration">
            <h1>書籍編集</h1>
            <div class="back">
                <a href ="read.php" class="btn">&lt;戻る</a>
            </div>
                <form action="update.php?id=<?=$_GET['id']?>" method="post" class="registration-form">
                <div>
                    <label for="book_cord">書籍コード</label>
                    <input type="number" name="book_cord"  value=<?=$book['book_cord'] ?> min="0" max="100000000" required>

                    <label for="book_name">書籍名</label>
                    <input type="text" name="book_name" value=<?=$book['book_name'] ?> maxlength="50"required>

                    <label for="price">単価</label>
                    <input type="number" name="price" value= <?=$book['price'] ?> min="0" max="100000000" required>

                    <label for="stock_quantity">在庫数</label>
                    <input type="number" name="stock_quantity" value=<?=$book['stock_quantity'] ?> min="0" max="100000000" required>

                    <label for="genre_code">仕入れ先コード</label>
                    <select name="genre_code" required>
                        <option disabled selected value>選択してください</option>
                        <?php
                        foreach($genre_codes as $genre_code){
                            if($genre_code === $book['genre_code']){
                            echo "<option value='{$genre_code}' selected>{$genre_code}</option>";
                        } else {
                            echo "<option value='{$genre_code}'>{$genre_code}</option>";
                        }
                    }
                        ?>
                    </select>

                </div>
                <button type="submit" class="submit-btn" name="submit" value= "update">更新</button>
                </form>


        </article>
    </main>
    <footer>
        <p class="copyright " >&copy;書籍管理アプリ All right reserved.></p>
    </footer>  
</body>