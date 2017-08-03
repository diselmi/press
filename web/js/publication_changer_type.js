$(document).ready(function () {
    
    changer();
    //changer les permissions
    $( "#publication-type_contenu" ).change(function() {
        //document.getElementById("newFonctionInput").style = "display: block";
        changer();
    });
    
});

function changer(){
    value = $("#publication-type_contenu option:selected").val();
    //console.log(value);
    if (value == 0) {
        //$("#newFonctionInput").css("display", "inherit");   
        $("#publication_contenu_doc").css("display", "none");
        $("#publication_contenu_html").css("display", "block");
        //console.log("cl");
    }else {
        $("#publication_contenu_doc").css("display", "block");
        $("#publication_contenu_html").css("display", "none");
    }
}
