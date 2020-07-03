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
			<label for="event">Contest</label>
			<select class="form-control form-control-sm" id="event" name="event">
			</select>
		</div>		
		<div class="form-group form-group-sm col pl-0 ml-0 my-1">
			<label>Map Type</label>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="radio" name="display" id="disp1" value="er" checked>
			  <label class="form-check-label" for="disp1">Election Results</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="radio" name="display" id="disp2" value="su">
			  <label class="form-check-label" for="disp2">Candidates Top Support</label>
			</div>
		</div>
		<div class="form-group form-group-sm col pl-0 ml-0 my-1">
			<label for="aggr">Geography</label>
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
		<label>Style Options</label>
		
		<div class="row mx-0">
			<div class="form-check form-check-inline col mr-0">
				<label class="form-check-label" style="position:relative; top: -3px;">Solid Fill</label>
				<div class="custom-control custom-switch">
				  <input type="checkbox" class="custom-control-input" id="shadSw">
				  <label class="form-check-label custom-control-label" for="shadSw">Shading Fill</label>
				</div>
			</div>
			<div class="form-check form-check-inline col mr-0">
				<label class="form-check-label" style="position:relative; top: -3px;">White Text</label>
				<div class="custom-control custom-switch">
				  <input type="checkbox" class="custom-control-input" id="txtSw">
				  <label class="form-check-label custom-control-label" for="txtSw">Black Text</label>
				</div>
			</div>
			<div class="form-check form-check-inline col-3 mr-0">
			</div>
		</div>
		<div style="position:absolute; right: 20px; bottom: 10px; padding:0; margin:0;">
			<button class="btn btn-sm btn-info" id="addon-csv" onclick="erRequestCSV();" style="min-width: 60px;">CSV</button>
			<button class="btn btn-sm btn-outline-primary" id="addon-submit1" onclick="erRequest();" style="min-width: 60px;">Draw</button>
		</div>
		<form id="csv-downloader" action="./data/electionresults.php">
			<input type="hidden" name="trg" value="csv">
			<input type="hidden" name="event">
			<input type="hidden" name="division">
			<input type="hidden" name="display">
			<input type="hidden" name="design">
		</form>
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

		function loadER(event, division, display, design, txtcolor) {
			var req = {'event': event, 'division': division, 'display': display, 'design': design, 'trg': 'expr'}
			
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

				
				if (['fill-shading', 'fill-solid'].includes(design)) {
					
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
					
					map.addLayer(
						{
							'id': 'erS',
							'type': 'symbol',
							'source': 'er',
							'paint': {
								'text-color': txtcolor == 'white' ? "rgb(255,255,255)" : "rgb(0,0,0)",
								"text-halo-color": "hsl(0, 0%, 100%)",
								"text-halo-width": 0.2,
								"text-halo-blur": 1
							},
							"layout": {
								'text-field': '{winnerEnh}',
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
					
				}/* else {
				
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
				*/
				map.addLayer(
					{
						'id': 'erH',
						'type': 'fill',
						'source': 'er',
						'layout': {},
						'paint': {
							'fill-color': 'rgb(230,230,230)',
							'fill-opacity': 0.5
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
				if (typeof properties.msg == 'undefined') 
					$('#details .no-results').text('No results')
				else
					$('#details .no-results').text(properties.msg);
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
			var disp = $('input[name="display"]:checked').val();
			var shad = $('#shadSw').is(':checked') ? 'shading' : 'solid';
			var txtcol = $('#txtSw').is(':checked') ? 'black' : 'white';
			
			//console.log([ev, ag, disp, name, shad, `${name}-${shad}`]);
			loadER(ev, ag, disp, `fill-${shad}`, txtcol);
		}
		
		function erRequestCSV() {
			var ev = $('#event option:selected').val();
			var ag = $('input[name="aggregation"]:checked').val();
			var disp = $('input[name="display"]:checked').val();
			//var name = $('#nameSw').is(':checked') ? 'names' : 'fill';
			var shad = $('#shadSw').is(':checked') ? 'shading' : 'solid';
			
			$('#csv-downloader input[name="event"]').val(ev);
			$('#csv-downloader input[name="division"]').val(ag);
			$('#csv-downloader input[name="display"]').val(disp);
			$('#csv-downloader input[name="design"]').val(`fill-${shad}`);
			$('#csv-downloader').submit();
		}
		
	</script>
	<?php
	}
}

