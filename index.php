<html>
	<head>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/src/jquery.jqplot.js"></script>
		<script type="text/javascript" src="/src/plugins/jqplot.meterGaugeRenderer.js"></script>
		<link rel="stylesheet" type="text/css" href="/src/jquery.jqplot.css" />
		<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" />
		<meta charset="utf-8">
		<title>Sensör Değerleri</title>
		<style>
			body {
			margin:2%;
			background: #202737;
			}
			#navbar {
			width:100%;
			} 
			#navbar #holder {
			height:64px;
			border-bottom:1px solid #000;
			width:100%; 	
			padding-left:25px; 
			} 
			#navbar #holder ul {
			list-style:none;
			margin:0;
			padding:0; 
			} 
			#navbar #holder ul li a { 
			text-decoration:none;
			float:left;
			margin-right:5px;
			line-height:23px;
			font-family:"Arial Black", Gadget, sans-serif;
			color:#000;
			border:1px solid #000;
			border-bottom:none;
			padding:20px;
			width:275px;
			text-align:center;
			display:block;
			background:#69F;
			-moz-border-radius-topleft:15px;
			-moz-border-radius-topright:15px;
			-webkit-border-top-left-radius:15px;
			-webkit-border-top-right-radius:15px; 
			} 
			#navbar #holder ul li a:hover {
			background:#F90;
			color:#FFF;
			text-shadow:1px 1px 1px #000;
			} 
			#holder ul li a#onlink {
			background:#FFF;
			color:#000;
			border-bottom:1px solid #FFF; 
			} 
			#holder ul li a#onlink:hover {
			background:#FFF;
			color:#69F;
			text-shadow:1px 1px 1px #000; 
			}
		</style>
	</head>
	<body>
		<div class="row">
			<div id="navbar">
				<div id="holder">
					<ul>
						<li><a href="/index.php" id="onlink">Sensörler</a></li>
						<li><a href="/control.php">Kontrol Düğmeleri</a></li>
						<li><a href="/reports.php">Raporlar</a></li>
					</ul>
				</div>
				<!-- end holder div --> 
			</div>
			<!-- end navbar div -->
		</div>
		<div class="row" style="border-bottom:1px solid">
			<?php
				$mysqli = new mysqli("localhost", "root", "", "fabrika_yonetim");
				
				/* check connection */
				if ($mysqli->connect_errno) {
				    printf("Connect failed: %s\n", $mysqli->connect_error);
				    exit();
				} else {
					$mysqli->query("SET NAMES UTF8");
				}
				
				$query = "SELECT id,isim FROM sensor";
				
				if ($result = $mysqli->query($query)) {
				
				    /* fetch associative array */
				    while ($row = $result->fetch_assoc()) { ?>
			<div id="<?php printf ("%s", $row["id"]) ?>" sensor-label="<?php printf ("%s", $row["isim"]) ?>" class="plot col-md-4" style="width:500px;height:300px;"></div>
			<?php       
				}
				
				   /* free result set */
				   $result->free();
				}
				
				/* close connection */
				$mysqli->close();
				?>
		</div>
		</section>
	</body>
	<script> 
		$(document).ready(function(){
			hepsindenVeriAl();
			setInterval(function() {
				hepsindenVeriAl();
			}, 5000);
		});
		
		function hepsindenVeriAl() {
			for (var i=0; i<$('.plot').size(); i++){
				(function(){
					generateRandomValues($('.plot')[i].id); 
				})();
			}
		}
		
		function rerenderMeter(chartId, value) {
			$('#' + chartId).empty()
			s1 = [value];
			sensor_label = $('#' + chartId).attr('sensor-label')
			$.jqplot(chartId, [s1],{
				seriesDefaults: {
					renderer: $.jqplot.MeterGaugeRenderer,
					rendererOptions: {
						label: sensor_label,
						labelPosition: 'bottom',
						labelHeightAdjust: -5,
						intervalOuterRadius: 85,
						ticks: [10000, 30000, 50000, 70000],
						intervals:[22000, 55000, 70000],
						intervalColors:['#66cc66', '#E7E658', '#cc6666']
					}
				}
			});
		}
		
		function generateRandomValues(chartId) {
			rerenderMeter(chartId, Math.floor(Math.random() * 100000) + 1);
		}
		
		
	</script>
</html>