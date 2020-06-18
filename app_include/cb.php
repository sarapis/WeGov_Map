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
}

