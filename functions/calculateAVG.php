<?

$PTRInputData=$_REQUEST["CTPInputData"];

$PTRInputData=preg_split("/[\s,]+/",$PTRInputData);
$N=count($PTRInputData);
$Warning="";


$tam=array_sum($PTRInputData) / $N;

$details=array('tam'=>$tam,'Warning'=>$Warning);
echo json_encode($details);
?>