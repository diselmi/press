$(document).ready(function () {
    var listeEmails = null;
    var max = 0;
    //console.log(listeEmails);
    ajouterEmail();
    ajouterEmail();
    ajouterEmail();

// Copier le lien
$("#copy_btn").click(function( event ) {
    //console.log("btn_media_add clicked");
    event.preventDefault();
        if ($("#lien").val() != "") {
            $("#lien").select();
            document.execCommand('copy');
            console.log("selected");
        }
    
});

// Ajouter un champ media
$("#btn_email_add").click(function( event ) {
    console.log("btn_media_add clicked");
    event.preventDefault();
    ajouterEmail();
    
});

function ajouterEmail(){
    max++;
    var block = $( "<div>", {id: "added_email_"+max} ).addClass("form-group input-group addedEmail");
    
    var btnRemove = $( "<div class='input-group-btn red'>" ).css("color", "#8c0909").css("font-size", "16px");
    btnRemove.html("<button class='btn btn-danger'><i class='glyphicon glyphicon-remove'></i></button>");
    btnRemove.click(function(){
        $(this).parent().remove();
        max--;
        //totalMedias --;
        checkActivation();
    });
    
    var fieldEmail = $( "<div class=''>" )
    fieldEmail.html("<input class='form-control' type='email' name='emails[]' placeholder='tapez un email ici' required='required'>");
    
    

    btnRemove.appendTo(block);
    //fieldEmail.appendTo(block);
    block.append(fieldEmail);
    block.appendTo("#all_emails");
    checkActivation();
    console.log(max);
    
}

function checkActivation(){
    if (max >0) {
        $("#share_submit_btn").attr("disabled",null);
    }else {
        $("#share_submit_btn").attr("disabled","disabled");
    }
}



});


