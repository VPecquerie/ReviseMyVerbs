$(document).ready(timerMessage(10000));


function timerMessage(time) {
console.log("Entrer dans timerMessage()");
console.log('time = '+time);
$("#MessageAffichage").hide();
console.log('Sortie de timerMessage()');
}