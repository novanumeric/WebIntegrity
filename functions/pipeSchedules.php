<?php
	
$RequestType=$_REQUEST["RequestType"];
$NPS=$_REQUEST["NPS"];
if(isset($_REQUEST["Schedule"])){
	$Schedule=$_REQUEST["Schedule"];
} else {
	$Schedule="";
}
$Units=$_REQUEST["selectUnits"];
if($RequestType=="NPS") {
	$details=getNPS();
	echo json_encode($details);
}
if($RequestType=="Schedule") {
	$details=getSchedule($NPS);
	echo json_encode($details);
}

if($RequestType=="Geometry") {
	$details=getPipeGeometry($NPS,$Schedule,$Units);
	echo json_encode($details);
}

function getNPS() {
		$details=Array();
		$dbh = new PDO("sqlite:../dbs/schedules.s3db"); 
		$j=1;
		foreach ($dbh->query('SELECT * FROM pipe order by DN') as $row) {
			$detailstemp=array('Size'.($j) => $row['NPS']." [".$row['DN']."] ");	
			$details=array_merge($details,$detailstemp);
			$j++;
		}
		return $details;
	
}
 
 
function getSchedule($NPS) {
	$details=Array();
	$dbh = new PDO("sqlite:../dbs/schedules.s3db"); 	 
	foreach ($dbh->query("SELECT sql FROM sqlite_master WHERE tbl_name = 'pipe' AND type = 'table'") as $row) {
		$temp=$row[0];
	}
	$temp=split(",",$temp);
	for($i=4;$i<sizeof($temp);$i++) {
		$temp2=split(" ",$temp[$i]);
		$schedules[$i-4]=str_replace("\r","",str_replace("\n","",str_replace("]","",str_replace("[","",$temp2[0]))));
	}
	$count=0;
	foreach ($dbh->query("SELECT * FROM pipe where NPS like '".str_replace("\"","",$NPS)."'") as $row) {
			
			for($i=0;$i<sizeof($schedules);$i++) { 	
				if($row[trim($schedules[$i])]!="") {
					$detailstemp=array('Schedules'.(++$count) => $schedules[$i]);	
					$details=array_merge($details,$detailstemp);
				}
			} 
	}	
	return $details;
}


function getPipeGeometry($NPS,$Schedule,$Units) {
	$dbh = new PDO("sqlite:../dbs/schedules.s3db"); 	 
	$count=0;
	foreach ($dbh->query("SELECT sql FROM sqlite_master WHERE tbl_name = 'pipe' AND type = 'table'") as $row) {
		$temp=$row[0];
	}
	$temp=split(",",$temp);
	for($i=4;$i<sizeof($temp);$i++) {
		$temp2=split(" ",$temp[$i]);
		$schedules[$i-4]=str_replace("]","",str_replace("[","",$temp2[0]));
	}
	$sqlstring='SELECT * FROM pipe where NPS = \''.$NPS.'\' ';
	foreach ($dbh->query($sqlstring) as $row) {
		$OD=floatval ($row['OD']);
		$Thickness=floatval ($row[trim($Schedule)]);
		if($Thickness=="") {
			for($i=0;$i<sizeof($schedules);$i++) {
				$temp=$row[trim($schedules[$i])];
				if($temp!="") {
					$Thickness=floatval($temp);
					break;
				}
			}
		}
		if($Units=="English") { 
			$Convert=1;
		} else {
			$Convert=25.4;
		}
		$details=array('OD' => $OD*$Convert,'Thickness' => $Thickness*$Convert);	
		return $details;
	}
}
?> 
 