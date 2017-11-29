//Initialisation de l'editeur

var editor;
var modeles;

var files;

var currentFile = -1;

//Initialisation de l'editeur
var editor = ace.edit("editor");

/*******************************************************************************
 *                       GESTION DES FICHIERS                                  *
 *******************************************************************************/

/* Fichier -> nom et contenu
 * L'ensemble des fichiers est stocké dans un tableau
 *
 */

function File(name, content) {
    this.name = name;
    this.content = content;
}

/**
 * Lorsque l'on change le fichier en cours, le contenu du fichier chargé dans l'éditeur est sauvegardé dans le tableau files,
 * puis le contenu du nouveau fichier en cours est chargé dans l'éditeur depuis le tableau
 *
 * @param id l'index du nouveau fichier actif.
 */
function changeActiveFileTo(id) {
    if (id == currentFile) {
        return //Demande de changement sur le fichier courant -> rien à faire
    }
    if (id >= 0 && id < files.length) {
        console.log("Change file to " + id);
        console.log(files);
        console.log(files[id].content);
        if (currentFile != -1) {
            files[currentFile].content = editor.getValue();
        }
        editor.setValue(files[id].content);
        $("#select-file").val(id);
        currentFile = id;

        $(".file-selected-tab").css("background-color", "");
        $("file-selected-tab").css("color", "");
        $(".file-selected-tab").removeClass("file-selected-tab");
        $(".file-tab[data-file='" + id + "']").addClass("file-selected-tab");

        synchroniseEditorAndSelectedTab();
    }
}

function synchroniseEditorAndSelectedTab(){
    $(".file-selected-tab").css("background-color", $("#editor").css("background-color"));
    $(".file-selected-tab").css("color", $("#editor").css("color"));
}

/**
 * Cette fonction détermine si le fichier de nom donné existe dans le tableau files
 * @param name le nom du fichier à rechercher
 * @returns {boolean} Vrai si le fichier existe, faux sinon
 */
function existFile(name) {
    var i;
    for (i = 0; i < files.length; i++) {
        if (name == files[i].name) {
            return true;
        }
    }
    return false;
}

//Ajout d'un fichier
/**
 * Ajoute un fichier avec de nom et de contenu données dans l'éditeur, met à jour la GUI.
 *
 * @param name le nom du fichier à ajouter.
 * @param content le contenu du fichier à ajouter.
 */
function addFile(name, content) {
    var f = new File(name, content);
    console.log("New file : ");
    console.log(f);
    files.push(f);

    $("#select-file").append(
        $("<option></option>")
            .attr("value", files.length - 1)
            .text(name)
    );
    var newTab = $("<div></div>").attr("class", "col file-tab").attr("data-file", files.length - 1).text(name);
    $("#file-tabs-row").append(newTab);
    console.log("Width " + newTab.width());

    newTab.click(function () {
        console.log("Clicked tab " + $(this).attr("data-file"));
        changeActiveFileTo($(this).attr("data-file"));
    });

    changeActiveFileTo(files.length - 1);

    return true;
}


function addFileCorrector(name, content){
    if(name == "" || existFile(name)){
        var errorMsg;
        if(name == "") {
            errorMsg = "Le nom de fichier est vide.";
        }
        else{
            errorMsg = "Le fichier " + name + " existe déjà.";
        }
        swal({
                title : "Erreur lors de la création du fichier " + name,
                type: "error",
                text : errorMsg + " Veuillez choisir un nouveau nom pour le fichier, ou annuler sa création",
                input : "text",
                inputValue: name,
                showCancelButton : true,
                confirmButtonText : "Créer",
                cancelButtonText : "Annuler",
                allowOutsideClick : false,
                inputValidator : function(value){
                    if(!value){
                        return !value && "Veuillez choisir un nom de fichier";
                    }
                    else{
                        return existFile(value) && ("Le fichier " + value + " existe déjà !")
                    }
                }
            }
        ).then(function(result){
            console.log(result);
            if(result.value){
                console.log("New file name " + result.value)
                name = result.value;
                return addFile(name, content);
            }
            else{
                console.log(result)
                return false;
            }
        });
        return true;
    }
    else{
        return addFile(name, content);
    }
}

/**
 * Supprime le fichier en position id dans le tableau files.
 * @param id
 */
function removeFile(id) {
    if (files.length <= 1) {
        console.log("Un seul fichier " + files.length);
        return;
    }
    if (id >= 0 && id < files.length) {
        currentFile = -1;

        var newFiles = new Array();
        for (var i = 0; i < files.length; i++) {
            if (i != id) {
                console.log("Push " + id);
                newFiles.push(files[i]);
            }
        }
        files = newFiles;

        console.log("Suppression de " + id + ". files = " + files);


        //Recréation de la liste déroulante
        //TODO : simple suppression de l'élément html correspondant au fichier.
        $("#select-file").html("");
        $("#file-tabs-row").html("");
        console.log("new tab");
        for (var i = 0; i < files.length; ++i) {
            $("#select-file").append(
                $("<option></option>")
                    .attr("value", i)
                    .text(files[i].name)
            );
            $("#file-tabs-row").append(
                $("<div></div>").attr("class", "col file-tab").attr("data-file", i).text(files[i].name)
            );
        }
        $(".file-tab").click(function () {
            changeActiveFileTo($(this).attr("data-file"));
        });

        changeActiveFileTo(0);

    }
}


//Synchronisation du modèle sélectionné dans le formulaire du nouveau fichier le reste du formulaire.
$("#select-modele").change(function () {
    var ext;
    if ($(this).val() == -1) {
        $("#show-model").text("").html();
        $("#show-model-btn").prop("disabled", true);

        $("#extension-addon").addClass("d-none");

    }
    else {
        $("#show-model-btn").prop("disabled", false);
        $("#show-model").text(modeles[$(this).val()].model).html();
        $("#show-model").html($("#show-model").html().replace(/\n/g, "<br>"));
        ext = modeles[$(this).val()].ext;
        $("#extension-addon").text("." + ext);
        $("#extension-addon").removeClass("d-none");
    }
});

//Bind bouton de suppression de fichier
$("#rm_file").click(function () {
    swal({
        title: 'Êtes vous sûr ?',
        text: "Cette action est irréversible",
        type: 'warning',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-danger',
        cancelButtonClass: 'btn btn-primary',
        confirmButtonText: 'Supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if(result.value){
        removeFile(currentFile);
        if (files.length == 1) {
            console.log("disabled");
            $(this).prop("disabled", true);
        }
    }
    });
});

/**
 * Fonction appelé lorsque la création du fichier est demandé.
 * Récupère les informations necessaires dans le DOM, puis créer le fichier via la fonction addFile.
 */
function createFile() {
    var fileContent = "";
    var fileName = $("#new-file-name").val();
    var idModel = $(" #select-modele").val();

    if (fileName == "") {
        fileName = "file";
    }

    if (idModel == -1) {
        //Création d'un fichier sans modèle, extension défini par l'utilisateur.
    }
    else {
        fileName = fileName + "." + modeles[idModel].ext;
        if($("#useModel").is(":checked")){
            fileContent = modeles[idModel].model;
        }
    }

    var created = addFileCorrector(fileName, fileContent);

    if (files.length > 1) {
        console.log("not disabled");
        $("#rm_file").prop("disabled", false);
    }

    return created;
}

//Bind bouton de création de fichier
$("#btn-create-file").click(function () {

    if (createFile())
    {
        $("#modal-new-file").modal('hide');
    }

});

//Changement du fichier actifs via la liste déroulante dans la toolbar
$("#select-file").change(function () {
    changeActiveFileTo($("#select-file").val());
});


/*******************************************************************************
 * Import de fichier
 *******************************************************************************/

function handleFileImport(){
    if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
        alert('The File APIs are not fully supported in this browser.');
        return;
    }

    var importFiles = $("#import-file-input").prop("files");
    if (!importFiles) {
        alert("This browser doesn't seem to support the `files` property of file inputs.");
        return false;
    }
    else if (!importFiles[0]) {
        alert("Veuillez selectionner un fichier avant de cliquer 'Importer'");
        return false;
    }
    else {
        for(var i = 0; i < importFiles.length; ++i){
            var fr = new FileReader();
            var file = importFiles[i];
            fr.onload = function(){
                addFileCorrector( file.name, fr.result);
            };
            fr.readAsText(file);
        }
        return true;
    }
}

$("#btn-import-file").click(function(){
    if(handleFileImport()){
        $("#modal-import-file").modal('hide');
    }
});

/*******************************************************************************
 *                    SAUVEGARDE DES FICHIERS                                  *
 *******************************************************************************/

const SAVE_CURRENT_FILE = "file";
const SAVE_ARCHIVE = "archive";
const SAVE_ALL_FILES = "all-files";

var CURRENT_SAVE_METHOD = SAVE_CURRENT_FILE;

/**
 * Sauvegarde le fichier via téléchargement à la position id dans le tableau file
 * @param id
 */
function saveFile(id) {
    //Sauvegarde d'un fichier via FileSaver.js
    if (id >= 0 && id < files.length) {
        if (id == currentFile) {
            files[currentFile].content = editor.getValue();
        }
        var blob = new Blob([files[id].content], {type: "text/plain;charset=utf-8"});
        saveAs(blob, files[id].name);
    }
    else {
        console.log("Unknown file id : " + id);
    }
}

/**
 * Appelle la fonction saveFile pour tous les fichiers
 */
function saveAllFiles() {
    for (i = 0; i < files.length; ++i) {
        saveFile(i);
    }
}

/**
 * Créer une archive contenant tous les fichiers, puis la sauvegarde.
 */
function saveAllFilesAsArchive() {
    var zip = new JSZip();
    for (i = 0; i < files.length; ++i) {
        zip.file(files[i].name, files[i].content);
    }
    zip.generateAsync({type: "blob"})
        .then(function (content) {
            saveAs(content, "archive.zip");
        }); //Generation de l'archive puis "téléchargement"
}

function isValidSaveMethod(saveMethod) {
    return saveMethod == SAVE_CURRENT_FILE || saveMethod == SAVE_ARCHIVE || saveMethod == SAVE_ALL_FILES;
}

function performSaveMethod(saveMethod) {
    if (saveMethod == SAVE_CURRENT_FILE) {
        saveFile(currentFile);
    }
    else if (saveMethod == SAVE_ARCHIVE) {
        saveAllFilesAsArchive();
    }
    else if (saveMethod == SAVE_ALL_FILES) {
        saveAllFiles();
    }
}

function changeSaveMethod(saveMethod) {
    if (isValidSaveMethod(saveMethod)) {
        CURRENT_SAVE_METHOD = saveMethod;
    }
    else {
        console.log("Unknown save method : " + saveMethod);
    }
}

$(".save-method").click(function () {
    console.log()

    changeSaveMethod($(this).attr("data-save-method"));
    $("#btn-save").html($(this).html());
    performSaveMethod(CURRENT_SAVE_METHOD);
});

$("#btn-save").click(function () {
    performSaveMethod(CURRENT_SAVE_METHOD);
});

/*******************************************************************************
 *                            CHANGEMENT DE LANGAGE                            *
 *******************************************************************************/

function requestAndSetLanguage(language) {
    console.log("Selection language : " + language);
    $.ajax({
        type: "POST",
        url: urlLangagesInfo,
        data: {
            lang: language
        },
        success: function (response) {
            console.log(response);

            //Coloration syntaxique de l'éditeur
            editor.getSession().setMode(response.ace);

            //Liste des modèle du langages.
            modeles = response.modeles;
            console.log(modeles);
            console.log(modeles[0]);

            //Mise à jour de la navbar et du titre de la page
            $("#navbarDropdownMenuLink").html("Editeur : " + response.name);
            document.title = "LIDE - " + response.name;

            //Suppression de tous les fichiers (correspondant au precedent langage)
            $("#select-file").html("");
            $("#file-tabs-row").html("");
            currentFile = -1;
            files = new Array();

            //Création d'un nouveau fichier avec le modèle par défaut (premier modèle).
            addFile("main." + modeles[0].ext, modeles[0].model)

            //Modification du formulaire de nouveau fichier
            $("#select-modele").html("")
            $("#select-modele").append(
                $("<option></option>")
                    .attr("value", -1)
                    .text("Fichier vide")
            );
            for (var i = 0; i < modeles.length; i++) {
                $("#select-modele").append($("<option></option>")
                    .attr("value", i)
                    .text("." + modeles[i].ext)
                );
            }
            $("#select-modele").val(0);
            $("#select-modele").change();

            $("#compileCMD-compilateur").text(response.compilateur);
        },
    });
}

//Selection d'un nouveau langage
function clickLangage() {
    var newSelected = $(this);
    var idRequestedLang = parseInt($(this).attr("data-id"));
    swal({
            title : 'Attention !',
            text : 'Toute modifications non sauvegardé seront perdu !',
            type : 'warning',
            cancelButtonText : 'Annuler',
            showCancelButton : true
        }
    ).then(function(result) {
        if (result.value){
            requestAndSetLanguage(idRequestedLang);
            var oldSelected = $(".choix-langage-selected");
            oldSelected.removeClass("choix-langage-selected");
            oldSelected.addClass("choix-langage");
            oldSelected.click(clickLangage);

            newSelected.removeClass("choix-langage");
            newSelected.addClass("choix-langage-selected");
            newSelected.unbind("click");
        }
        else{
            //DO NOTHING
        }
    });

}

$(".choix-langage").click(clickLangage);

/*******************************************************************************
 *                           Various binding                                   *
 *******************************************************************************/

$("#toggle-console").click(function () {
    console.log("TOGGLE CONSOLE");

//   $("#console").toggleClass("d-none");
    $("#console-block").toggleClass("console-block-open");
    $("#console-block").toggleClass("console-block-collapsed");
    $("#console-block").toggleClass("col-6");
    $("#console-block").toggleClass("col-1");

    $("#editor-toolbar-options").toggleClass("col-6");
    $("#editor-toolbar-options").toggleClass("col-1");

    $("#block-editor").toggleClass("col-11");
    $("#block-editor").toggleClass("col-6");
    $("#file-selector").toggleClass("col-11");
    $("#file-selector").toggleClass("col-6");


    /*
        $(this).toggleClass("console-toggle-open");
        $(this).toggleClass("console-toggle-close");

    */
});

$(window).resize(function () {
    $("#row-editeur-console").height($("#top-container").height() - $("#editor-toolbar").height());
});

$(document).ready(function () {
    //Activation des tooltip bootstrap
    $('[data-tooltip="tooltip"]').tooltip();

    // Paramètrage de l'éditeur

    $("#row-editeur-console").height($("#top-container").height() - $("#editor-toolbar").height());

    editor = ace.edit("editor");
    editor.setTheme("ace/theme/tomorrow_night");
    editor.$blockScrolling = Infinity

    requestAndSetLanguage($(".choix-langage-selected").attr("data-id"));
});
