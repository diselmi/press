/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//les definitions js
function newFonctionCheck(){
    
    //document.getElementById("newFonctionInput").style = "display: block";
}

function userRoleChanged(s) {
    //console.log(s);
    //console.log(s.value);
    if (s.value == "client") {
        // pjax
        //$.pjax({url: "/role/get-role-list", container: '#role_field_pjax'})
        $("#user_form_client").css("display", "inherit");
        $("#user_form_role_admin").css("display", "none");

    }else {
        
        $("#user_form_role_admin").css("display", "inherit");
        $("#user_form_client").css("display", "none");
    }
}

//Jquery

// hide nes Fonction field
$( "#user-fonction" ).change(function() {
    //document.getElementById("newFonctionInput").style = "display: block";
    value = $("#user-fonction option:selected").val();
    if (value < 0) {
        $("#newFonctionInput").css("display", "inherit");   
    }
});

// Form pre submit
$(".user-form").submit(function( event ) {
    type = $("#user-role_type").val();
    role_admin = $("#user_form_role_admin");
    console.log(type);
    if (type == "client") {
        role_admin.remove();
    }
    return ;
    //event.preventDefault();
});


//les appels
/////////////////

$(function() {
    //newFonctionCheck();
    if ($("#user-role_type").get(0)) {
        // charger les roles 
        userRoleChanged($("#user-role_type").get(0));
    }
    
});


