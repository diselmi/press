$(document).ready(function () {
    var listeMedia = null;
    var max = 0;
    //console.log(listeMedia);
    getMedia();

// Ajouter un champ media
$("#btn_media_add").click(function( event ) {
    //console.log("btn_media_add clicked");
    event.preventDefault();
    ajouterMedia();
    
});

function ajouterMedia(){
    max++;
    var block = $( "<div>", {id: "added_media_"+max} ).addClass("form-group input-group col-md-12 addedMedia");
    
    var btnRemove = $( "<div class='input-group-btn red'>" ).css("color", "#8c0909").css("font-size", "16px");
    btnRemove.html("<button class='btn btn-danger'><i class='glyphicon glyphicon-remove'></i></button>");
    btnRemove.click(function(){
        $(this).parent().remove();
        //totalMedias --;
    });
    
    var fieldMedia = $( "<select id='media_select_"+max+"' media-num="+max+" class='form-control col-md-6' name='journaliste_media["+max+"][id_media]'>" );
    fieldMedia.change(function() {
        changerTypes($(this).attr('media-num'));
    });
    fieldMedia.append(" <option value='-1' disabled selected> Choisir une media ")
    $.each(listeMedia, function(i, media) {
        //console.log(media.nom);
        fieldMedia.append(" <option value='"+ media.id +"'>"+ media.nom +"</option> ");
    });

    var allCases = $("<div class='col-sm-5' id='media_types_"+max+"' >");

    btnRemove.appendTo(block);
    $("<div class='col-sm-6'>").append(fieldMedia).appendTo(block);
    allCases.appendTo(block);
    block.appendTo("#all_assoc_media");
    
}

function getMedia(){
    console.log("liste medias reçue");
    $.get("/journaliste/media-subform", function(data, status){
        //console.log("Data: " + data + "\nStatus: " + status);
        //return data;
        listeMedia = data;
        if (getFromUrl("id")) { initMedia(getFromUrl("id")); }

    }); 
}

function initMedia(id){
    console.log("id== "+id);
    $.get("/journaliste/medias?id="+id, function(liste, status){
        console.log(liste);
        $.each(liste, function(i, media) {
            console.log("j_m ajoutee");
            ajouterMedia();
            $("#media_select_"+max).val(media['media']);
            changerTypes(max);
            
            if(media['tv']) $("input[name='journaliste_media["+max+"][tv]']").prop('checked', true);
            if(media['radio']) $("input[name='journaliste_media["+max+"][radio]']").prop('checked', true);
            if(media['j_papier']) $("input[name='journaliste_media["+max+"][j_papier]']").prop('checked', true);
            if(media['j_electronique']) $("input[name='journaliste_media["+max+"][j_electronique]']").prop('checked', true);
            
            
        });
    });
    
    
    
    
}


function changerTypes(i) {
    var allCases = $("#media_types_"+i);
    var selected = $("#media_select_"+i+ " option:selected").val();
    if(selected){
        //console.log("ssss: "+selected);
        var media = null;
        $.each(listeMedia, function(i, m) {
            //console.log(m.id);
            if(m.id == selected){
                media = m;
            }

        })
        allCases.text("");
        if(media.tv == 1){ allCases.append("<input type='checkbox' name='journaliste_media["+i+"][tv]' /> Tv "); }
        if(media.radio == 1){ allCases.append("<input type='checkbox' name='journaliste_media["+i+"][radio]' /> Radio "); }
        if(media.j_papier == 1){ allCases.append("<input type='checkbox' name='journaliste_media["+i+"][j_papier]' /> journal papier "); }
        if(media.j_electronique == 1){ allCases.append("<input type='checkbox' name='journaliste_media["+i+"][j_electronique]' /> journal electronique "); }
        
        $("#added_media_"+i).append(allCases);
    }
    
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



});


