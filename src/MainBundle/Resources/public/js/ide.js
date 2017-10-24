var editor = ace.edit("editor");
editor.getSession().setMode(aceMode);
editor.setTheme("ace/theme/dawn");
editor.$blockScrolling = Infinity
document.getElementById('editor').style.fontSize='16px';

function requestAndSetLanguage(language){
  console.log("Selection language : " + language);
  $.ajax({
    type: "POST",
    url: "app_dev.php/language_info",
    data: {
           lang : language
    },
    success: function(response) {
      console.log(response);
      editor.getSession().setMode(response.ace);
      editor.setValue(response.model);
      $("#navbarDropdownMenuLink").html("Editeur : " + response.name);
    },
  });
}

$("#console-toogle").click(function (){
  $("#console").toggleClass("d-none");
  $("#console-block").toggleClass("console-block-open");
  $("#console-block").toggleClass("col-4");
  $("#console-block").toggleClass("console-block-collapsed");

  $( this ).toggleClass("console-toogle-open");
  $( this ).toggleClass("console-toggle-close");

  $(".console-toggle-close").html("<");
  $(".console-toogle-open").html(">"); //Sale

  $("#block-editor").toggleClass("col-12");
  $("#block-editor").toggleClass("col-8");

});

//Selection d'un nouveau langage

function clickLangage(){
  if(confirm("Attention ! Toute donnée non sauvegardées seront perdu !")){
    requestAndSetLanguage( parseInt($( this ).attr("data-id")) );
    var oldSelected = $(".choix-langage-selected");
    oldSelected.removeClass("choix-langage-selected");
    oldSelected.addClass("choix-langage");
    oldSelected.click(clickLangage);

    newSelected = $( this );
    newSelected.removeClass("choix-langage");
    newSelected.addClass("choix-langage-selected");
    newSelected.unbind("click");
  }else{
    //DO NOTHING FOR NOW
  }
}
$(".choix-langage").click(clickLangage);
