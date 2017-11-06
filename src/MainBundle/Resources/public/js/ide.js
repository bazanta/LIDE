
$("#editor").height( $("#block-editor").height() - $("#editor-toolbar").height());

$("#output-console").height( $("#console-block").height() - $("#input-console").height());

$("#form-new-file").css("top", $("#editor-toolbar").height());

//Initialisation de l'editeur
var editor = ace.edit("editor");
editor.setTheme("ace/theme/pastel_on_dark");
editor.$blockScrolling = Infinity

var modeles;
var files;
var currentFile = -1;

function File(name, content){
  this.name = name;
  this.content = content;
}

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

      modeles = response.modeles;
      console.log(modeles);
      console.log(modeles[0]);

      $("#navbarDropdownMenuLink").html("Editeur : " + response.name);
      document.title = "LIDE - " + response.name;

      $("#select-file").html("");
      currentFile  = -1;
      files = new Array();
      addFile("main." + modeles[0].ext, modeles[0].model)

      //Modification du formulaire de nouveau fichier
      $("#select-modele").html("")
      $("#select-modele").append(
        $("<option></option")
          .attr("value", -1)
          .text("Fichier vide")
      );
      for(var i = 0; i < modeles.length; i++){
        $("#select-modele").append($("<option></option")
          .attr("value", i)
          .text("." + modeles[i].ext )
        );
      }
      $("#select-modele").val(-1);
      console.log(modeles[0].model);
      $("#show-model").text(modeles[0].model).html();
      $("#show-modelhttps://trello.com/b/PCq4IDlC/lide-licence-informatic-development-environment").html($("#show-model").html().replace(/\n/g,"<br>"));
    },
  });
}

function changeActiveFileTo(id){
  if(id >= 0 && id < files.length){
    console.log("Change file to "  + id);
    console.log(files);
    console.log(files[id].content);
    if(currentFile != -1){
      files[currentFile].content = editor.getValue();
    }
    editor.setValue(files[id].content);
    $("#select-file").val(id);
    currentFile = id;
  }
}

function addFile(name, content){

  var f = new File(name, content);
  console.log("New file : ");
  console.log(f);
  files.push(f);

  $("#select-file").append("<option value=\"" + (files.length - 1)  + "\">" + name + "</option>\n");
  changeActiveFileTo(files.length - 1);
}

$("#select-file").change(function (){
  changeActiveFileTo($("#select-file").val());
});

$("#select-modele").change(function(){
  var ext;
  if($( this ).val() == -1){
    $("#show-model").text("").html();
    ext = modeles[0].ext;
    $("#show-model-btn").prop("disabled", true);
  }
  else{
    $("#show-model-btn").prop("disabled", false);
    $("#show-model").text(modeles[ $( this ).val() ].model).html();
    $("#show-model").html($("#show-model").html().replace(/\n/g,"<br>"));
    ext = modeles[ $( this ).val() ].ext;
  }
  $("#extension-addon").text("." + ext);
});

function removeFile(id){
  if(id >= 0 && id < files.length){
    currentFile = -1;

    var newFiles = new Array();
    for(var i = 0; i < files.length; i++){
      if( i != id){
        console.log("Push " + id);
        newFiles.push(files[i]);
      }
    }
    files = newFiles;

    console.log("Suppression de " + id + ". files = " + files);
    if(files.length == 0){
      console.log("Aucun fichier. Création d'un fichier de base");
      addFile("main." + modeles[0].ext, modeles[0].model);
    }else{
      changeActiveFileTo(0);
    }

    $("#select-file").html("");
    for(var i = 0; i < files.length; ++i){
      $("#select-file").append(
        $("<option></option>")
          .attr("value", i)
          .text(files[0].name)
      );
    }
  }
}

$("#rm_file").click(function (){
  if(confirm("Attention ! Toute donnée non sauvegardées seront perdu !")){
    removeFile(currentFile);    
  }
});

function saveFile(id){
  if(id >= 0 && id < files.length){
    if(id == currentFile){
      files[currentFile].content = editor.getValue();
    }
    var blob = new Blob( [files[id].content] , {type: "text/plain;charset=utf-8"});
    saveAs(blob, files[id].name);
  }
}

function saveAllFile(){
  for(i = 0; i < files.length; ++i){
    saveFile(i);
  }
}

$("#btn-save").click(saveAllFile);


$("#btn-create-file").click(function (){
  var fileContent = "";
  var fileName = $( "#new-file-name" ).val();
  var idModel = $(" #select-modele").val();

  if(fileName == ""){
    fileName = "file";
  }

  if(idModel == -1){
    fileName = fileName + "." + modeles[0].ext;
  }
  else{
    fileName = fileName + "." + modeles[idModel].ext;
    fileContent = modeles[idModel].model;
  }

  addFile(fileName, fileContent);
  $("#add_file").click();
})

requestAndSetLanguage($(".choix-langage-selected").attr("data-id"));

$("#add_file").click(function(){
  console.log("Add file button");
  $("#form-new-file").toggleClass("hidden-new-file");
  $("#form-new-file").toggleClass("showed-new-file");
})

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
