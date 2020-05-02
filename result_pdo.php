<!DOCTYPE html>
<html>
    <head>
        <title>Book-O-Rama Search Results</title>
    </head>
    <body>
        <h1>Book-O-Rama Search Results</h1>
        <?php
        // 建立簡短的變數名稱
        $searchtype = $_POST['searchtype'];
        $searchterm = $_POST['searchterm'];

        if (!$searchtype || !$searchterm) {
            echo "You have not entered search details.<br\>
            Please go back and try again.</p>";
            exit;
        }

        // searchtype 的白名單
        switch ($searchtype) {
            case 'Title':
            case 'Author':
            case  'ISBN':
                break;
            default:        
                echo '<p>That is not a valid search type. <br/>
                Please go back and try again.</p>';
            exit;
        }
        // 設定使用 PDO
        $user = 'bookorama';
        $pass = 'bookorama123';
        $host = 'localhost';
        $db_name = 'books';

        // 設定 DSN
        $dsn = "mysql:host=$host;dbname=$db_name";

        // 連接資料庫
        try {
            $db = new PDO($dsn, $user, $pass);

            // 執行查詢
            $query = "SELECT ISBN, Author, Title, Price
            FROM Books WHERE $searchtype = :searchterm";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':searchterm', $searchterm);
            $stmt->execute();

            // 取得回傳的資料列數量
            echo "<p>Number of books found : ".$stmt->rowCount()."</p>";

            // 顯示每一個回傳的資料列
            while ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
                echo "<p><strong>Title: ".$result->Title."</strong>";
                echo "<br/>Author: ".$result->Author;
                echo "<br/>ISBN: ".$result->ISBN;
                echo "<br/>Price: \$".number_format($result->Price, 2)."</p>";
            }

            // 中斷資料庫連線
            $db = NULL;

        } catch (PDOException $e) {
            echo "Error: ".$e->getMessage();
            exit;
        }
        ?>
    </body>
</html>