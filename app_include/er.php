<?php

class SubView
{
	static public $type = 'er';
	
	static function searchForm()
	{
	?>
	</div>
	<div  id='searchControls-er'>
		<div class="form-group form-group-sm col pl-0 ml-0 my-1">
			<label for="event">Election / Position</label>
			<select class="form-control form-control-sm" id="event" name="event">
			</select>
		</div>		
		<div class="form-group form-group-sm col pl-0 ml-0 my-1">
			<label for="aggr">Aggregation</label>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="radio" name="aggregation" id="aggr1" value="ad" checked>
			  <label class="form-check-label" for="aggr1">Assembly District</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="radio" name="aggregation" id="aggr2" value="aded">
			  <label class="form-check-label" for="aggr2">Election District</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="radio" name="aggregation" id="aggr3" value="county">
			  <label class="form-check-label" for="aggr3">County</label>
			</div>
		</div>
		<label for="display">Display</label>
		
		<div class="row mx-0">
			<div class="form-check form-check-inline col-5 mr-0">
				<label class="form-check-label">Area Fill</label>
				<div class="custom-control custom-switch">
				  <input type="checkbox" class="custom-control-input" id="nameSw">
				  <label class="form-check-label custom-control-label" for="nameSw">Candidate Name</label>
				</div>
			</div>
			<div class="form-check form-check-inline col-4 mr-0">
				<label class="form-check-label">Solid</label>
				<div class="custom-control custom-switch">
				  <input type="checkbox" class="custom-control-input" id="shadSw">
				  <label class="form-check-label custom-control-label" for="shadSw">Shading</label>
				</div>
			</div>
		</div>
		<div style="position:absolute; right: 20px; bottom: 20px; padding:0; margin:0;">
			<button class="btn btn-outline-primary" id="addon-submit1" onclick="erRequest();">Search</button>
		</div>
			
	<?php
	}


	static function detailsCard()
	{
	?>
	<!-- details -->
		<div id='details' style="display:none;">
			<p class="details-header" id="event-title"></p>
			<p class="details-header"><span class="loc-hdr"></span>: <span class="loc-title"></span></p>
			<table>
			  <thead>
				<tr><th>Participant</th><th>Votes</th><th>Rate</th></tr>
			  </thead>
			  <tbody>
			  </tbody>
			</table>
			<p class="no-results details" style="display:none;">No results</p>
		</div>
		<script>
			var context = 'er';
		</script>	
	<!-- /details -->
	<?php
	}

	static function script()
	{
	?>
	<script>
		var dicts = {}
		$.get('./data/electionresults.php', {'trg': 'select'}, function (resp, status, xhr) {
			dicts = resp;
			for (var evcode in dicts.events) {
				$('#event').append(`<option value="${evcode}">${dicts.events[evcode]}</option>`);
			}
		});
		
		map.on('load', function() {
			//loadER('General Election 2018 - 11/06/2018|Governor/Lieutenant Governor|', 'ad', 'fill-shading');
		});

		function loadER(event, division, display) {
			var req = {'event': event, 'division': division, 'display': display, 'trg': 'expr'}
			
			$.get('./data/electionresults.php', req, function (resp, status, xhr) {
				['er', 'erL', 'erS', 'erH'].forEach(function (id, idx) {
					if (map.getLayer(id)) 
						map.removeLayer(id);
				});
				if (map.getSource('er')) 
						map.removeSource('er');
					
				map.addSource('er', {
					type: 'geojson',
					data: resp['geojson']
				});

				
				if (['fill-shading', 'fill-solid'].includes(display)) {
					
					map.addLayer(
						{
							'id': 'er',
							'type': 'fill',
							'source': 'er',
							'layout': {},
							'paint': {
								'fill-color': resp['expr']
							}
						},
						'waterway-label'
					);
					
				} else {
				
					map.addLayer(
						{
							'id': 'er',
							'type': 'fill',
							'source': 'er',
							'layout': {},
							'paint': {
								'fill-color': 'rgb(0,0,0)',
								'fill-opacity': 0
							}
						},
						'waterway-label'
					);
					
					map.addLayer({
						"id": 'erL',
						"type": "line",
						"source": 'er',
						"layout": {
							//'visibility': 'true',
						},
						"paint": {
							"line-color": resp['expr'],
							"line-width": 0.5,
							'line-opacity': 0.8
						}
					});

					map.addLayer(
						{
							'id': 'erS',
							'type': 'symbol',
							'source': 'er',
							'paint': {
								'text-color': resp['expr'],
								"text-halo-color": "hsl(0, 0%, 100%)",
								"text-halo-width": 0.5,
								"text-halo-blur": 1
							},
							"layout": {
								'text-field': '{winnerEnh}',
								//'visibility': 'none',
								'text-size': {
									"base": 1,
									"stops": [
										[12, 12],
										[16, 16]
									]
								},
							},
						},
						'waterway-label'
					);
				}
				
				map.addLayer(
					{
						'id': 'erH',
						'type': 'fill',
						'source': 'er',
						'layout': {},
						'paint': {
							'fill-color': 'rgb(0,0,0)',
							'fill-opacity': 0.2
						},
						'filter': ['in', 'nameCol', '']
					},
					'waterway-label'
				);
				
				map.on('click', 'er', function(e) {
					showLocResults(dicts.events[event], division, e.features[0].properties);
					map.setFilter('erH', ['in', 'nameCol', e.features[0].properties.nameCol]);
				});
			});
		}
		
		function showLocResults(event, div, properties) {
			//console.log([event, div, properties, properties.results]);
			$('#details').show();
			$('#details tbody tr').remove();
			$('#event-title').html(event);
			var tt = {'ad': 'Assembly District', 'aded': 'Election District', 'county': 'County'}
			$('.loc-hdr').html(tt[div]);
			$('.loc-title').html(properties['nameCol']);
			if (typeof properties.results == 'undefined') {
				$('#details .no-results').show();
				$('#details table').hide();
			} else {
				$('#details table').show();
				$('#details .no-results').hide();
				JSON.parse(properties.results).forEach(function (p, idx) {
					$('#details tbody').append(`<tr><td>${p.name}</td><td>${p.tally}</td><td>${p.perc}%</td></tr>`);				
				})
			}
		}
		
		function erRequest() {
			var ev = $('#event option:selected').val();
			var ag = $('input[name="aggregation"]:checked').val();
			var name = $('#nameSw').is(':checked') ? 'names' : 'fill';
			var shad = $('#shadSw').is(':checked') ? 'shading' : 'solid';
			
			console.log([ev, ag, name, shad, `${name}-${shad}`]);
			loadER(ev, ag, `${name}-${shad}`);
		}
		
	</script>
	<?php
	}
}

