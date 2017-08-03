
var currentId = 0;
var currentUser = null;
var connectedUser = null;

$(document).ready(function () {
    //hideChat();
    initForm();
    
    $(".chat-user-link").click(function( event ) {
        event.preventDefault();
        var link = $(event.currentTarget);
        currentId = link.attr("data");
        //console.log(link.attr("data"));
        getConversation(currentId);  
    });
    
    if (getFromUrl("id")) { currentId = getFromUrl("id"); getConversation(currentId); }
    
});

function hideChat() {
    $('#chat-container').hide();
}
function showChat() {
    $('#chat-container').show();
}

function initForm() {
    $("#chat-form").submit(function(e) {

    var url = "/chat/send";
    
    console.log($("#chat-form").serialize());

    $.ajax({
           type: "POST",
           url: url,
           data: $("#chat-form").serialize(), // serializes the form's elements.
           success: function(data)
           {
               getConversation(currentId);
           }
         });

    e.preventDefault(); // avoid to execute the actual submit of the form.
});
}

function getConversation(id) {
    $("#destinataire").val(id);
    console.log($("#chat-form [name='destinataire'] "));
    console.log($(""));
    $.post("/chat/conversation", {id: id, _csrf : yii.getCsrfToken()}, function(liste, status){
        console.log(liste);
        if (liste['status']==1) {
            currentUser = liste['user'];
            connectedUser = liste['cUser'];
            $("#chat-box").empty();
            $("#chat-container").attr("style", "display : inherit");
            $.each(liste['messages'], addItem);
        }
    })
}

function addItem(index, msg) {
    //console.log(currentUser);
    var direction = 0; // 0:ltr | 1:rtl
    var item = $("<div>");
    var image = $("<img alt='user image' class='offline'>");
    var date = $("<small class='text-muted'><i class='fa fa-clock-o'></i> "+msg.date_envoie+"</small>");
    var conteneur = $(" <div> ");
    
    var message = $("<div class='contenu-msg'>").append(msg.texte);
    
    if (msg.expediteur == currentId) {
        //console.log("non");
        var nom = $("<div class='name text-light-blue'>")
            .append(currentUser.prenom+" "+currentUser.nom);
        image.attr("src",currentUser.photo);
        item.addClass("item");
        conteneur.addClass("message");
        conteneur.append(nom).append(date);
        if (msg.document == null) { conteneur.append(message); }
        item.append("<hr>").append(image).append(conteneur);
    }else {
        direction = 1;
        //console.log("oui");
        var nom = $("<div class='name text-light-blue'>")
            .append(connectedUser.prenom+" "+connectedUser.nom);
        image.attr("src",connectedUser.photo);
        date.addClass("date-from-me");
        item.addClass("item-from-me");
        conteneur.addClass("message-from-me");
        conteneur.append(nom).append(date);
        if (msg.document == null) { conteneur.append(message); }
        item.append("<hr>").append(image).append(conteneur);
    }
    
    if (msg.document !== null) {
        console.log("document");
        var filename = $("<p class='filename'>").append(msg.document);
        var document = $("<div class='attachement-from-me'>");
        if (direction) {
            var cheminComplet = connectedUser.chemin+msg.document;
            document.append("<div class='pull-left'><a href='"+ cheminComplet +"' class='btn btn-primary btn-sm btn-flat'><i class='glyphicon glyphicon-save'></i></a></div>");
        }else {
            var cheminComplet = currentUser.chemin+msg.document;
            document.append("<div class='pull-right'><a href='"+ cheminComplet +"' class='btn btn-primary btn-sm btn-flat'><i class='glyphicon glyphicon-save'></i></a></div>");
        }
        
        document.prepend(filename);
        item.append(document);
    }
    
    $("#chat-box").append(item);
}

function getFromUrl(variable)
{
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i=0;i<vars.length;i++) {
            var pair = vars[i].split("=");
            if(pair[0] == variable){return pair[1];}
    }
    return false;
}


