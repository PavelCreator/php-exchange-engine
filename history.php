<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>История изменения курса</title>
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/verificationAdmin.js"></script>
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
    <style>
        *{
            font-family:"Times New Roman", Times, Baskerville, Georgia, serif;
            font-size:16px;
        }
        input{
            width:100px;
        }
        td{
            border:1px solid #999;
        }
    </style>
</head>
<body>
<div id="container">
    <div id="label">ИСТОРИЯ ИЗМЕНЕНИЯ КУРСА</div>
    <div>Ссылки:
        <br><a href="index.php">клиентская сторона</a>
        <br><a href="admin.php">админ панель</a>
        <br><a href="exchange_history.php">история обменов</a>
        <br><a href="profit_calculator.php">подсчёт прибыли за период</a>
        <br>
        <br></div>

    <table style='width:100%;style="margin:0 auto;"'>
        <tr>
            <td rowspan="2">Дата и время</td>
            <td colspan="4">Покупка</td>
            <td colspan="4">Продажа</td>
        </tr>
        <tr>
            <td>USD</td>
            <td>EUR</td>
            <td>RUB</td>
            <td>CNY</td>
            <td>USD</td>
            <td>EUR</td>
            <td>RUB</td>
            <td>CNY</td>
        </tr>

        <?php
        /*Перенос последних курсов в массива из MySQL*/
        $mysqli = new mysqli("localhost", "root", "", "excdb");
        $mysqli->query("SET NAMES 'utf8'");

        function printResultSet($result_set) {
            echo "Количество изменений курса: " . $result_set->num_rows . "<br>"; //подсчитывает количество записей
            /*fetch_assoc() переберает все строки (создаёт из каждой строки массив и отдаёт переменной), когда записи заканчиваются возвращает false*/
            while (($row = $result_set->fetch_assoc()) != false) {
                echo '<tr>
                        <td>' . date("Y-m-d H:i:s", $row["date"]) . '</td>
                        <td>' . $row["UAH_USD_BUY"] . '</td>
                        <td>' . $row["UAH_EUR_BUY"] . '</td>
                        <td>' . $row["UAH_RUB_BUY"] . '</td>
                        <td>' . $row["UAH_CNY_BUY"] . '</td>
                        <td>' . $row["UAH_USD_SELL"] . '</td>
                        <td>' . $row["UAH_EUR_SELL"] . '</td>
                        <td>' . $row["UAH_RUB_SELL"] . '</td>
                        <td>' . $row["UAH_CNY_SELL"] . '</td>
                      </tr>';
            };
        }

        $result_set = $mysqli->query("SELECT * FROM `cr`");
        printResultSet($result_set);


        $mysqli->close();

        ?></table>

    <div id="error"></div>
    <div id="success"></div>
</div>
</body>
</html>