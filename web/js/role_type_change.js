$(document).ready(function () {
    
    changePermissionBlock();
    //changer les permissions
    $( "#role-type" ).change(function() {
        //document.getElementById("newFonctionInput").style = "display: block";
        changePermissionBlock();
    });
    
});

function changePermissionBlock(){
    value = $("#role-type option:selected").text();
    //console.log(value);
    if (value == "client") {
        //$("#newFonctionInput").css("display", "inherit");   
        $("#role_type_admin").css("display", "none");
        $("#role_type_client").css("display", "block");
        console.log("cl");
    }else {
        $("#role_type_client").css("display", "none");
        $("#role_type_admin").css("display", "block");
    }
}
