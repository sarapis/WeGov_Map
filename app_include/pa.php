<?php

class SubView
{
	static public $type = 'pa';
	
	static function DetailsCard()
	{
	?>
	<!-- details -->
		<div id='details'>
			<p class="details-title">
				<small>Date:</small>
				<span id="details-date" class="details"></span>
				<textarea id="details-permalink" class="details"></textarea>
				<a title="Share direct link"><i data-feather="share"></i></a>
			</p>
			<div class="row">
				<p class="col-2 ml-0 flex"><small>Plate:</small></p>
				<h4 class="col-3 badge badge-light" style="text-align: center; margin: 2px 0 -2px;">
					<span id="details-plateState" class="details"></span>&nbsp;
					<span id="details-plateNum" class="details"></span>
				</h4>
				<p class="col-7 mr-0"><small># reports:</small><a id="details-reportsNum" class="details"></a></p>
			</div>
			<p><small>Coords:</small><span id="details-coordinates" class="details"></span></p>
			<p><small>Address:</small><span id="details-address" class="details"></span></p>
			<div id="msg-box">
				<p style="position: absolute; text-overflow: ellipsis; overflow: hidden;">
					<small>Message:</small>
					<a id="details-twitlink" href="#" target="_blank" style="display: none;" title="Open in Twitter"><i data-feather="twitter"></i></a>
					<span id="details-message" class="details"></span>
				</p>
			</div>
			<script>
				var context = 'pa';
			</script>
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
	<?php
	}
}


