mapboxgl.accessToken = 'pk.eyJ1Ijoic291bmRwcmVzcyIsImEiOiJjazY1OTF3cXIwbjZyM3BtcGt3Y3F2NjZwIn0.3hmCJsl0_oBUpoVsNJKZjQ';

// initial basemap
var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/light-v10',
    center: [-73.96, 40.65],
	//pitch: 60,
    zoom: 11
});

map.addControl(new mapboxgl.NavigationControl());

feather.replace();


map.on('load', function() {	
	setBoundary('nta', '#185892', '#185892');
	
	map.on('click', function(e) {
		// set bbox as 5px reactangle area around clicked point
		var bbox = [
			[e.point.x - 5, e.point.y - 5],
			[e.point.x + 5, e.point.y + 5]
		];
		var features = map.queryRenderedFeatures(bbox, {
			layers: ['ntaF']
		});
		 
		// Run through the selected features and set a filter
		// to match features with unique FIPS codes to activate
		// the `counties-highlighted` layer.
		var filter = features.reduce(
			function(memo, feature) {
				memo.push(feature.properties.nameAlt);
				return memo;
			},
			['in', 'nameAlt']
		);
		console.log(filter);
		dataRequest({'nta': filter.slice(2)});
		map.setFilter('ntaH', filter);
	});
	
	if (typeof defaultRequest !== 'undefined') {
		dataRequest(defaultRequest);
	}
});


function setBoundary(code, lineClr, symbClr) {

    map.addSource(code, {
        type: "geojson",
        data: `./data/${code}.geojson`
    });

    map.addLayer({
        "id": code + 'L',
        "type": "line",
        "source": code,
        "layout": {
			'visibility': 'visible',
        },
        "paint": {
            "line-color": lineClr,
            "line-width": 1
        }
    });

    map.addLayer({
        "id": code + 'S',
        "type": "symbol",
        "source": code,
        "layout": {
            'text-field': '{nameCol}',
			'visibility': 'visible',
            'text-size': {
                "base": 1,
                "stops": [
                    [12, 12],
                    [16, 16]
                ]
            },
        },
        "paint": {
            "text-color": symbClr,
            "text-halo-color": "hsl(0, 0%, 100%)",
            "text-halo-width": 0.5,
            "text-halo-blur": 1
        }
    });
	
    map.addLayer({
			"id": code + 'F',
			"type": "fill",
			"source": code,
			"layout": {
				'visibility': 'visible',
			},
			"paint": {
				'fill-outline-color': 'rgba(0,0,0,0.01)',
				'fill-color': 'rgba(0,0,0,0.01)'
			}
		},
		'settlement-label'
	);

    map.addLayer({
			"id": code + 'H',
			"type": "fill",
			"source": code,
			"layout": {
				'visibility': 'visible',
			},
			'paint': {
				'fill-outline-color': lineClr,
				'fill-color': lineClr,
				'fill-opacity': 0.4
			},
			'filter': ['in', 'nameAlt', '']
		},
		'settlement-label'
    );

	$.get('./data/covidprojectscoverage.php', 
		function(ntas) {
			var filter = ntas.reduce(
				function(memo, nta) {
					memo.push(nta.nta);
					return memo;
				},
				['in', 'nameAlt']
			);
			console.log(filter);
			
			map.addLayer({
					"id": code + 'HH',
					"type": "fill",
					"source": code,
					"layout": {
						'visibility': 'visible',
					},
					'paint': {
						'fill-color': '#7a7e5a',
						'fill-opacity': 0.3
					},
					'filter': filter
				},
				'settlement-label'
			);
		}
	);

}


function dataRequest(q) {
	var req = jQuery.param(q);
	console.log(req);
	$.get(`./data/covidprojects.php?${req}`, 
		function(data) {
			$('#modalContent').html(data);
			feather.replace();
			$('#modal').modal();
		}
	);
}



// search

function searchByAddress() {
	var addr = $('#address').val();
	if (addr == null || addr == '') {
		alert('red', 'Wrong request', 'Please enter valid address');
		return;
	}
	dataRequest({'address': addr});
	$('#address').val('');
}


function alert(color, hdr, body) {
	$('#alert rect').attr('fill', color);
	$('#alert-header').text(hdr);
	$('#alert-body').text(body);
	$('#alert').toast('show');
}
