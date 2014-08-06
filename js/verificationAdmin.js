/*Ввод в поле "Сколько" только цифр*/
$(document).ready(function () {
    $("input").keydown(function (event) {
        // Разрешаем: backspace, delete, tab и escape
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||
            // Разрешаем: Ctrl+A, Ctrl+C, Ctrl+V
            (event.keyCode == 65 && event.ctrlKey === true) || //A
            (event.keyCode == 67  && event.ctrlKey === true) || //C
            (event.keyCode == 86 && event.ctrlKey === true) || //V
            (event.keyCode == 190) || (event.keyCode == 110) || //.
            // Разрешаем: home, end, влево, вправо
            (event.keyCode >= 35 && event.keyCode <= 39)) {
            // Ничего не делаем
            return;
        }
        else {
            // Обеждаемся, что это цифра, и останавливаем событие keypress
            if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault();
            }
        }
    });
});