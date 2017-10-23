$("#console-toogle").click(function (){
  $("#console").toggleClass("d-none");
  $("#console-block").toggleClass("console-block");
  $("#console-block").toggleClass("console-block-collapsed");
});

var editor = ace.edit("editor");
editor.getSession().setMode("ace/mode/c_cpp");
editor.setTheme("ace/theme/dawn");
document.getElementById('editor').style.fontSize='16px';
