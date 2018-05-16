<html>
	<head>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
			.switch input {
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
			filter: alpha(opacity=0);
			-moz-opacity: 0;
			opacity: 0;
			z-index: 100;
			position: absolute;
			width: 100%;
			height: 100%;
			cursor: pointer;
			}
			.switch {
			width: 120px;
			height: 30px;
			position: relative;
			margin-top: 5px;
			}
			.switch label {
			display: block;
			width: 65%;
			height: 100%;
			position: relative;
			background: #1F2736; /*#121823*/
			background: linear-gradient(#121823, #161d2b);
			border-radius: 30px 30px 30px 30px;
			box-shadow: inset 0 3px 8px 1px rgba(0,0,0,0.5),  inset 0 1px 0 rgba(0,0,0,0.5),  0 1px 0 rgba(255,255,255,0.2);
			-webkit-transition: all .5s ease;
			transition: all .5s ease;
			}
			.switch input ~ label i {
			display: block;
			height: 25px;
			width: 25px;
			position: absolute;
			left: 2px;
			top: 2px;
			z-index: 2;
			border-radius: inherit;
			background: #283446; /* Fallback */
			background: linear-gradient(#36455b, #283446);
			box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),  0 0 8px rgba(0,0,0,0.3),  0 12px 12px rgba(0,0,0,0.4);
			-webkit-transition: all .5s ease;
			transition: all .5s ease;
			}
			.switch label + span {
			content: "";
			display: inline-block;
			position: absolute;
			right: 0px;
			top: 7px;
			width: 18px;
			height: 18px;
			border-radius: 10px;
			background: #283446;
			background: gradient-gradient(#36455b, #283446);
			box-shadow: inset 0 1px 0 rgba(0,0,0,0.2),  0 1px 0 rgba(255,255,255,0.1),  0 0 10px rgba(185,231,253,0),  inset 0 0 8px rgba(0,0,0,0.9),  inset 0 -2px 5px rgba(0,0,0,0.3),  inset 0 -5px 5px rgba(0,0,0,0.5);
			-webkit-transition: all .5s ease;
			transition: all .5s ease;
			z-index: 2;
			}
			/* Toggle */
			.switch input:checked ~ label + span {
			content: "";
			display: inline-block;
			position: absolute;
			width: 18px;
			height: 18px;
			border-radius: 10px;
			-webkit-transition: all .5s ease;
			transition: all .5s ease;
			z-index: 2;
			background: #b9f3fe;
			background: gradient-gradient(#ffffff, #77a1b9);
			box-shadow: inset 0 1px 0 rgba(0,0,0,0.1),  0 1px 0 rgba(255,255,255,0.1),  0 0 10px rgba(100,231,253,1),  inset 0 0 8px rgba( 61,157,247,0.8),  inset 0 -2px 5px rgba(185,231,253,0.3),  inset 0 -3px 8px rgba(185,231,253,0.5);
			}
			.switch input:checked ~ label i {
			left: auto;
			left: 63%;
			box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),  0 0 8px rgba(0,0,0,0.3),  0 8px 8px rgba(0,0,0,0.3),  inset -1px 0 1px #b9f3fe;
			-webkit-transition: all .5s ease;
			transition: all .5s ease;
			}
		</style>
	</head>
	<body>
		<div class="row">
			<div id="navbar">
				<div id="holder">
					<ul>
						<li><a href="/index.php">Sensörler</a></li>
						<li><a href="/control.php"  id="onlink">Kontrol Düğmeleri</a></li>
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
				
				$query = "SELECT id,isim FROM demirbas_tipi";
				
				if ($result = $mysqli->query($query)) {
				
				    /* fetch associative array */
				    while ($row = $result->fetch_assoc()) { ?>
			<div class="card col-md-3" z-default="10" z-hover="30" style="display: block; margin: 25px; transition: box-shadow 0.15s; border-bottom: 1px solid rgb(170, 170, 170); padding: 10px; box-shadow: rgb(85, 85, 85) 0px 2px 3.33333px; background:#bbc6d0">
				<center><b><?php printf ("%s", $row["isim"]) ?></b></center>
				<div class="switches" >
					<?php $query = "SELECT id,isim,acik_kapali FROM demirbas WHERE demirbas_tipi ='".$row['id']."'";
						if ($demirbasResult = $mysqli->query($query)) {
						
						    /* fetch associative array */
						    while ($demirbas = $demirbasResult->fetch_assoc()) { ?>
					<div class="row" style="margin-right:0px">
						<div class="col-md-9" >
							<?php printf ("%s", $demirbas["isim"]) ?>
						</div>
						<div class="switch col-md-3">
							<input type="checkbox" <?php if($demirbas["acik_kapali"]) { echo 'checked'; } ?> demirbas-id="<?php printf ("%s", $demirbas["id"]) ?>" name="<?php printf ("%s", $demirbas["isim"]) ?>" onchange="toggleCheckbox(this)">
							<label for="toggle"><i></i></label>
							<span></span>
						</div>
					</div>
					<?php }} ?>
				</div>
			</div>
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
	function toggleCheckbox(element)
	{
	    demirbasDurumGuncelle(element.getAttribute('demirbas-id'), element.checked);
	}
	
	function demirbasDurumGuncelle(demirbasId, hareketTuru)
	{
		$.post('/demirbasDurumGuncelle.php', {dId: demirbasId, hTuru: hareketTuru}, function(data) 
		{
			alert(data);	
		});
	}
	</script>
</html>