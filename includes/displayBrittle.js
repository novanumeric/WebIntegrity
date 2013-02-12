function displayBrittle() {
	$.ajax({type: 'POST', 
		url:'functions/calculateBrittle.php',
		async: false,
		dataType: "json",
		data:{
			Calc:$Calc,
			tmin:window.tmin, tminl:window.tminl,
			tminc:window.tminc, tam:window.tam,
			tams:window.tams, tamc:window.tamc,
			tmm:window.tmm,
			MAWP:window.MAWP,
			MAWPL:window.MAWPL,
			MAWPC:window.MAWPC,
			RSF:window.RSF,
			RSFM:window.RSFM,
			RSFC:window.RSFC,
			WeldJointEfficiency:$("#CalcsWeldJointEfficiency").val(),
			EllipsoidLocation:$("#selectEllipsoidLocation").val(),
			AnalysisProcedure:$('#selectAnalysisProcedure').val(),
			FFSLevel:$("#selectFFSLevel").val(),
			InputType:$("#selectInputType").val(),
			ShellType:$("#selectShellType").val(),
			LengthUnits:window.LengthUnits,
			FCA:$("#CalcsCorrosionAllowance").val(),
			tnom:$("#CalcsUniformThickness").val()
		}}).done(	function displayResultsFinal(data) {
			if(data.GMLThicknessCalcResult!="") {
				$("#GMLThicknessCalcResult").show();
				$("#GMLThicknessCalcResult").attr("src","functions/render.php?equation=" + encodeURIComponent(data.GMLThicknessCalcResult));			
			} else {
				$("#GMLThicknessCalcResult").hide();
			}
			
		});	
	var plot2 = $.jqplot ('GovThickness', [[3,7,9,1,4,6,8,2,5]], {
		  // Give the plot a title.
		  title: 'Governing Plate Thickness',
		  // You can specify options for all axes on the plot at once with
		  // the axesDefaults object.  Here, we're using a canvas renderer
		  // to draw the axis label which allows rotated text.
		  axesDefaults: {
			labelRenderer: $.jqplot.CanvasAxisLabelRenderer
		  },
		  // An axes object holds options for all axes.
		  // Allowable axes are xaxis, x2axis, yaxis, y2axis, y3axis, ...
		  // Up to 9 y axes are supported.
		  axes: {
			// options for each axis are specified in seperate option objects.
			xaxis: {
			  label: "X Axis",
			  // Turn off "padding".  This will allow data point to lie on the
			  // edges of the grid.  Default padding is 1.2 and will keep all
			  // points inside the bounds of the grid.
			  pad: 0
			},
			yaxis: {
			  label: "Y Axis"
			}
		  }
		});
		 var plot2 = $.jqplot ('TempReduction', [[3,7,9,1,4,6,8,2,5]], {
		  // Give the plot a title.
		  title: 'Temperature Reduction',
		  // You can specify options for all axes on the plot at once with
		  // the axesDefaults object.  Here, we're using a canvas renderer
		  // to draw the axis label which allows rotated text.
		  axesDefaults: {
			labelRenderer: $.jqplot.CanvasAxisLabelRenderer
		  },
		  // An axes object holds options for all axes.
		  // Allowable axes are xaxis, x2axis, yaxis, y2axis, y3axis, ...
		  // Up to 9 y axes are supported.
		  axes: {
			// options for each axis are specified in seperate option objects.
			xaxis: {
			  label: "X Axis",
			  // Turn off "padding".  This will allow data point to lie on the
			  // edges of the grid.  Default padding is 1.2 and will keep all
			  // points inside the bounds of the grid.
			  pad: 0
			},
			yaxis: {
			  label: "Y Axis"
			}
		  }
	});
}