<?php
	
$RequestType=$_REQUEST["RequestType"];


function getMoniker($DesignCode,$CodeYear,$NominalComposition="",$ProductForm="",$SpecNo="") {
	$moniker="";
	
	if(strtoupper($CodeYear)!=strtoupper("All")&&$CodeYear!=""&&strtoupper($CodeYear)!=strtoupper("Latest")) {
		$moniker="where $CodeYear >= YearMin And $CodeYear <= YearMax and ApplicandMaxTempLimitVIII1!='NP'";
	}
	
	
	if(strtoupper($NominalComposition)!=strtoupper("All")&&$NominalComposition!="") {
		if($moniker=="") {
			$moniker.=" where ";
		} else {
			$moniker.=" and ";
		}
		$moniker.=" [NominalComp]  like '" .$NominalComposition."'";
	}
	if(strtoupper($ProductForm)!=strtoupper("All")&&$ProductForm!="") {
		if($moniker=="") {
			$moniker.=" where ";
		} else {
			$moniker.=" and ";
		}
		$moniker.=" [ProductForm]  like '" .$ProductForm."' ";
	}
	if(strtoupper($SpecNo)!=strtoupper("All")&&$SpecNo!="") {
		if($moniker=="") {
			$moniker.=" where ";
		} else {
			$moniker.=" and ";
		}
		$moniker.=" [SpecNo]  like '" .$SpecNo."'";
	}

	return $moniker;
}


if($RequestType=="CodeYear") {
	$DesignCode=$_REQUEST["DesignCode"];
	$details= array( "CodeYear1"=>"2005", "CodeYear2"=>"1998 ");
	echo json_encode($details);
}

if($RequestType=="NominalComposition") {
		$details=Array();
		$DesignCode=$_REQUEST["DesignCode"];
		$CodeYear=$_REQUEST["CodeYear"];
		$ProductForm=$_REQUEST["ProductForm"];		
		$SpecNo=$_REQUEST["SpecNo"];
		$CodeYear=$_REQUEST["CodeYear"];
		$details=array("NominalComp1" => "All");
		$whereMoniker=getMoniker($DesignCode,$CodeYear,"",$ProductForm,$SpecNo);
		$dbh = new PDO("sqlite:../dbs/allowables.s3db"); 
		$j=2;
		foreach ($dbh->query('SELECT DISTINCT "NominalComp" FROM sectionviiitable1 '.$whereMoniker . ' order by NominalComp' ) as $row) {
			if($row['NominalComp']!="") {
				$detailstemp=array('NominalComposition'.($j) => $row['NominalComp']);	
				$details=array_merge($details,$detailstemp);
				$j++;
			}
		}
		echo json_encode($details);
		exit(0);
}


if($RequestType=="ProductForm") {
		$DesignCode=$_REQUEST["DesignCode"];
		$CodeYear=$_REQUEST["CodeYear"];
		$NominalComposition=$_REQUEST["NominalComposition"];		
		$SpecNo=$_REQUEST["SpecNo"];	
		$details=Array();
		$details=array("ProductForm1" => "All");

		$whereMoniker=getMoniker($DesignCode,$CodeYear,$NominalComposition,"",$SpecNo);

		$dbh = new PDO("sqlite:../dbs/allowables.s3db"); 
		$j=2;
		foreach ($dbh->query('SELECT DISTINCT "ProductForm" FROM sectionviiitable1 '.$whereMoniker.'order by productform' ) as $row) {
			if($row['ProductForm']!="") {
				$detailstemp=array('ProductForm'.($j) => $row['ProductForm']);	
				$details=array_merge($details,$detailstemp);
				$j++;
			}
		}
		echo json_encode($details);
		exit(0);
}
if($RequestType=="SpecNo") {
		$details=array("SpecNo1" => "All");
		$CodeYear=$_REQUEST["CodeYear"];
		$NominalComposition=$_REQUEST["NominalComposition"];		
		$ProductForm=$_REQUEST["ProductForm"];		
		$DesignCode=$_REQUEST["DesignCode"];
		$whereMoniker=getMoniker($DesignCode,$CodeYear,$NominalComposition,$ProductForm);

		$dbh = new PDO("sqlite:../dbs/allowables.s3db"); 
		$j=2;
		foreach ($dbh->query('SELECT DISTINCT "SpecNo" FROM sectionviiitable1 '.$whereMoniker . " order by SpecNo") as $row) {
			$detailstemp=array('SpecNo'.($j) => $row['SpecNo']);	
			$details=array_merge($details,$detailstemp);
			$j++;
		}
		echo json_encode($details);
		exit(0);
}
if($RequestType=="Material") {
		$DesignCode=$_REQUEST["DesignCode"];
		$CodeYear=$_REQUEST["CodeYear"];
		$NominalComposition=$_REQUEST["NominalComposition"];		
		$ProductForm=$_REQUEST["ProductForm"];		
		$SpecNo=$_REQUEST["SpecNo"];
		$whereMoniker=getMoniker($DesignCode,$CodeYear,$NominalComposition,$ProductForm,$SpecNo);
		$details=array('MaterialID1'=>'0','MaterialName'.(1) => 'Manual');
		$dbh = new PDO("sqlite:../dbs/allowables.s3db"); 
		if($DesignCode=="ASME SVIII-I") {
			$j=2;
			$sqlString = 'SELECT * FROM sectionviiitable1 '.$whereMoniker ." order by YearMax desc";
			foreach ($dbh->query($sqlString) as $row) {
				$name=$row['NominalComp'] .' '.$row['ProductForm'].' '.$row['SpecNo'];
				$name.=' '.$row['TypeGrade'].' '.$row['AlloyDesigUNSNo'].' '.$row['ClassCondTemper'];
				$name.=' '.$row['SizeThickness'];
				if($row['YearMin']!=$row['YearMax']) {
			//	$name.=' ('.$row['YearMin'] . '-' .$row['YearMax'] .') ';
				} else { 
				//$name.=' ('.$row['YearMin']  .') ';			
				}
				$detailstemp=array('MaterialID'.($j)=>$row['ID'],'MaterialName'.($j) => trim($name));	
				$details=array_merge($details,$detailstemp);
				$j++;
			}
		}
		echo json_encode($details);
		exit(0);
}

if($RequestType=="AllowableStress") {
	$DesignCode=$_REQUEST["DesignCode"];
	$TemperatureUnits=$_REQUEST["TemperatureUnits"];
	$StressUnits=$_REQUEST["StressUnits"];
	$MaterialID=$_REQUEST["MaterialID"];
	$AnalysisProcedure=$_REQUEST["AnalysisProcedure"];
	
	if(stripos($AnalysisProcedure,"Brittle Fracture")===false) {
		$Temperature=$_REQUEST["Temperature"];
		//echo "Not Brittle";
	} else {
		//echo "Brittle";
		if($TemperatureUnits=="C") {
			$Temperature=(100-32)/1.8;		
		} else {
			$Temperature="100";
		}

	}
	if($TemperatureUnits=="C") {
		$Temperature=$Temperature*1.8+32;
	}
	
	$base=20;
	$sqlString = "SELECT * FROM sectionviiitable1 where ID=$MaterialID";
	//echo $sqlString;
	$dbh = new PDO("sqlite:../dbs/allowables.s3db"); 
	foreach ($dbh->query($sqlString) as $row) {
		$TempArray=array(100,150,200,250,300,400,500,600,650,700,750,800,850,900,950,1000,1050,1100,1150,1200,1250,1300,1350,1400,1450,1500,1550,1600,1650);
		$MaxTemp=$row['ApplicAndMaxTempLimitVIII1'];
		if($Temperature<=-20) {
			$Stress=$row['100'];
		} elseif($Temperature>1650||$Temperature>$MaxTemp) {
			$Stress=0;
		} else {
			for ($i=0; $i<=28; $i++) {
				if($Temperature>=$TempArray[$i]&&$Temperature<=$TempArray[$i+1]) {
					$leftStress=$row[$TempArray[$i]];
					if($leftStress==0) {
						$leftStress=$row[$TempArray[$i-1]];
					}
					$rightStress=$row[$TempArray[$i+1]];
					if($rightStress==0) {
						$rightStress=$row[$TempArray[$i+1]];
					}
					$delta=($rightStress-$leftStress)/($TempArray[$i+1]-$TempArray[$i]);
					$Stress=$leftStress+$delta*($Temperature-$TempArray[$i]);
				}	
			}
		}
	}

	if(strtoupper($StressUnits)=="MPA") { //Convert Ksi to MPa
		$Stress=$Stress*6.89475908677537;
	}
	$details=array('AllowableStress'=>$Stress);
	echo json_encode($details);
	exit(0);
}

?> 
 