// Первый вариант
document.getElementsByClassName('c')[0].style.color = "green";

// Второй вариант
document.getElementById("t").className = "a";

// Третий вариант используя JQuery
$(".c").css( "color", "green" );