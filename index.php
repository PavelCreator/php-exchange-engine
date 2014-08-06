<?php
/*Перенос последних курсов валют в массива из MySQL*/
$mysqli = new mysqli("localhost", "root", "", "excdb");
$mysqli->query("SET NAMES 'utf8'");

$last_row = $mysqli->query("select * from `cr` where `id`=(select max(`id`) from `cr`)");
$last_values = $last_row->fetch_assoc();
$mysqli->close();

include_once "parser.php";
echo '<br>';

if ($_POST["submit"] != "") {
    $mysqli = new mysqli("localhost", "root", "", "excdb");
    $mysqli->query("SET NAMES 'utf8'");
    /*Засносим данные в таблицу `ex`*/
    $mysqli->query("INSERT INTO `ex` (`UAH_USD_BUY`,`USD_NBU`,`UAH_USD_SELL`,`UAH_EUR_BUY`,`EUR_NBU`,`UAH_EUR_SELL`,`UAH_RUB_BUY`,`RUB_NBU`,`UAH_RUB_SELL`,`UAH_CNY_BUY`,`CNY_NBU`,`UAH_CNY_SELL`,`userName`,`currencyFirst`,`currencySecond`,`value1`,`value2`,`date`) VALUES('" . $last_values["UAH_USD_BUY"] . "','" . $USD_NBU . "','" . $last_values["UAH_USD_SELL"] . "','" . $last_values["UAH_EUR_BUY"] . "','" . $EUR_NBU . "','" . $last_values["UAH_EUR_SELL"] . "','" . $last_values["UAH_RUB_BUY"] . "','" . $RUB_NBU . "','" . $last_values["UAH_RUB_SELL"] . "','" . $last_values["UAH_CNY_BUY"] . "','" . $CNY_NBU . "','" . $last_values["UAH_CNY_SELL"] . "','" . $_POST["userName"] . "','" . $_POST["currencyFirst"] . "','" . $_POST["currencySecond"] . "','" . $_POST["value1"] . "','" . $_POST["value2"] . "','" . time() . "')");
    $mysqli->close();
}

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Обмен валют Online</title>
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/verification.js"></script>
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
</head>
<body>


<script>
    /*Функция округления до числа*/
    function roundPlus(x, n) { //x - число, n - количество знаков
        var m = Math.pow(10, n);
        return Math.round(x * m) / m;
    }

    /*  Вставка знака валюты при переключаниях <option>.
        Внесение данных о направлении валютного обмена.
        Манипуляции с <option>                              */
    var value1 = 1;
    var value2 = <?php echo($last_values["UAH_USD_SELL"]) ?>;

    $(function () {
        $(document).on("click", "#UAH1", function (element) {
            $("#sign1").html('<img class="img" src="img/uah.png" height="15px" alt=""/>');
            value1 = 1;
            value2 = <?php echo $last_values["UAH_USD_SELL"] ?>;
            $("#value2").val(roundPlus(($("#value1").val() * (value1 / value2)), 2));//механизм подстановки курса
            $("#error").html("");
            $("#sign2").html('<img class="img" src="img/usd.png" height="15px" alt=""/>');
            $("#currencySecond").html('<option id="USD2" value="USD" selected>USD американский доллар</option><option id="EUR2" value="EUR">EUR евро</option><option id="RUB2" value="RUB">RUB российский рубль</option><option id="CNY2" value="CNY">CNY китайский юань</option>');
        });
        $(document).on("click", "#USD1", function (element) {
            $("#sign1").html('<img class="img" src="img/usd.png" height="15px" alt=""/>');
            value1 = <?php echo $last_values["UAH_USD_BUY"] ?>;
            value2 = 1;
            $("#value2").val(roundPlus(($("#value1").val() * (value1 / value2)), 2));//механизм подстановки курса
            $("#error").html("");
            $("#sign2").html('<img class="img" src="img/uah.png" height="15px" alt=""/>');
            $("#currencySecond").html('<option id="UAH2" value="UAH">UAH украинская гривна</option>');
        });
        $(document).on("click", "#EUR1", function (element) {
            $("#sign1").html('<img class="img" src="img/eur.png" height="15px" alt=""/>');
            value1 = <?php echo $last_values["UAH_EUR_BUY"] ?>;
            value2 = 1;
            $("#value2").val(roundPlus(($("#value1").val() * (value1 / value2)), 2));//механизм подстановки курса
            $("#error").html("");
            $("#sign2").html('<img class="img" src="img/uah.png" height="15px" alt=""/>');
            $("#currencySecond").html('<option id="UAH2" value="UAH">UAH украинская гривна</option>');
        });
        $(document).on("click", "#RUB1", function (element) {
            $("#sign1").html('<img class="img" src="img/rub.png" height="15px" alt=""/>');
            value1 = <?php echo $last_values["UAH_RUB_BUY"] ?>;
            value2 = 1;
            $("#value2").val(roundPlus(($("#value1").val() * (value1 / value2)), 2));//механизм подстановки курса
            $("#error").html("");
            $("#sign2").html('<img class="img" src="img/uah.png" height="15px" alt=""/>');
            $("#currencySecond").html('<option id="UAH2" value="UAH">UAH украинская гривна</option>');
        });
        $(document).on("click", "#CNY1", function (element) {
            $("#sign1").html('<img class="img" src="img/cny.png" height="15px" alt=""/>');
            value1 = <?php echo $last_values["UAH_CNY_BUY"] ?>;
            value2 = 1;
            $("#value2").val(roundPlus(($("#value1").val() * (value1 / value2)), 2));//механизм подстановки курса
            $("#error").html("");
            $("#sign2").html('<img class="img" src="img/uah.png" height="15px" alt=""/>');
            $("#currencySecond").html('<option id="UAH2" value="UAH">UAH украинская гривна</option>');
        });
        $(document).on("click", "#USD2", function (element) {
            $("#sign2").html('<img class="img" src="img/usd.png" height="15px" alt=""/>');
            value2 = <?php echo $last_values["UAH_USD_SELL"] ?>;
            $("#value2").val(roundPlus(($("#value1").val() * (value1 / value2)), 2));//механизм подстановки курса
            $("#error").html("");
        });
        $(document).on("click", "#EUR2", function (element) {
            $("#sign2").html('<img class="img" src="img/eur.png" height="15px" alt=""/>');
            value2 = <?php echo $last_values["UAH_EUR_SELL"] ?>;
            $("#value2").val(roundPlus(($("#value1").val() * (value1 / value2)), 2));//механизм подстановки курса
            $("#error").html("");
        });
        $(document).on("click", "#RUB2", function (element) {
            $("#sign2").html('<img class="img" src="img/rub.png" height="15px" alt=""/>');
            value2 = <?php echo $last_values["UAH_RUB_SELL"] ?>;
            $("#value2").val(roundPlus(($("#value1").val() * (value1 / value2)), 2));//механизм подстановки курса
            $("#error").html("");
        });
        $(document).on("click", "#CNY2", function (element) {
            $("#sign2").html('<img class="img" src="img/cny.png" height="15px" alt=""/>');
            value2 = <?php echo $last_values["UAH_CNY_SELL"] ?>;
            $("#value2").val(roundPlus(($("#value1").val() * (value1 / value2)), 2));//механизм подстановки курса
            $("#error").html("");
        });
    });

    $(function () {
        $(document).on("click", "#label", function (element) {
            alert(value1 + "\n" + value2);
        });
    });

</script>

<!-- Тело страницы -->
<div id="container">
    <form name="formName" action="index.php" method="post">
        <div id="label">ОБМЕН ВАЛЮТ ОНЛАЙН</div>
        <div>Ссылки:
            <br><a href="admin.php">админ панель</a>
            <br><a href="exchange_history.php">история обменов</a>
            <br><a href="history.php">история изменения курсов</a>
            <br><a href="profit_calculator.php">подсчёт прибыли за период</a>
            <br><br>
        </div>

        <label for="userName">Имя пользователя: </label><input type="text" name="userName" id="userName"/>

        <div class="table-div">
            <table id="tableMain" style="margin:0 auto;">
                <tr>
                    <td>
                        <div id="currencyFirstLabel"><label for="currencyFirst">Что меняем</label></div>
                        <select name="currencyFirst" id="currencyFirst" style="width:200px">
                            <option id="UAH1" value="UAH">UAH украинская гривна</option>
                            <option id="USD1" value="USD">USD американский доллар</option>
                            <option id="EUR1" value="EUR">EUR евро</option>
                            <option id="RUB1" value="RUB">RUB российский рубль</option>
                            <option id="CNY1" value="CNY">CNY китайский юань</option>
                        </select></td>
                    <td style="width:">
                        <label for="value1">Сколько</label><br/>
                        <input type="text" name="value1" id="value1" value="100" style='text-align:right;'/>

                        <div id="sign1"><img class="img" src="img/uah.png" height="15px" alt=""/></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="currencySecondLabel"><label for="currencySecond">На что меняем</label></div>
                        <select name="currencySecond" id="currencySecond" style="width:200px">
                            <option id="USD2" value="USD">USD американский доллар</option>
                            <option id="EUR2" value="EUR">EUR евро</option>
                            <option id="RUB2" value="RUB">RUB российский рубль</option>
                            <option id="CNY2" value="CNY">CNY китайский юань</option>
                        </select></td>
                    <td>
                        <label for="value2">Вы получите</label><br>
                        <input type="text" name="value2" id="value2" value="<?php echo round((100 / $last_values["UAH_USD_SELL"]), 2) ?>" style='text-align:right;' readonly/>

                        <div id="sign2"><img class="img" src="img/usd.png" height="15px" alt=""/></div>
                    </td>
                </tr>
            </table>
        </div>


        <input type="submit" name="submit" id="button" value="Обменять"/>

        <div id="error"></div>
        <div id="success"></div>

        <script>

            /*Сохранение введённых данных после перезагрузки страницы*/
            if ('<?php echo $_POST["submit"] ?>' != "") {
                $("#userName").val('<?php echo $_POST["userName"] ?>');
                $("#value1").val('<?php echo $_POST["value1"] ?>');
                $("#<?php echo $_POST["currencyFirst"] ?>1").attr("selected", "selected");
                $("#<?php echo $_POST["currencySecond"] ?>2").attr("selected", "selected");
                switch ("<?php echo $_POST["currencyFirst"] ?>") {
                    case "UAH":
                        $("#sign1").html('<img class="img" src="img/uah.png" height="15px" alt=""/>');
                        value1 = 1;
                        break;
                    case "USD":
                        $("#sign1").html('<img class="img" src="img/usd.png" height="15px" alt=""/>');
                        value1 = <?php echo $last_values["UAH_USD_BUY"] ?>;
                        value2 = 1;
                        $("#value2").val(roundPlus(($("#value1").val() * (value1 / value2)), 2));//механизм подстановки курса
                        $("#error").html("");
                        $("#sign2").html('<img class="img" src="img/uah.png" height="15px" alt=""/>');
                        $("#currencySecond").html('<option id="UAH2" value="UAH">UAH украинская гривна</option>');
                        break;
                    case "EUR":
                        $("#sign1").html('<img class="img" src="img/eur.png" height="15px" alt=""/>');
                        value1 = <?php echo $last_values["UAH_EUR_BUY"] ?>;
                        value2 = 1;
                        $("#value2").val(roundPlus(($("#value1").val() * (value1 / value2)), 2));//механизм подстановки курса
                        $("#error").html("");
                        $("#sign2").html('<img class="img" src="img/uah.png" height="15px" alt=""/>');
                        $("#currencySecond").html('<option id="UAH2" value="UAH">UAH украинская гривна</option>');
                        break;
                    case "RUB":
                        $("#sign1").html('<img class="img" src="img/rub.png" height="15px" alt=""/>');
                        value1 = <?php echo $last_values["UAH_RUB_BUY"] ?>;
                        value2 = 1;
                        $("#value2").val(roundPlus(($("#value1").val() * (value1 / value2)), 2));//механизм подстановки курса
                        $("#error").html("");
                        $("#sign2").html('<img class="img" src="img/uah.png" height="15px" alt=""/>');
                        $("#currencySecond").html('<option id="UAH2" value="UAH">UAH украинская гривна</option>');
                        break;
                    case "CNY":
                        $("#sign1").html('<img class="img" src="img/cny.png" height="15px" alt=""/>');
                        value1 = <?php echo $last_values["UAH_CNY_BUY"] ?>;
                        value2 = 1;
                        $("#value2").val(roundPlus(($("#value1").val() * (value1 / value2)), 2));//механизм подстановки курса
                        $("#error").html("");
                        $("#sign2").html('<img class="img" src="img/uah.png" height="15px" alt=""/>');
                        $("#currencySecond").html('<option id="UAH2" value="UAH">UAH украинская гривна</option>');
                        break;
                }
                switch ("<?php echo $_POST["currencySecond"] ?>") {
                    case "UAH":
                        $("#sign2").html('<img class="img" src="img/uah.png" height="15px" alt=""/>');
                        value2 = 1;
                        break;
                    case "USD":
                        $("#sign2").html('<img class="img" src="img/usd.png" height="15px" alt=""/>');
                        value2 = <?php echo $last_values["UAH_USD_SELL"] ?>;
                        break;
                    case "EUR":
                        $("#sign2").html('<img class="img" src="img/eur.png" height="15px" alt=""/>');
                        value2 = <?php echo $last_values["UAH_EUR_SELL"] ?>;
                        break;
                    case "RUB":
                        $("#sign2").html('<img class="img" src="img/rub.png" height="15px" alt=""/>');
                        value2 = <?php echo $last_values["UAH_RUB_SELL"] ?>;
                        break;
                    case "CNY":
                        $("#sign2").html('<img class="img" src="img/cny.png" height="15px" alt=""/>');
                        value2 = <?php echo $last_values["UAH_CNY_SELL"] ?>;
                        break;
                }
                /*Подстановка курса при загрузке страницы*/
                $("#value2").val(roundPlus(($("#value1").val() * (value1 / value2)), 2));//механизм подстановки курса

                /*Выдача сообщения о успешном проведении операции*/
                $("#success").html('Операция обмена успешно произведена<br>Вы можете провести ещё одну операцию');
            }

            /*Вычисление поля "Вы получите" при вводе с клавиатуры*/
            $("#value1").keyup(function (event) {
                $("#value2").val(roundPlus(($("#value1").val() * (value1 / value2)), 2));//механизм подстановки курса
            });
        </script>
        <script type="text/javascript" src="sign2.js"></script>
    </form>
</div>
</body>
</html>