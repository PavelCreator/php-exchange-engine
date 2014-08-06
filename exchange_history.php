<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>История обменов</title>
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/verificationAdmin.js"></script>
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
    <style>
        *{
            font-family:"Times New Roman", Times, Baskerville, Georgia, serif;
            font-size:16px;
        }
        td{
            border:1px solid #999;
        }
    </style>
</head>
<body>
<div id="container">
    <div id="label">ИСТОРИЯ ОБМЕНОВ</div>
    <div>Ссылки:
        <br><a href="index.php">клиентская сторона</a>
        <br><a href="admin.php">админ панель</a>
        <br><a href="history.php">история изменения курсов</a>
        <br><a href="profit_calculator.php">подсчёт прибыли за период</a>
        <br>
        <br></div>

    <table style='width:100%; margin:0 auto;'>
        <tr>
            <td rowspan="2">Дата и время</td>
            <td rowspan="2">Имя пользователя</td>
            <td rowspan="2">С чего обмен</td>
            <td rowspan="2">На что</td>
            <td rowspan="2">Значение с чего обмен</td>
            <td rowspan="2">Значение на что</td>
            <td rowspan="2">Прибыль<br>в ГРН</td>
            <td colspan="3">USD</td>
            <td colspan="3">EUR</td>
            <td colspan="3">RUB</td>
            <td colspan="3">CNY</td>
        </tr>
        <tr>
            <td>Покупка</td>
            <td>НБУ</td>
            <td>Продажа</td>
            <td>Покупка</td>
            <td>НБУ</td>
            <td>Продажа</td>
            <td>Покупка</td>
            <td>НБУ</td>
            <td>Продажа</td>
            <td>Покупка</td>
            <td>НБУ</td>
            <td>Продажа</td>
        </tr>

        <?php
        /*Перенос последних курсов валют из массива из MySQL*/
        $mysqli = new mysqli("localhost", "root", "", "excdb");
        $mysqli->query("SET NAMES 'utf8'");

        function printResultSet($result_set) {
            echo "Количество обменов: " . $result_set->num_rows . "<br>";
            while (($row = $result_set->fetch_assoc()) != false) {
                $USD_UAH = $row["UAH_USD_SELL"] - $row["UAH_USD_BUY"];
                $EUR_UAH = $row["UAH_EUR_SELL"] - $row["UAH_EUR_BUY"];
                $RUB_UAH = $row["UAH_RUB_SELL"] - $row["UAH_RUB_BUY"];
                $CNY_UAH = $row["UAH_CNY_SELL"] - $row["UAH_CNY_BUY"];

                /*Формула высчета прибыли*/
                if (($row["currencyFirst"] == "USD") && ($row["currencySecond"] == "UAH")) $profit = $row["value1"] * $USD_UAH;
                if (($row["currencyFirst"] == "EUR") && ($row["currencySecond"] == "UAH")) $profit = $row["value1"] * $EUR_UAH;
                if (($row["currencyFirst"] == "RUB") && ($row["currencySecond"] == "UAH")) $profit = $row["value1"] * $RUB_UAH;
                if (($row["currencyFirst"] == "CNY") && ($row["currencySecond"] == "UAH")) $profit = $row["value1"] * $CNY_UAH;
                if (($row["currencyFirst"] == "UAH") && ($row["currencySecond"] == "USD")) $profit = $row["value2"] * $USD_UAH;
                if (($row["currencyFirst"] == "UAH") && ($row["currencySecond"] == "EUR")) $profit = $row["value2"] * $USD_UAH;
                if (($row["currencyFirst"] == "UAH") && ($row["currencySecond"] == "RUB")) $profit = $row["value2"] * $USD_UAH;
                if (($row["currencyFirst"] == "UAH") && ($row["currencySecond"] == "CNY")) $profit = $row["value2"] * $USD_UAH;

                /*Вывод таблицы*/
                echo '<tr>
                        <td>' . date("Y-m-d H:i:s", $row["date"]) . '</td>
                        <td>' . $row["userName"] . '</td>
                        <td>' . $row["currencyFirst"] . '</td>
                        <td>' . $row["currencySecond"] . '</td>
                        <td>' . $row["value1"] . '</td>
                        <td>' . $row["value2"] . '</td>
                        <td>' . $profit . '</td>
                        <td>' . $row["UAH_USD_BUY"] . '</td>
                        <td>' . $row["USD_NBU"] . '</td>
                        <td>' . $row["UAH_USD_SELL"] . '</td>
                        <td>' . $row["UAH_EUR_BUY"] . '</td>
                        <td>' . $row["EUR_NBU"] . '</td>
                        <td>' . $row["UAH_EUR_SELL"] . '</td>
                        <td>' . $row["UAH_RUB_BUY"] . '</td>
                        <td>' . $row["RUB_NBU"] . '</td>
                        <td>' . $row["UAH_RUB_SELL"] . '</td>
                        <td>' . $row["UAH_CNY_BUY"] . '</td>
                        <td>' . $row["CNY_NBU"] . '</td>
                        <td>' . $row["UAH_CNY_SELL"] . '</td>
                      </tr>';
            };
        }

        $result_set = $mysqli->query("SELECT * FROM `ex`");
        printResultSet($result_set);


        $mysqli->close();

        ?></table>

    <div id="error"></div>
    <div id="success"></div>
</div>
</body>
</html>