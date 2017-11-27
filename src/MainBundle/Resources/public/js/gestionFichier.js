/*******************************************************************************
 *                       GESTION DES FICHIERS                                  *
 *******************************************************************************/
  function File(name, content){
    this.name = name;
    this.content = content;
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

 function existFile(name){
   var i;
   for(i = 0; i < files.length; i++){
     if(name == files[i].name){
       return true;
     }
   }
 }

 //Ajout d'un fichier
 function addFile(name, content){
     if(name == ""){
         swal( "Erreur lors de la création du fichier",
             "Impossible de créer un fichier avec un nom vide",
             "error"
         );
         return;
     }
     if(existFile(name)){
         swal("Erreur lors de la création du fichier",
             "Le fichier " + name + " existe déjà. Veuillez choisir un autre nom pour votre fichier",
             "error");
         return;
     }

   var f = new File(name, content);
   console.log("New file : ");
   console.log(f);
   files.push(f);

   $("#select-file").append(
     $("<option></option>")
              .attr("value", files.length - 1)
              .text(name)
            );
   changeActiveFileTo(files.length - 1);
 }

//Fonction supprimant un fichier
 function removeFile(id){
   if(files.length <= 1){
     console.log("Un seul fichier " + files.length);
     return;
   }
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

     changeActiveFileTo(0);

     //Recréation de la liste déroulante
     //TODO : simple suppression de l'élément html correspondant au fichier.
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

 //Changement du fichier actifs via la liste déroulante dans la toolbar
 $("#select-file").change(function (){
   changeActiveFileTo($("#select-file").val());
 });

 //Synchronisation du modèle sélectionné dans le formulaire du nouveau fichier le reste du formulaire.
 $("#select-modele").change(function(){
   var ext;
   if($( this ).val() == -1){
     $("#show-model").text("").html();
     $("#show-model-btn").prop("disabled", true);

     $("#extension-addon").addClass("d-none");

   }
   else{
     $("#show-model-btn").prop("disabled", false);
     $("#show-model").text(modeles[ $( this ).val() ].model).html();
     $("#show-model").html($("#show-model").html().replace(/\n/g,"<br>"));
     ext = modeles[ $( this ).val() ].ext;
     $("#extension-addon").text("." + ext);
     $("#extension-addon").removeClass("d-none");
   }
 });

 //Bind bouton de suppression de fichier
 $("#rm_file").click(function (){
   if(confirm("Attention ! Toute donnée non sauvegardées seront perdu !")){
     removeFile(currentFile);
     if(files.length == 1){
       console.log("disabled");
       $(this).prop("disabled", true);
     }
   }
 });

 function createFile(){
   var fileContent = "";
   var fileName = $( "#new-file-name" ).val();
   var idModel = $(" #select-modele").val();

   if(fileName == ""){
     fileName = "file";
   }

   if(idModel == -1){
     //Création d'un fichier sans modèle, extension défini par l'utilisateur.
   }
   else{
     fileName = fileName + "." + modeles[idModel].ext;
     fileContent = modeles[idModel].model;
   }

   addFile(fileName, fileContent);

   $("#form-new-file").toggleClass("hidden-new-file");
   $("#form-new-file").toggleClass("showed-new-file");

   if(files.length > 1){
     console.log("not disabled");
     $("#rm_file").prop("disabled", false);
   }
 }

 //Bind bouton de création de fichier
 $("#btn-create-file").click(function (){
   createFile();
 });

 $("#add_file").click(function(){
   console.log("Add file button");
   $("#form-new-file").toggleClass("hidden-new-file");
   $("#form-new-file").toggleClass("showed-new-file");
 });

$("#close-new-file").click(function (){
  $("#form-new-file").addClass("hidden-new-file");
  $("#form-new-file").removeClass("showed-new-file");
});

/*******************************************************************************
 *                    SAUVEGARDE DES FICHIERS                                  *
 *******************************************************************************/

const SAVE_CURRENT_FILE = "file";
const SAVE_ARCHIVE = "archive";
const SAVE_ALL_FILES = "all-files";

var CURRENT_SAVE_METHOD = SAVE_CURRENT_FILE;

 function saveFile(id){
   //Sauvegarde d'un fichier via FileSaver.js
   if(id >= 0 && id < files.length){
     if(id == currentFile){
       files[currentFile].content = editor.getValue();
     }
     var blob = new Blob( [files[id].content] , {type: "text/plain;charset=utf-8"});
     saveAs(blob, files[id].name);
   }
   else{
     console.log("Unknown file id : " + id);
   }
 }

 function saveAllFiles(){
   for(i = 0; i < files.length; ++i){
     saveFile(i);
   }
 }

 function saveAllFilesAsArchive(){
   //Sauvegarde dans une archive zip avec JSZip + FileSaver.js
   var zip = new JSZip();
   for(i = 0; i < files.length; ++i){
     zip.file(files[i].name, files[i].content);
   }
   zip.generateAsync({type:"blob"})
   .then(function(content) {
     saveAs(content, "archive.zip");
   });
 }

function isValidSaveMethod(saveMethod){
  return saveMethod == SAVE_CURRENT_FILE || saveMethod == SAVE_ARCHIVE || saveMethod == SAVE_ALL_FILES;
}

function performSaveMethod(saveMethod){
  if(saveMethod == SAVE_CURRENT_FILE){
    saveFile(currentFile);
  }
  else if(saveMethod == SAVE_ARCHIVE){
    saveAllFilesAsArchive();
  }
  else if(saveMethod == SAVE_ALL_FILES){
    saveAllFiles();
  }
}

function changeSaveMethod(saveMethod){
  if(isValidSaveMethod(saveMethod)){
    CURRENT_SAVE_METHOD = saveMethod;
  }
  else{
    console.log("Unknown save method : " + saveMethod);
  }
}

$(".save-method").click(function(){
  console.log()

  changeSaveMethod( $( this ).attr("data-save-method"));
  $("#btn-save").html( $( this ).html());
  performSaveMethod(CURRENT_SAVE_METHOD);
})

$("#btn-save").click(function (){
  performSaveMethod(CURRENT_SAVE_METHOD);
});
