<?php
/*
	$pageType
		'pa' - Plackard Abuse
		'cp' - Capital Projects
*/

function view($pageType)
{
?><!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8' />
    <title>Placard Abuse</title>
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no,width=device-width,shrink-to-fit=no' />
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v1.8.1/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v1.8.1/mapbox-gl.css' rel='stylesheet' />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>

<body>
	<!-- Modal Photo Gallery -->
	<div class="modal fade" id="gallery" tabindex="-1" role="dialog" aria-labelledby="galleryTitle" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="galleryTitle">Gallery</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body" id="gallery-images">
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>
	<!-- /Modal Photo Gallery -->
	
	<div id="container" class="container-fluid d-flex h-100 flex-column p-0">
		
		<header class="site-header">
			<!-- start top logo area -->
			<div class="logo_area">
				<div class="container">
					<div class="border_bottom">
						<a class="logo_left" href="#">
							<img src="images/logo.png" class="img-responsive">
						</a>
						<!-- start main menu area -->
						<div class="main_menu">
							<ul class="external_menubar">
								<li class="dropdown">
									<a class="dropdown-toggle menu_link is-active" data-toggle="dropdown" href="javascript:void(0)">
										<span>Advocacy</span>
									</a>
									<ul class="dropdown-menu">
										<li><a href="https://wegov.nyc/211-4-nyc/" class="menu_link">Searchable Safety Net</a></li>
										<li><a href="https://wegov.nyc/open-engaging-effective-government/" class="menu_link">Digital Transformation</a></li>
										<li><a href="https://wegov.nyc/nymeta/" class="menu_link">Metro-Regional Leadership</a></li>
									</ul>
								</li>
								<li class="dropdown">
									<a class="dropdown-toggle menu_link" data-toggle="dropdown" href="javascript:void(0)"><span>Tools</span></a>
									<ul class="dropdown-menu">
										<li><a href="http://capital.research.wegov.nyc/" class="menu_link">Capital Projects Directory</a></li>
										<li><a href="http://services.wegov.nyc" class="menu_link">Services Directory & API</a></li>
										<li><a href="http://nyclaws.readthedocs.io/" class="menu_link">Charter, Code & Rules</a></li>
										<li><a href="https://wegov.nyc/action/" class="menu_link">The Action App!</a></li>
									</ul>
								</li>
								<li><a href="https://wegov.nyc/news/" class="menu_link"><span>Community</span></a></li>
								<li><a href="https://wegov.nyc/blog/" class="menu_link"><span>Blog</span></a></li>
								<li><a href="https://opencollective.com/wegovnyc" class="menu_link"><span>Donate</span></a></li>
								<li><a href="https://wegov.nyc/contact/" class="menu_link"><span>Contact</span></a></li>
							</ul>
						</div>
						<!-- end main menu area -->
						<!-- <a class="nav-link " id="google_translate_element"></a> -->
						<div class="top-bar-right">
							<button type="button" onclick="openNav()"  class="btn btn-raised btn-block btn-primary menu_filter">
								<!-- <img src="resources/menu.svg" alt="" title="">Menu  -->
								Menu
							</button>
						</div>
					</div>
				</div>
			</div>
			<!-- end top logo area -->

			<div class="submenu_div">
				<div class="container">
					<span class="badge badge-light title_top_header" >Actions</span>
					<a class="menu_link" href="covidprojects.php">Mutual Aid Groups</a>
					<a class="menu_link<?php echo $pageType == 'pa' ? ' active' : '" href="placabuse.php'; ?>">Placard Abuse</a>
					<a class="menu_link<?php echo $pageType == 'cp' ? ' active' : '" href="capprojects.php'; ?>">Capital Projects</a>
					<a class="menu_link" href="https://wegov.nyc/tools/action/">About</a>
					<!-- <a class="menu_link float-right" href="javascript:void(0)">Download the App</a> -->
				</div>
			</div>
			<!-- start mobile menu area -->
			<div class="responsive_menu" id="mySidenav">
				<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">
					<img src="images/close-white.png" alt="" title="">
				</a>
				<ul class="external_menubar">
					<li class="dropdown">
						<a class="dropdown-toggle menu_link is-active" data-toggle="dropdown" href="javascript:void(0)">
							<span>Advocacy</span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="https://wegov.nyc/211-4-nyc/" class="menu_link">Searchable Safety Net</a></li>
							<li><a href="https://wegov.nyc/open-engaging-effective-government/" class="menu_link">Digital Transformation</a></li>
							<li><a href="https://wegov.nyc/nymeta/" class="menu_link">Metro-Regional Leadership</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a class="dropdown-toggle menu_link" data-toggle="dropdown" href="javascript:void(0)"><span>Tools</span></a>
						<ul class="dropdown-menu">
							<li><a href="http://capital.research.wegov.nyc/" class="menu_link">Capital Projects Directory</a></li>
							<li><a href="http://services.wegov.nyc" class="menu_link">Services Directory & API</a></li>
							<li><a href="http://nyclaws.readthedocs.io/" class="menu_link">Charter, Code & Rules</a></li>
							<li><a href="https://wegov.nyc/action/" class="menu_link">The Action App!</a></li>
						</ul>
					</li>
					<li><a href="https://wegov.nyc/news/" class="menu_link"><span>Community</span></a></li>
					<li><a href="https://wegov.nyc/blog/" class="menu_link"><span>Blog</span></a></li>
					<li><a href="https://opencollective.com/wegovnyc" class="menu_link"><span>Donate</span></a></li>
					<li><a href="https://wegov.nyc/contact/" class="menu_link"><span>Contact</span></a></li>
				</ul>
			</div>
			<!-- end mobile menu area -->
		</header>
		
		<div class="map flex-fill d-flex">
			<div class="col-12" style="padding:0;">
			
				<div class="row m-0">
					<div  class="col-lg-9 col-md-9 col-sm-12 left_column">
						<!-- search form -->
						<div id='searchControls'>
						
							<?php if ($pageType == 'pa') : ?>
								<div class="btn-group dropdown">
									<button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Search by Plate
									</button>
									<div class="dropdown-menu fade" id="dropdownPlate">
										<div class="px-4 py-3">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control" placeholder="Plate number.." aria-label="Plate number.." aria-describedby="addon-submit1" id="plate">
												<div class="input-group-append">
													<button class="btn btn-outline-primary" id="addon-submit1" onclick="searchByPlate();">Search</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								
							<?php else : ?>
								<div class="btn-group dropdown">
									<button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Search by Project ID
									</button>
									<div class="dropdown-menu fade" id="dropdownPID">
										<div class="px-4 py-3">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control" placeholder="Project ID.." aria-label="Project ID.." aria-describedby="addon-submit1" id="pid">
												<div class="input-group-append">
													<button class="btn btn-outline-primary" id="addon-submit1" onclick="searchByPID();">Search</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								
							<?php endif; ?>
							
							<div class="btn-group dropdown">
								<button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Search by Date Range
								</button>
								<div class="dropdown-menu fade" id="dropdownDates">
									<div class="px-4 py-3">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control" placeholder="Date range.." aria-label="Date range.." aria-describedby="addon-submit2" id="dates">
											<div class="input-group-append">
												<button class="btn btn-outline-primary" id="addon-submit2" onclick="searchByDates();">Search</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="btn-group dropdown">
								<button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Search by Boundary
								</button>
								<div class="dropdown-menu fade" id="dropdownBoundary">
									<div class="px-4 py-3">
										<select class="custom-select custom-select-sm mb-2" id="boundaryType" onchange="updateBoundaryList();">
										<option value="-" selected>Open this select menu</option>
										<option value="cd">Community Districts</option>
										<option value="pp">Police Precincts</option>
										<option value="dsny">Sanitation Districts</option>
										<option value="fb">Fire Battilion</option>
										<option value="sd">School Districts</option>
										<option value="hc">Health Center Districts</option>
										<option value="cc">City Council Districts</option>
										<option value="nycongress">Congressional Districts</option>
										<option value="sa">State Assembly Districts</option>
										<option value="ss">State Senate Districts</option>
										<option value="bid">Business Improvement District</option>
										<option value="nta">Neighborhood Tabulation Area</option>
										<option value="zipcode">Zip Code</option>
										</select>
										<select class="custom-select custom-select-sm mb-2" id="boundary">
										</select>
										<button class="btn btn-outline-primary btn-sm">Search</button>
									</div>
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
					</div>
					<!-- /toasts -->
					<div class=" col-lg-3 col-md-3 col-sm-6 ml-auto right_column">
						<!-- details -->
							<div id='details'>
								<?php include "../app_include/{$pageType}.html"; ?>
							</div>
						<!-- /details -->
						
						<!-- images -->
							<div id='images'>
								<div id="photos">
									<div class="photo"><a data-toggle="modal" href="#gallery"><img /></a></div>
									<div class="photo"><a data-toggle="modal" href="#gallery"><img /></a></div>
								</div>
								<div class="more-photo-link"><a data-toggle="modal" href="#gallery" class="details"></a></div>
							</div>
						<!-- /images -->
						
						<!-- districts -->
							<div id='districts'>
								<p><span class="district-hdr">Community District</span><a id="details-cd" class="details"></a></p>
								<p><span class="district-hdr">Police Precinct</span><a id="details-pp" class="details"></a></p>
								<p><span class="district-hdr">Sanitation District</span><a id="details-dsny" class="details"></a></p>
								<p><span class="district-hdr">Fire Battilion</span><a id="details-fb" class="details"></a></p>
								<p><span class="district-hdr">School District</span><a id="details-sd" class="details"></a></p>
								<p><span class="district-hdr">Health Center District</span><a id="details-hc" class="details"></a></p>
								<p><span class="district-hdr">City Council District</span><a id="details-cc" class="details"></a></p>
								<p><span class="district-hdr">Congressional District</span><a id="details-nycongress" class="details"></a></p>
								<p><span class="district-hdr">State Assembly District</span><a id="details-sa" class="details"></a></p>
								<p><span class="district-hdr">State Senate District</span><a id="details-ss" class="details"></a></p>
								<!-- <p><span class="district-hdr">Business Improvement District</span><a id="details-bid" class="details"></a></p>	-->
								<p><span class="district-hdr">Zip Code</span><a id="details-zipcode" class="details"></a></p>
								<p><span class="district-hdr">Neighborhood Tabulation Area</span><a id="details-nta" class="details"></a></p>
							</div>
						<!-- /districts -->
					</div>
				</div>
				
				<!-- toggles -->
				<div id='toggles'>
					<div class="custom-control custom-switch">
					  <input type="checkbox" class="custom-control-input" id="cd-switch">
					  <label class="custom-control-label" for="cd-switch">Community Districts<hr class="border-sample"></label>
					</div>
					<div class="custom-control custom-switch">
					  <input type="checkbox" class="custom-control-input" id="pp-switch">
					  <label class="custom-control-label" for="pp-switch">Police Precincts<hr class="border-sample"></label>
					</div>
					<div class="custom-control custom-switch">
					  <input type="checkbox" class="custom-control-input" id="dsny-switch">
					  <label class="custom-control-label" for="dsny-switch">Sanitation Districts<hr class="border-sample"></label>
					</div>
					<div class="custom-control custom-switch">
					  <input type="checkbox" class="custom-control-input" id="fb-switch">
					  <label class="custom-control-label" for="fb-switch">Fire Battilion<hr class="border-sample"></label>
					</div>
					<div class="custom-control custom-switch">
					  <input type="checkbox" class="custom-control-input" id="sd-switch">
					  <label class="custom-control-label" for="sd-switch">School Districts<hr class="border-sample"></label>
					</div>
					<div class="custom-control custom-switch">
					  <input type="checkbox" class="custom-control-input" id="hc-switch">
					  <label class="custom-control-label" for="hc-switch">Health Center Districts<hr class="border-sample"></label>
					</div>
					<div class="custom-control custom-switch">
					  <input type="checkbox" class="custom-control-input" id="cc-switch">
					  <label class="custom-control-label" for="cc-switch">City Council Districts<hr class="border-sample"></label>
					</div>
					<div class="custom-control custom-switch">
					  <input type="checkbox" class="custom-control-input" id="nycongress-switch">
					  <label class="custom-control-label" for="nycongress-switch">Congressional Districts<hr class="border-sample"></label>
					</div>
					<div class="custom-control custom-switch">
					  <input type="checkbox" class="custom-control-input" id="sa-switch">
					  <label class="custom-control-label" for="sa-switch">State Assembly Dist...<hr class="border-sample"></label>
					</div>
					<div class="custom-control custom-switch">
					  <input type="checkbox" class="custom-control-input" id="ss-switch">
					  <label class="custom-control-label" for="ss-switch">State Senate Districts<hr class="border-sample"></label>
					</div>
					<div class="custom-control custom-switch">
					  <input type="checkbox" class="custom-control-input" id="bid-switch">
					  <label class="custom-control-label" for="bid-switch">Business Improvem...<hr class="border-sample"></label>
					</div>
					<div class="custom-control custom-switch">
					  <input type="checkbox" class="custom-control-input" id="nta-switch">
					  <label class="custom-control-label" for="nta-switch">Neighborhood Tab...<hr class="border-sample"></label>
					</div>
					<div class="custom-control custom-switch">
					  <input type="checkbox" class="custom-control-input" id="zipcode-switch">
					  <label class="custom-control-label" for="zipcode-switch">Zip Code<hr class="border-sample"></label>
					</div>
				</div>
				<!-- /toggles -->
				
				<div id="map"></div>
			</div>
		</div>
	</div>
	
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
	<?php if ($_GET['id']) : ?>
		<script>
			defaultRequest = {'id': '<?php echo $_GET['id']; ?>'}
		</script>
	<?php endif; ?>
	<script src="js/script.js"></script>
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