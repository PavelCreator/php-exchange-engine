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
    <div id="label">ПОДСЧЁТ ПРИБЫЛИ</div>
    <div>Ссылки:
        <br><a href="index.php">клиентская сторона</a>
        <br><a href="admin.php">админ панель</a>
        <br><a href="history.php">история изменения курсов</a>
        <br><a href="exchange_history.php">история обменов</a>
        <br>
        <br></div>
    <div>Отчёт подчитывает прибыль в указанном периоде</div>
    <form name="formName" action="profit_calculator.php" method="post">
        <label for="date1">Введите начальную
            дату: </label><input type="text" name="date1" id="date1" placeholder="ДД.MM.ГГГГ"/><br>
        <label for="date2">Введите конечную
            дату: </label><input type="text" name="date2" id="date2" placeholder="ДД.MM.ГГГГ"/><br>
        <br>Примечание:<br>формат ввода даты ДД.ММ.ГГГГ (Пример - 03.08.2014)<br>
        <input type="submit" name="submit" value="Подсчитать" style="width:380px;"/>

        <div>Для проверки можете указать диапазон <br>от 02.08.2014 до 05.08.2014, в нём есть 11 записей.</div>
    </form>

    <?php
    $profit_sum = 0;

    if ($_POST["submit"] != "") {
        $date1_time = explode('.', $_POST["date1"]);
        $time1 = mktime(0, 0, 0, $date1_time["1"], $date1_time["0"], $date1_time["2"]);
        $date2_time = explode('.', $_POST["date2"]);
        $time2 = mktime(0, 0, 0, $date2_time["1"], $date2_time["0"], $date2_time["2"]);
        $mysqli = new mysqli("localhost", "root", "", "excdb");
        $mysqli->query("SET NAMES 'utf8'");
        function printResultSet($result_set) {
            echo "Количество записей: " . $result_set->num_rows . "<br>";
            while (($row = $result_set->fetch_assoc()) != false) {
                $USD_UAH = $row["UAH_USD_SELL"] - $row["UAH_USD_BUY"];
                $EUR_UAH = $row["UAH_EUR_SELL"] - $row["UAH_EUR_BUY"];
                $RUB_UAH = $row["UAH_RUB_SELL"] - $row["UAH_RUB_BUY"];
                $CNY_UAH = $row["UAH_CNY_SELL"] - $row["UAH_CNY_BUY"];
                /*Формула высчета прибыли*/
                if (($row["currencyFirst"] == "USD") && ($row["currencySecond"] == "UAH"))
                    $profit = $row["value1"] * $USD_UAH;
                if (($row["currencyFirst"] == "EUR") && ($row["currencySecond"] == "UAH"))
                    $profit = $row["value1"] * $EUR_UAH;
                if (($row["currencyFirst"] == "RUB") && ($row["currencySecond"] == "UAH"))
                    $profit = $row["value1"] * $RUB_UAH;
                if (($row["currencyFirst"] == "CNY") && ($row["currencySecond"] == "UAH"))
                    $profit = $row["value1"] * $CNY_UAH;
                if (($row["currencyFirst"] == "UAH") && ($row["currencySecond"] == "USD"))
                    $profit = $row["value2"] * $USD_UAH;
                if (($row["currencyFirst"] == "UAH") && ($row["currencySecond"] == "EUR"))
                    $profit = $row["value2"] * $USD_UAH;
                if (($row["currencyFirst"] == "UAH") && ($row["currencySecond"] == "RUB"))
                    $profit = $row["value2"] * $USD_UAH;
                if (($row["currencyFirst"] == "UAH") && ($row["currencySecond"] == "CNY"))
                    $profit = $row["value2"] * $USD_UAH;
                global $profit_sum;
                $profit_sum = $profit_sum + $profit;
            };
        }

        $result_set = $mysqli->query("SELECT * FROM `ex` WHERE `date` < " . $time2 . " AND `date` > " . $time1 . "");
        printResultSet($result_set);
        $mysqli->close();
        if ($profit_sum != 0) {
            echo "<div id='success' style='font-weight:bold;'>Результат: " . $profit_sum . " грн.</div>";
        } else {
            echo "<div id='error'>В данном диапазоне нет произведённых обменов валют</div>";
    }
    }

    ?>


</div>
</body>
</html>