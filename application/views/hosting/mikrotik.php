<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<html>
<head>
	<title>Mikrotik</title>
	
	<!-- Mainly scripts -->
    <script src="<?php echo base_url('assets/js/jquery-2.1.1.js'); ?>"></script>
</head>
<body>
    <h2 class="font-bold" style="display: none;"><div id="trafico"></div></h2>
    
    <div id="container" class="m-b-md"></div>
	<input name="interface" id="interface" type="hidden" value="WAN" />
	
	<!-- Highcharts plugin javascript -->
    <script type="text/javascript" src="<?php echo base_url('assets/highchart/js/highcharts.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/highchart/js/themes/gray.js'); ?>"></script>
	

    <script>
	    var chart;

		function requestDatta(interface) {
			$.ajax({
				url: '/assets/highchart/data.php?interface='+interface,
				datatype: "json",
				success: function(data) {
					var midata = JSON.parse(data);
					if( midata.length > 0 ) {
						//console.log(data);
						var TX=parseInt(midata[0].data);
						var RX=parseInt(midata[1].data);
						var x = (new Date()).getTime(); 
						shift=chart.series[0].data.length > 19;
						chart.series[0].addPoint([x, TX], true, shift);
						chart.series[1].addPoint([x, RX], true, shift);
						document.getElementById("trafico").innerHTML=TX + " / " + RX;
					}else{
						document.getElementById("trafico").innerHTML="- / -";
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
					console.error("Status: " + textStatus + " request: " + XMLHttpRequest); console.error("Error: " + errorThrown); 
				}       
			});
		}
		
		$(document).ready(function() {
	        	
	        	Highcharts.setOptions({
					global: {
						useUTC: false
					}
				});
	
	           chart = new Highcharts.Chart({
				   chart: {
					renderTo: 'container',
					animation: Highcharts.svg,
					type: 'spline',
					events: {
						load: function () {
							setInterval(function () {
								requestDatta(document.getElementById("interface").value);
							}, 1000);
						}				
					}
				 },
				 title: {
					text: 'Gigared'
				 },
				 xAxis: {
					type: 'datetime',
						tickPixelInterval: 150,
						maxZoom: 20 * 1000
				 },
				 yAxis: {
					minPadding: 0.2,
						maxPadding: 0.2,
						title: {
							text: 'Tráfico en Mbits',
							margin: 10
						}
				 },
		            series: [{
		                name: 'Download',
		                data: []
		            }, {
		                name: 'Upload',
		                data: []
		            }]
			  });

        });
    </script>
</body>
</html>