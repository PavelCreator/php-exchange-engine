<?php
include_once "parser.php";include_once "parser.php";

/*Перенос последних курсов в массива из MySQL*/
$mysqli = new mysqli("localhost", "root", "", "excdb");
$mysqli->query("SET NAMES 'utf8'");

$last_row = $mysqli->query("select * from `cr` where `id`=(select max(`id`) from `cr`)");
$last_values = $last_row->fetch_assoc();
/*print_r($last_values);*/

$mysqli->close();

if ($_POST["submit"] != "") {
    $mysqli = new mysqli("localhost", "root", "", "excdb");
    $mysqli->query("SET NAMES 'utf8'");
    /*Засносим данные в таблицу `cr`*/
    $mysqli->query("INSERT INTO `cr` (`UAH_USD_BUY`,`UAH_EUR_BUY`,`UAH_RUB_BUY`,`UAH_CNY_BUY`,`UAH_USD_SELL`,    `UAH_EUR_SELL`,`UAH_RUB_SELL`,`UAH_CNY_SELL`,`date`) VALUES('" . $_POST["UAH_USD_BUY"] . "','" . $_POST["UAH_EUR_BUY"] . "','" . $_POST["UAH_RUB_BUY"] . "','" . $_POST["UAH_CNY_BUY"] . "','" . $_POST["UAH_USD_SELL"] . "','" . $_POST["UAH_EUR_SELL"] . "','" . $_POST["UAH_RUB_SELL"] . "','" . $_POST["UAH_CNY_SELL"] . "','" . time() . "')");
    $mysqli->close();
    /*Перезагрузка необходима для подгрузки новых данных в поля по каждой валюте*/
    echo "<script>window.location = 'admin.php';</script>";
}
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ панель</title>
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
    </style>
</head>
<body>
<div id="container">
    <div id="label">АДМИН ПАНЕЛЬ</div>
    <div class="table-div">
        <table style="width:400px;text-align:center;margin:0 auto;">
            <tr>
                <td colspan="3">Ссылки:
                    <br><a href="index.php">клиентская сторона</a>
                    <br><a href="history.php">история изменения курсов</a>
                    <br><a href="exchange_history.php">история обменов</a>
                    <br><a href="profit_calculator.php">подсчёт прибыли за период</a>
                </td>
            </tr>
            <tr>
                <td colspan="3"><b>Установка процентного отношения к курсу НБУ</b>
                    <br>(курс НБУ для каждой валюты парсится со <br>страницы сайта НБУ через регулярное выражение)
                </td>
            </tr>
            <tr>
                <td>Покупка на <input type="text" name="buyPercent" id="buyPercent" style="text-align:right;"/></td>
                <td>% дешевле</td>
                <td><img class="imgButton" id="buyPercentSubmit" src="img/button.png" alt=""/></td>
            </tr>
            <tr>
                <td>Продажа на <input type="text" name="sellPercent" id="sellPercent" style="text-align:right;"/>
                </td>
                <td>% дороже</td>
                <td><img class="imgButton" id="sellPercentSubmit" src="img/button.png" alt=""/></td>
            </tr>

            <script>
                /*Пересчёт по указанным процентам и вставка в поля по валютам*/
                $(function () {
                    $(document).on("click", "#buyPercentSubmit", function (element) {
                        $("#UAH_USD_BUY").val($("#UAH_USD_NBU").val() - ($("#UAH_USD_NBU").val() * $("#buyPercent").val() * 0.01));
                        $("#UAH_EUR_BUY").val($("#UAH_EUR_NBU").val() - ($("#UAH_EUR_NBU").val() * $("#buyPercent").val() * 0.01));
                        $("#UAH_RUB_BUY").val($("#UAH_RUB_NBU").val() - ($("#UAH_RUB_NBU").val() * $("#buyPercent").val() * 0.01));
                        $("#UAH_CNY_BUY").val($("#UAH_CNY_NBU").val() - ($("#UAH_CNY_NBU").val() * $("#buyPercent").val() * 0.01));
                    });
                    $(document).on("click", "#sellPercentSubmit", function (element) {
                        $("#UAH_USD_SELL").val($("#UAH_USD_NBU").val() - ($("#UAH_USD_NBU").val() * $
                        ("#sellPercent").val() * 0.01 * (-1)));
                        $("#UAH_EUR_SELL").val($("#UAH_EUR_NBU").val() - ($("#UAH_EUR_NBU").val() * $
                        ("#sellPercent").val() * 0.01 * (-1)));
                        $("#UAH_RUB_SELL").val($("#UAH_RUB_NBU").val() - ($("#UAH_RUB_NBU").val() * $("#sellPercent").val() * 0.01 * (-1)));
                        $("#UAH_CNY_SELL").val($("#UAH_CNY_NBU").val() - ($("#UAH_CNY_NBU").val() * $("#sellPercent").val() * 0.01 * (-1)));
                    });
                });
            </script>
        </table>
        Примечание: разделитель между целым числом и дробной частью вводить в виде точки("."), а не запятой (",").
        <br><br>

        <div><b>Установка курсов валют</b><br>(при загрузке страницы в поля "Покупка" и <br>"Продажа" автоматически
            вставляются последние <br>внесённые пользователем данные из БД)
        </div>
        <form name="adminForm" action="admin.php" method="post">

            <table class="tableAdmin" style="margin:0 auto;">
                <tr>
                    <td>Навправление обмена</td>
                    <td>Покупка</td>
                    <td>Курс НБУ на<br>
                        <script>
                            /*Текущаяя дата в формате DD.MM.YYYY*/
                            var date2 = new Date();
                            date2.toString = function () {
                                var year = this.getFullYear();
                                var month = this.getMonth() + 1;//+1 делает последовательность месяцев не от 0 до 11, а от 1 до 12
                                if (month < 10) month = "0" + month;//делаем вместо 3 - 03
                                var day = this.getDate();
                                if (day < 10) day = "0" + day;
                                return day + "." + month + "." + year;
                            }
                            document.write(date2);
                        </script>
                    </td>
                    <td>Продажа</td>
                </tr>
                <tr>
                    <td>1 USD =<br><span class="small">(американский доллар)</span></td>
                    <td>
                        <input type="text" name="UAH_USD_BUY" id="UAH_USD_BUY" value="<?php echo $last_values["UAH_USD_BUY"]; ?>" style="text-align:right;"/>UAH
                    </td>
                    <td><input readonly name="UAH_USD_NBU" id="UAH_USD_NBU" type="text" value="<?php echo $USD_NBU
                        ?>" style="text-align:right;"/>UAH
                    </td>
                    <td>
                        <input type="text" name="UAH_USD_SELL" id="UAH_USD_SELL" value="<?php echo $last_values["UAH_USD_SELL"]; ?>" style="text-align:right;"/>UAH
                    </td>
                    </td>
                </tr>
                <tr>
                    <td>1 EUR =<br><span class="small">(евро)</span></td>
                    <td>
                        <input type="text" name="UAH_EUR_BUY" id="UAH_EUR_BUY" value="<?php echo $last_values["UAH_EUR_BUY"]; ?>" style="text-align:right;"/>UAH
                    </td>
                    <td><input readonly name="UAH_EUR_NBU" id="UAH_EUR_NBU" type="text" value="<?php echo $EUR_NBU
                        ?>" style="text-align:right;
                    "/>UAH
                    </td>
                    <td>
                        <input type="text" name="UAH_EUR_SELL" id="UAH_EUR_SELL" value="<?php echo $last_values["UAH_EUR_SELL"]; ?>" style="text-align:right;"/>UAH
                    </td>
                    </td>
                </tr>
                <tr>
                    <td>1 RUB =<br><span class="small">(российский рубль)</span></td>
                    <td>
                        <input type="text" name="UAH_RUB_BUY" id="UAH_RUB_BUY" value="<?php echo $last_values["UAH_RUB_BUY"]; ?>" style="text-align:right;"/>UAH
                    </td>
                    <td><input readonly name="UAH_RUB_NBU" id="UAH_RUB_NBU" type="text" value="<?php echo $RUB_NBU
                        ?>" style="text-align:right;"/>UAH
                    </td>
                    <td>
                        <input type="text" name="UAH_RUB_SELL" id="UAH_RUB_SELL" value="<?php echo $last_values["UAH_RUB_SELL"]; ?>" style="text-align:right;"/>UAH
                    </td>
                    </td>
                </tr>
                <tr>
                    <td>1 CNY =<br><span class="small">(китайский юань)</span></td>
                    <td>
                        <input type="text" name="UAH_CNY_BUY" id="UAH_CNY_BUY" value="<?php echo $last_values["UAH_CNY_BUY"]; ?>" style="text-align:right;"/>UAH
                    </td>
                    <td><input readonly name="UAH_CNY_NBU" id="UAH_CNY_NBU" type="text" value="<?php echo $CNY_NBU
                        ?>" style="text-align:right;"/>UAH
                    </td>
                    <td>
                        <input type="text" name="UAH_CNY_SELL" id="UAH_CNY_SELL" value="<?php echo $last_values["UAH_CNY_SELL"]; ?>" style="text-align:right;"/>UAH
                    </td>
                    </td>
                </tr>
            </table>
    </div>
    <input type="submit" name="submit" id="button2" value="Установить"/>
    <br><br><br><br><br><br>

    </form>
    <div id="error"></div>
    <div id="success"></div>
</div>
</body>
</html>