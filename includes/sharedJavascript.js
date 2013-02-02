

function getParameterByName( name, defaultValue )
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return defaultValue;
  else
    return decodeURIComponent(results[1].replace(/\+/g, " "));
}



function clickAPI579() {
	window.location.replace("api579.php?CalcsDiameter="+$('#CalcsDiameter').val()+"&selectUnits=" + $('#selectUnits').val()+"&CalcsUniformThickness="+ $('#CalcsUniformThickness').val());
};

function clickASMEB31G() {
	window.location.replace("b31g.php?CalcsDiameter="+$('#CalcsDiameter').val()+"&selectUnits="+ $('#selectUnits').val()+"&CalcsUniformThickness="+ $('#CalcsUniformThickness').val());
};

function setupLinks() {
$('#clickASMEB31G').click(clickASMEB31G);
$('#clickAPI579').click(clickAPI579);
}
