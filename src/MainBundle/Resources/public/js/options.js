
var selectedTheme = $("#ace-theme-selector").val();
var fontSize = $("#editor-font-size-selector").val();

$("#ace-theme-selector").change(function(){
    editor.setTheme($( this ).val());

});

$("#editor-font-size-selector").change(function () {
    $("#editor").css("font-size", $(this).val() + "pt");
})

$("#options-ok").click(function () {
    selectedTheme = editor.getTheme();
    fontSize = $("#editor-font-size-selector").val();
    $('#modal-options').modal('hide');
    synchroniseEditorAndSelectedTab();
});

$('#options-cancel').click(function () {
    console.log(selectedTheme + " " + fontSize);
    $("#ace-theme-selector").val(selectedTheme);
    if(editor.getTheme() != selectedTheme){
        editor.setTheme(selectedTheme);
    }

    $("#editor-font-size-selector").val(fontSize);
    $("#editor").css("font-size", fontSize + "pt");

    $('#modal-options').modal('hide');
});