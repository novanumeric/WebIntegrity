<table style="border-style:solid;border-color:black;border-width:1px;"  class="ui-widget-content">
<tr><td>
Units: <select id="selectUnits" class="updateDisplay"><option>Metric-MPa<option>Metric-Bar<option selected>English</select>
<br><img id="ShellTypeImage" src="images/cylinder.png" ></td><td>
<table> 
<tr><td  style="color:blue"><b>Enter Vessel Geometry:</b></td>
</tr>

<tr><td  colspan=2 id="selectShellTypeSpan"><span class="WithHints" title="Select Geometry">Shell Type:</span> 
<select class="updateDisplay" id="selectShellType">

<option>Cone<option>Cylinder<option>Elbow<option>Ellipsoid<option>Pipe<option>Sphere<option>Toricone<option>Torisphere</select>

</td>

</tr>
<tr><td colspan=2  id="CalcsRatioRow"                >
 <span class="WithHints" title="Ratio of Major to Minor Diameter "  >Ratio (R<sub>ell</sub>):</span>  
 <input class="updateDisplay" id="CalcsRatio" size=5 type="text" value="2"> </td></tr>
<tr><td colspan=2   id="CalcsLocationRow">
 Location:<select class="updateDisplay" id="selectLocation"><option>Center<option>Other</select>      
 </td></tr>

 
 <tr><td colspan=2 id="CalcsAngleRow"                ><span class="WithHints" title="Angle"                     >1/2 Apex Angle (&alpha;)</span>                  <input class="updateDisplay" id="CalcsAngle" size=5 type="text" value="">                           <span class="AngleUnits"></span></td></tr>
<tr><td colspan=2 id="CalcsCrownRadiusRow"    ><span class="WithHints" title="Crown Radius"       >Crown Radius (C<sub>r</sub>)</span>            <input class="updateDisplay" id="CalcsCrownRadius" size=5 type="text" value="">               <span class="LengthUnits"></span></td></tr>
<tr><td colspan=2 id="CalcsKnuckleRadiusRow"        ><span class="WithHints" title="Angle"                     >Knuckle Radius (r<sub>k</sub>)</span>          <input class="updateDisplay" id="CalcsKnuckleRadius" size=5 type="text" value="">                   <span class="LengthUnits"></span></td></tr>
<tr><td  id="CalcsDiameterRow"><span id="VesselID" class="WithHints" title="Inside Diameter of Vessel">Diameter (D):</span>   <span id="PipeOD" class="WithHints" title="Outside Diameter Pipe">Diameter (D<sub>o</sub>):</span>                                 <input class="updateDisplay" id="CalcsDiameter" size=5 type="text" value="30">                        <span class="LengthUnits" id="CalcsDiameterUnits"></span> </td>
<td rowspan=2><img class="ShowPipeScheduleLookup" id="ShowPipeScheduleLookup" title="Click here to select geometry based upon pipe schedule" src="images/pipeSchedule.png" alt="Click here to lookup Pipe Schedule" width=50>
</td></tr>
<tr><td id="CalcsUniformThicknessRow"><span class="WithHints" title="Uniform Thickness"      >Thickness (t<sub>nom</sub>):</span>    <input class="updateDisplay" id="CalcsUniformThickness" size=5 type="text" value="6.0" size=5>      
 <span class="LengthUnits" id="CalcsUniformThicknessUnits"></span>
</td></tr>
<tr><td nowrap > 
 <span class="WithHints" title="Uniform Loss"      >Metal Loss (LOSS):</span>    <input class="updateDisplay" id="CalcsUniformLoss" size=5 type="text" value="0.0" size=5>      
 <span class="LengthUnits"></span>
 </td></tr>
<tr><td  colspan=2 class="CalcsPipeElbow"><span class="WithHints" title="Mean Radius (R<sub>b</sub>)"      >Radius of Bend (Rb) :</span>                     <input class="updateDisplay" id="CalcsRb" size=5 type="text" value="0.0" size=5>       <span class="LengthUnits"></span></td></tr>
<tr><td  colspan=2 class="CalcsPipeElbow"><span class="WithHints" title="Angle"      >Angle (&Beta;):</span>                     <input class="updateDisplay" id="CalcsBeta" size=3 type="text" value="90.0" >       <span >[&deg;]</span> <span class="WithHints" title="Angle"      >(&Theta;):</span><span ><input class="updateDisplay" id="CalcsTheta" size=3 type="text" value="0.0" ></span >       <span >[&deg;]</span></td></tr>

 <tr><td  colspan=2 id="CalcsCorrosionAllowanceRow"><span class="WithHints" title="Corrosion Allowance"      >Allowance (FCA):</span>                     <input class="updateDisplay" id="CalcsCorrosionAllowance" size=5 type="text" value="0.0" size=5>       <span class="LengthUnits" id="CalcsCorrosionAllowanceUnits"></span></td></tr>
<tr><td colspan=2 class="CalcsInputs" id="CalcsWeldJointEfficiencyRow"  ><span class="WithHints" title="Weld Joint Efficiency" >Weld Joint Efficiency (E):</span>                     <input class="updateDisplay" id="CalcsWeldJointEfficiency" size=2 type="text" value="1">                </td></tr>


</table></td></tr>
<tr  ><td class="CalcsInputs"    id="CalcsPressureRow"         ><span class="WithHints" title="Pressure [Precede by a '-' for External]"                   >Pressure (P):</span>                                  <input class="updateDisplay" id="CalcsPressure" size=5 type="text" value="">                        <span class="PressureUnits"></span></td>
	<td id="TemperatureRow"><span class="WithHints" title="Temperature"                  >Temperature (T):</span>                                  <input class="updateDisplay" id="CalcsTemperature" size=3 type="text" value="100">                        <span class="TemperatureUnits"></span></td>
</tr>

</table><br>
<table style="border-style:solid;border-color:black;width:400px;border-width:1px;"  class="ui-widget-content">
<tr><td  style="color:blue"><b>Analysis Options:</b></td></tr>
<tr><td colspan=2 id="SpanAnalysisProcedure">Procedure: <select class="updateDisplay" id="selectAnalysisProcedure"><option selected>Part 3: Brittle Fracture<option>Part 4: General Metal Loss - GML<option>Part 5: Local Thickness Area - LTA</select></td></tr>

<tr>
    <td style="width:150px">FFS Level: <select id="selectFFSLevel" class="updateDisplay"><option>Level 1<option>Level 2</select></td>
	<td id="FFSCriteriaType" >Criteria: <select class="updateDisplay" id="selectCriteria"><option>Thickness<option>MAWP</select></td>
	</tr>
<tr><td  colspan=2 id="selectDesignCodeSpan">Design Code: <select id="selectDesignCode" class="updateDisplay"><option>ASME SVIII-I<option>ASME B31.3</select> Design Year: <select id="selectCodeYear" class="updateDisplay"><option>Latest</select> </td></tr>
<tr><td colspan=2 nowrap> Material (<span style="font-size:small;color:blue" id="showMaterialFilters">Filters</span>): <select id="selectMaterial" class="updateDisplay"></select> </td></tr>
<tr><td  colspan=2 nowrap class="MaterialFilters" style="display:none;">&nbsp;&nbsp;Nominal Composition: <select id="selectNominalComposition" class="updateDisplay"></select></td></tr>
<tr><td colspan=2 class="MaterialFilters" style="display:none;">&nbsp;&nbsp;Product Form: <select id="selectProductForm" class="updateDisplay"></select></td></tr>
<tr><td colspan=2 class="MaterialFilters" style="display:none;">&nbsp;&nbsp;Spec No: <select id="selectSpecNo" class="updateDisplay"></select></td></tr>


<tr><td colspan=2 class="CalcsInputs" id="CalcsAllowableStressRow"      ><span class="WithHints" title="Allowable Stress"          >Allowable Stress(S):</span>                           <input class="updateDisplay" id="CalcsAllowableStress" size=5 type="text" value="20">                 <span class="StressUnits"></span></td></tr>
<tr><td colspan=2 class="CalcsInputs" id="CalcsMatCurveRow"      ><span class="WithHints" title="Material Curve"          >Material Curve:</span>                           <select id="CalcsMatCurveRow" type="text" class="updateDisplay"><option>Section VIII-I</select><select id="SectionVIIICurve" class="updateDisplay"><option>A<option>B<option>C<option>D</select>, Threshold R<sub>TS</sub>: <input type="text" id="ThresholdRTS" value="0.3" size=2 >                 </td></tr>

<tr><td  colspan=2 id="InputTypeRow"><span class="WithHints" title="Select Thickness Reading Form"> Input Type:</span> <select class="updateDisplay" id="selectInputType"><option>Critical Thickness Profile(CTP)<option>Thickness Grid(GRID)<option>Point Thickness Readings(PTR)</select></td></tr>

<tr><td  colspan=2 class="FlawDimensions" id="FlawDimensionsRow" id="CalcsFlawDimensions"><span class="WithHints" title="Spacing"      >Flaw Dimensions Input:</span>    <select id="selectFlawDimensions" class="updateDisplay"><option>Spacing<option>Length</select></td></tr>
<tr><td  colspan=2 class="FlawDimensions FlawDimensionsLength" id="CircumferentialLengthRow"><span class="WithHints" title="Spacing"      >Circumferential Length (C):</span>    <input class="updateDisplay" id="CircumferentialLength" size=5 type="text" value="1.5" size=5>       <span class="LengthUnits"></span></td></tr>
<tr><td  colspan=2 class="FlawDimensions FlawDimensionsLength" id="LongitudinalLengthRow"><span class="WithHints" title="Spacing"      >Longitudinal Length (S):</span>    <input class="updateDisplay" id="LongitudinalLength" size=5 type="text" value="1.5" size=5>       <span class="LengthUnits"></span></td></tr>
<tr><td  colspan=2 class="FlawDimensions FlawDimensionsSpacing" id="CircumferentialSpacingRow"><span class="WithHints" title="Spacing"      >Circumferential Spacing (&Delta;M):</span>    <input class="updateDisplay" id="CircumferentialSpacing" size=5 type="text" value="1.5" size=5>       <span class="LengthUnits"></span></td></tr>
<tr><td  colspan=2 class="FlawDimensions FlawDimensionsSpacing" id="LongitudinalSpacingRow"><span class="WithHints" title="Spacing"      >Longitudinal Spacing (&Delta;C):</span>    <input class="updateDisplay" id="LongitudinalSpacing" size=5 type="text" value="1.5" size=5>       <span class="LengthUnits"></span></td></tr>

</table>
