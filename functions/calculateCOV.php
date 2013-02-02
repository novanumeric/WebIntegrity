<?

$PTRInputData=trim($_REQUEST["PTRInputData"]);
$LengthUnits=$_REQUEST["LengthUnits"];
$tnom=$_REQUEST["tnom"];
$PTRInputData=preg_split("/[\s,]+/",$PTRInputData);
$tmm=min($PTRInputData);
$N=count($PTRInputData);
$Warning="";
if($N<15) {
	$Warning="A minimum of 15 thickness readings is recommended API 579 4.3.3.2. ";
}

function formatLengthResults($val,$LengthUnits) {
	$buf="";
	$buf.=round($val,4);
	$buf.="\\ \\textup{".$LengthUnits."}";
	return $buf;
}

$tam=array_sum($PTRInputData) / $N;
$S=0;
for($i=0;$i<$N;$i++) {

	$S=$S+pow(($PTRInputData[$i]-$tam),2);

}
$COV=1/$tam*sqrt($S/($N-1));
if($COV>0.1) {
	if($Warning!="") {
		$Warning.="<BR>";
	}
	$Warning.="Consider the use of Critical Thickness Profiles COV > 10%";	

	$Color="\\color{Red}";
} else {
	$Color="";
}
$Loss=$tnom-$tam;
$Equation="|n|N=".$N."|n|S=\sum_{i=1}^N (t_{rd,i}-t_{am})^2 =".round($S,3)."|n| {COV= \\frac{1}{t_{am}$} \\left[\\frac{S}{N-1}\\right]^{0.5}=".$Color.round($COV,4)."} |n|";
$Equation.="{t_{mm}}=".formatLengthResults($tmm,$LengthUnits).",\\ {t_a_m}=".formatLengthResults($tam,$LengthUnits)."}|n|";
$Equation.="LOSS={t_{nom}}-{t_{am}}=".formatLengthResults($Loss,$LengthUnits)."|n|";
$details=array('tam'=>$tam,'tmm'=>$tmm,'Loss'=>$Loss,'COV' => $COV,'Equation' => $Equation,'Warning'=>$Warning);
echo json_encode($details);

?>