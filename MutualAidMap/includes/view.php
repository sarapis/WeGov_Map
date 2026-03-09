<?php
/*
	$embeddable - accessable within iframe
*/

function view()
{
?><!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8' />
    <title>Mutual Aid Groups</title>
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no,width=device-width,shrink-to-fit=no' />
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v1.8.1/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v1.8.1/mapbox-gl.css' rel='stylesheet' />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>

<body>
	<!-- Modal -->
	<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
	  <div class="modal-dialog" role="document" style="max-width: 850px;">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="modalTitle">Available mutual aid groups</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body" id="modalContent">
		  </div>
		</div>
	  </div>
	</div>
	<!-- /Modal -->
	
	<div id="container" class="container-fluid d-flex h-100 flex-column p-0">
	
	  
		<div class="map flex-fill d-flex">
			<div class="col-12" style="padding:0;">
			
				<div class="row m-0">
					<div  class="col-lg-5 col-md-5 col-sm-12 left_column">
					
						<!-- search form -->
						<div id='searchControls'>
					
							<!-- search input -->
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" placeholder="Click the neighbourhood area or enter your address.." aria-label="Enter your address.." aria-describedby="addon-submit1" id="address">
								<div class="input-group-append">
									<button class="btn btn-outline-primary" id="addon-submit1" onclick="searchByAddress();">Search</button>
								</div>
							</div>
						</div>
						<!-- /search form -->

						
						<!-- toasts -->
						<div aria-live="polite" aria-atomic="true" id="toasts">
							
							<!-- Position it -->
							<div style="position: absolute; top: 0; left: 0;">

								<!-- Then put toasts within -->
								<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="8000" id="alert">
									<div class="toast-header">
										<svg class="bd-placeholder-img rounded mr-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img"><rect width="100%" height="100%" fill="red"></rect></svg>
										<strong class="mr-auto" id="alert-header">Some header</strong>
										<small class="text-muted"></small>
										<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="toast-body" id="alert-body">
										Some body
									</div>
								</div>
							</div>
						</div>
						<!-- /toasts -->
					</div>
				
				</div>
				
				<div id="map"></div>
			</div>
		</div>
	</div>
		<!-- poweredBy note -->
		<div id="poweredBy">
			Map powered by WeGov.NYC
		</div>
		<!-- /poweredBy note -->
	</div>
	
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" defer></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js" defer></script>
	<?php /*<?php if ($_GET['nta']) : ?>
		<script>
			defaultRequest = {'nta': ['<?php echo $_GET['nta']; ?>']}
		</script>
	<?php endif; ?>*/ ?>
	<script src="js/script_covid.js"></script>
	<script>
	  	function openNav() {
			document.getElementById("mySidenav").style.width = "250px";
			// document.getElementById("mySidenav").style.display = "block";
			
		}

		function closeNav() {
			document.getElementById("mySidenav").style.width = "0";
			// document.getElementById("mySidenav").style.display = "none";

		}
  </script>
	
</body>

</html><?php

}