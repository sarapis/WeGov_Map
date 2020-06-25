<?php

class SubView
{
	static public $type = 'cb';
	
	static function searchForm()
	{
	?>
		<div class="input-group input-group-sm col-lg-6 col-md-6 col-sm-12 pl-0">
			<input type="text" class="form-control" placeholder="Enter an address.." aria-label="Enter an address.." aria-describedby="addon-submit1" id="address">
			<div class="input-group-append">
				<button class="btn btn-outline-primary" id="addon-submit1" onclick="searchBoundariesByAddress();">Search</button>
			</div>
		</div>		
	<?php
	}


	static function detailsCard()
	{
	?>
	<!-- details -->
		<div id='details'>
			<p class="details-title">
				<textarea id="details-permalink" class="details"></textarea>
				<span id="details-addr"></span> <a title="Share direct link"><i data-feather="share"></i></a>
			</p>
			<script>
				var context = 'cb';
				defaultRequest = {'address': ''}
			</script>	
		</div>
	<!-- /details -->
	<?php
	}
	
	static function districtsCard()
	{
	?>
						<!-- districts -->
							<div id='districts'>
								<p><span class="district-hdr">Community District</span><a id="details-cd" class="details"></a></p>
								<p><span class="district-hdr">Election District</span><a id="details-ed" class="details"></a></p>
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
	<?php
	}

}

