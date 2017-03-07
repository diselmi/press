/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//les definitions js
function newFonctionCheck(){
    
    //document.getElementById("newFonctionInput").style = "display: block";
}

//Jquery
$( "#user-fonction" ).change(function() {
    //document.getElementById("newFonctionInput").style = "display: block";
    value = $("#user-fonction option:selected").val();
    if (value < 0) {
        $("#newFonctionInput").css("display", "inherit");   
    }
});


//les appels
//newFonctionCheck();


