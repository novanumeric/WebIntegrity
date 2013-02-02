<?

$CTPInputM=$_REQUEST["CTPInputM"];
$CTPInputC=$_REQUEST["CTPInputC"];
$CTPInputM=preg_split("/[\s,]+/",$CTPInputM);
$CTPInputC=preg_split("/[\s,]+/",$CTPInputC);

$Warning="";



$tmm=min(min($CTPInputM),min($CTPInputC));
$Equation="|n|t_{mm}=$tmm|n|";
$details=array('tmm'=>$tmm,'Equation'=>$Equation);
echo json_encode($details);
?>