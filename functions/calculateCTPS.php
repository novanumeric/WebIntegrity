<?


$GridInputData=$_REQUEST["GridInputData"];
/*$GridInputData="1.024 1.031 1.028 1.031 1.035 1.028 1.02 1.024 1.031 1.028
1.028 1.031 1.031 1.031 1.035 1.031 1.024 1.024 1.031 1.028
1.028 1.031 1.028 1.031 1.035 1.031 1.024 1.024 1.031 1.028
1.031 1.031 1.024 1.031 1.031 1.031 1.024 1.028 1.028 1.028
1.031 1.031 1.028 1.035 1.031 1.031 1.024 1.024 1.028 1.031
1.031 1.031 1.031 1.035 1.031 1.031 1.024 1.024 1.028 1.031
1.035 1.031 1.031 1.031 1.031 1.031 1.024 1.024 1.028 1.02
1.035 1.035 0.846 1.031 1.031 1.031 1.028 1.024 1.028 1.016
1.035 1.039 0.335 1.031 1.031 1.031 1.028 1.024 1.028 1.012
1.035 1.039 0.339 1.031 1.028 1.031 1.028 1.024 1.028 1.012
1.035 1.043 0.858 1.031 1.031 1.031 1.028 1.028 1.024 1.012
1.039 1.031 1.031 1.031 1.031 1.031 1.031 1.031 1.02 1.012
1.035 1.031 1.031 1.031 1.031 1.031 1.031 1.035 1.02 1.008
1.039 1.031 1.028 1.031 1.028 1.035 1.031 1.035 1.02 1.008
1.035 1.028 1.031 1.031 1.031 1.035 1.031 1.039 1.02 1.008
1.035 1.028 1.031 1.031 1.031 1.039 1.035 1.035 1.028 1.012
1.035 1.028 1.031 1.031 0.906 0.917 1.035 1.035 1.028 1.02
1.035 1.028 1.039 1.031 0.89  1.043 1.035 1.031 1.031 1.02
1.031 1.028 1.039 1.031 0.894 1.043 0.913 1.035 1.031 1.02
1.031 1.031 1.035 1.028 0.909 1.043 0.902 1.035 1.035 1.02
1.031 1.031 1.031 1.028 1.035 0.862 0.909 0.902 1.035 1.024
1.031 1.031 1.031 1.028 1.031 0.862 0.917 1.028 1.031 1.031
1.031 1.031 1.028 1.028 1.031 1.043 1.031 1.028 1.028 1.031
";*/

$list = explode("\n", $GridInputData);
$columnCount=-1;
$rowCount=0;
$array=array();
for($i=0;$i<count($list);$i++) {
	if(trim($list[$i])!="") {
	
		$buf=preg_split("/[\s,]+/",trim($list[$i])); 
		if($columnCount==-1) {
			$columnCount=count($buf);
		}
		$array=array_merge($array,$buf);
		$rowCount++;
	}
}
$CTPInputM="";
for($row=0;$row<$rowCount;$row++) {
	$temp=1000;
	
	for($column=0;$column<$columnCount;$column++) {
		$temp=min($temp,$array[$row*$columnCount+$column]);
	}

	if($CTPInputM =="") {
		$CTPInputM= $temp;
	} else { 
		$CTPInputM=$CTPInputM . " " . $temp;
	}
} 

$CTPInputMArray=preg_split("/[\s,]+/",$CTPInputM); 
$CTPInputC="";
for($column=0;$column<$columnCount;$column++) {

	$temp=1000;
	for($row=0;$row<$rowCount;$row++) {
		$temp=min($temp,$array[$row*$columnCount+$column]);

	}
	
	if($CTPInputC =="") {
		$CTPInputC= $temp;
	} else { 
		$CTPInputC=$CTPInputC . "  " . $temp;
	}
} 

$CTPInputCArray=preg_split("/[\s,]+/",$CTPInputC); 
$HTMLTable="<tr><td style='font-size:10pt' colspan=".($columnCount+1).">Determination of Critical Thickness Profiles</td></tr>";
$HTMLTable.="<tr><td></td><td style='color:blue;border-bottom:thin solid black;><span style='color:blue'><b>".preg_replace("/[\s,]+/","</b></td><td style='color:blue;border-bottom:thin solid black;'><b>",$CTPInputC)."</b></span></td></tr>";

for($row=0;$row<$rowCount;$row++) {
	$HTMLTable.="<tr><td style='color:red;border-right:thin solid black;><span style='color:red'><b>".$CTPInputMArray[$row]."</b></span></td>";
	for($column=0;$column<$columnCount;$column++) {

		if($array[$row*$columnCount+$column]==$CTPInputCArray[$column]&&$array[$row*$columnCount+$column]==$CTPInputMArray[$row]) {
			$HTMLTable.="<td><span style='color:violet'><b>".$array[$row*$columnCount+$column]."</b></span></td>";
		}
		elseif($array[$row*$columnCount+$column]==$CTPInputCArray[$column]) {
			$HTMLTable.="<td><span style='color:blue'><b>".$array[$row*$columnCount+$column]."</b></span></td>";
		}
		elseif($array[$row*$columnCount+$column]==$CTPInputMArray[$row]) {
			$HTMLTable.="<td><span style='color:red'><b> ".$array[$row*$columnCount+$column]."</b></span></td>";
		} else {
			$HTMLTable.="<td>".$array[$row*$columnCount+$column]."</td>";
		}
	}
	
	$HTMLTable.="</tr>";
	
}
$HTMLTable.="<tr><td colspan=50><br><span style='color:red'>M Plane</span><br><span style='color:blue'>C Plane</span></td></tr>";
$Warning="";
$details=array('CTPInputM'=>$CTPInputM,'CTPInputC' => $CTPInputC,'Warning'=>$Warning,'HTMLTable'=>$HTMLTable);
echo json_encode($details);

?>