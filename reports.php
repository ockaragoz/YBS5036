<html>
	<head>
		<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
		<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" />
		<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" />
		
		<meta charset="utf-8">
		<title>Sensör Değerleri</title>
		<style>
			body {
			margin:2%;
			background: #202737;
			}
			tbody {
			background: ghostwhite;
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
			
			label {
				color: white;
			}
		</style>
	</head>
	<body>
		<div class="row" style="margin-bottom: 15px;"">
			<div id="navbar">
				<div id="holder">
					<ul>
						<li><a href="/index.php">Sensörler</a></li>
						<li><a href="/control.php">Kontrol Düğmeleri</a></li>
						<li><a href="/reports.php" id="onlink">Raporlar</a></li>
					</ul>
				</div>
				<!-- end holder div --> 
			</div>
			<!-- end navbar div -->
		</div>
				<?php  
					$mysqli = new mysqli("localhost", "root", "", "fabrika_yonetim");
					
					/* check connection */
					if ($mysqli->connect_errno) {
						printf("Connect failed: %s\n", $mysqli->connect_error);
						exit();
					} else {
						$mysqli->query("SET NAMES UTF8");
					}
					
					$query ="SELECT demirbas.isim as isim, hareket_turu as 'hareket turu', hareket_zamani as 'hareket zamanı' FROM hareket, demirbas WHERE hareket.demirbas_id = demirbas.id Order By hareket_zamani desc";  
					myTable($mysqli, $query);  


					function myTable($obConn,$sql)
					{
						$rsResult = mysqli_query($obConn, $sql) or die(mysqli_error($obConn));
						if(mysqli_num_rows($rsResult)>0)
						{
							//We start with header. >>>Here we retrieve the field names<<<
							echo "<table class=\"table table-striped table-bordered\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\"><tr align=\"center\" bgcolor=\"#CCCCCC\">";
							$i = 0;
							while ($i < mysqli_num_fields($rsResult)){
							   $field = mysqli_fetch_field_direct($rsResult, $i);
							   $fieldName=$field->name;
							   echo "<td><strong>$fieldName</strong></td>";
							   $i = $i + 1;
							}
							echo "</tr>"; 
							//>>>Field names retrieved<<<

							//We dump info
							$bolWhite=true;
							while ($row = mysqli_fetch_assoc($rsResult)) {
								echo $bolWhite ? "<tr bgcolor=\"#CCCCCC\">" : "<tr bgcolor=\"#FFF\">";
								$bolWhite=!$bolWhite;
								$i=0;
								foreach($row as $data) {
									if ($i == 1)
									if ($data == 1)
										$data = 'açıldı';
									else if ($data == 0) 
										$data = 'kapandı';
									
									echo "<td align='center'> $data</td>";
									$i++;
								}
								echo "</tr>";
							}
							echo "</table>";
						}
					}					
				?>  
		</section>
	</body>
	<script>
	$(document).ready(function() {
		$('#example').DataTable();
	} );
	</script>
</html>