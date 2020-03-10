mapboxgl.accessToken = 'pk.eyJ1Ijoic291bmRwcmVzcyIsImEiOiJjazY1OTF3cXIwbjZyM3BtcGt3Y3F2NjZwIn0.3hmCJsl0_oBUpoVsNJKZjQ';

// initial basemap
var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/light-v10',
    center: [-73.96, 40.65],
	pitch: 60,
    zoom: 11
});

map.addControl(new mapboxgl.NavigationControl());

$('#dates').daterangepicker(
	{}, 
	function (start, end) {
		searchByDatesCallback(start, end);
	}
);


$('select, option').click(function(e) {
    e.stopPropagation();
});

$('#boundaryType').change(function(e) {
	updateBoundaryList();
});

$('#dropdownBoundary button').click(function(e) {
	searchByBoundary();
});

feather.replace();

var domMarkers = [];
var mapMarkers = [];
var geojson = {'type': 'FeatureCollection', 'features': []};
var boundariesList = {};
var codes = ['cd', 'pp', 'dsny', 'fb', 'sd', 'hc', 'cc', 'nycongress', 'sa', 'ss', 'bid', 'nta', 'zipcode'];
var clrs = ['#bc2b32', '#be7957', '#d2ac6d', '#77aa98', '#3e7864', '#085732', '#9abe0c', '#f3bd1c', '#f5912f', '#dc2118', '#39a6a5', '#185892', '#7a7e5a'];


map.on('load', function() {
	
	for (let i = 0; i < 13; i++) {
		setBoundary(codes[i], clrs[i], clrs[i]);
	}
	
	if (typeof defaultRequest == 'undefined' || $.isEmptyObject(defaultRequest)) {
		defaultRequest = {'dates': '1 week ago - today'}
	}
	dataRequest(context, defaultRequest);

	window.setTimeout(function (){	
			$('label[for="cd-switch"]').click();
		}, 2000
		
	);
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
			'visibility': 'none',
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
			'visibility': 'none',
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
	
	$(`#${code}-switch`).change(function() {
		if ($(this).is(':checked')) {
			map.setLayoutProperty(code + 'L', 'visibility', 'visible');
			map.setLayoutProperty(code + 'S', 'visibility', 'visible');
		} else {
			map.setLayoutProperty(code + 'L', 'visibility', 'none');
			map.setLayoutProperty(code + 'S', 'visibility', 'none');
		}
	});
	
	$(`label[for="${code}-switch"] hr`).attr('style', `background-color: ${lineClr};`);
}


function dataRequest(trg, q) {
	markersClean();
	var req = `trg=${trg}&${jQuery.param(q)}`;
	console.log(req);
	$.get(`./data/container.php?${req}`,
		function(container) {
			//console.log(container);
			map.jumpTo(container);
		}
	);
	//console.log(`./data/submissions.php?trg=${trg}&${jQuery.param(q)}`);
	$.get(`./data/submissions.php?${req}`, 
		function(data) {
			geojson = data;
			//console.log(data);
			markersGen();
		}
	);
}


function markersClean() {
	domMarkers.forEach(function(marker) {
		marker.remove();
	});
	domMarkers = [];
	mapMarkers.forEach(function(marker) {
		marker.remove();
	});
	mapMarkers = [];
}


function markersGen() {
	if ($.isEmptyObject(geojson.features))
		return;
    geojson.features.forEach(function(marker) {
        // create a DOM element for the marker
        var el = document.createElement('div');
        el.className = 'marker';
        el.style.backgroundImage = 'url(./images/markerB.png)';

        el.addEventListener('click', function() {
			domMarkers.forEach(function(el_) {
				el_.style.backgroundImage = 'url(./images/markerB.png)';
			});
			el.style.backgroundImage = 'url(./images/markerR.png)';
			displayDetails(marker.properties);
        });

        // add marker to map
		domMarkers.push(el);
        mapMarkers.push(new mapboxgl.Marker(el)
				.setLngLat(marker.geometry.coordinates)
				.addTo(map));
    });
	setTimeout(function() {
		domMarkers[0].click();
	}, 2000);
	
}


function displayDetails(dd) {
	console.log(dd);
	$('.details').text('');
	for (var key in dd) {
		if ($(`#details-${key}`)) {
			$(`#details-${key}`).text(dd[key]);
		}
	}
	
	for (let i = 1; i <= 2; i++) {
		$(`#photos:nth-child(${i}) img`).attr('src', '');
	}
	$(`.more-photo-link a`).text('');
	$('#gallery-images').empty();
	
	// main images to img section and modal gallery
	for (let i = 1; i <= dd['images']['main'].length; i++) {
		$(`.photo:nth-child(${i}) img`).attr('src', dd['images']['main'][i - 1]);
		$('#gallery-images').append(`<div class="gallery-img"><img src="${dd['images']['main'][i - 1]}" /></div>`);
	}

	// additional images to link and modal gallery
	if (dd['images']['additional'].length > 0) {
		$('.more-photo-link a').text(`${dd['images']['additional'].length} more photo`);
		for (let i = 1; i <= dd['images']['additional'].length; i++) {
			$('#gallery-images').append(`<div class="gallery-img"><img src="${dd['images']['additional'][i - 1]}" /></div>`);
		}
	}
	
	// search by current property boundary links
	var bType;
	for (let i in codes) {
		bType = codes[i];
		if ($(`#details-${bType}`)) {
			$(`#details-${bType}`).unbind();
			$(`#details-${bType}`).click(function(){
				dataRequest(context, {'btype': codes[i], 'boundary': dd[codes[i]]});
			});
		}
	}

	// search by current plate num link
	if ($('#details-reportsNum')) {
		$('#details-reportsNum').unbind();
		$('#details-reportsNum').click(function(){
			dataRequest(context, {'plate': $('#details-plateNum').text()});
		});
	}
	
	// twit link
	if (dd['tweetLink']) {
		$('#details-twitlink').show();
		$('#details-twitlink').attr('href', dd['tweetLink']);
	} else {
		$('#details-twitlink').hide();
	}
	
	// share link
	$('.details-title svg').unbind();
	$('.details-title svg').click(function() {
		copyLink();
	});
}


// search

function searchByPlate() {
	plate = $('#plate').val();
	if (plate == null || plate == '') {
		alert('red', 'Wrong request', 'Enter valid plate number');
		return;
	}
	dataRequest(context, {'plate': plate});
	$('#plate').val('');
}


function searchByPID() {
	pid = $('#pid').val();
	if (pid == null || pid == '') {
		alert('red', 'Wrong request', 'Enter valid Project ID');
		return;
	}
	dataRequest(context, {'pid': pid});
	$('#pid').val('');
}


function searchByDatesCallback(start, end) {
	dates = `${start.format('MM/DD/YYYY')} - ${end.format('MM/DD/YYYY')}`;
	dataRequest(context, {'dates': dates});
	$('#dates').val('');
}


function searchByDates() {
	dates = $('#dates').val();
	if (dates == null || dates == '') {
		alert('red', 'Wrong request', 'Enter valid date range');
		return;
	}
	dataRequest(context, {'dates': dates});
	$('#dates').val('');
}


function updateBoundaryList() {
	if ($.isEmptyObject(boundariesList)) {
		$.get('./data/boundariesList.json', function (data){
			boundariesList = data;
			updateBoundaryListCallback();
		});
	} else {
		updateBoundaryListCallback();
	}
}
	
function updateBoundaryListCallback() {
	$('#boundary').empty();
	var bType = $('#boundaryType').children("option:selected").val();
	if (bType != '-') {
		boundariesList[bType].forEach(function (bName) {
			$('#boundary').append(`<option value="${bName}">${bName}</option>`);
		});
	}
}


function searchByBoundary() {
	var bType = $('#boundaryType').children("option:selected").val();
	var boundary = $('#boundary').children("option:selected").val();
	if (bType == '-') {
		alert('red', 'Wrong request', 'Enter valid boundary type');
	}
	dataRequest(context, {'btype': bType, 'boundary': boundary});
	$('#boundaryType').val('-');
	updateBoundaryListCallback();
}


function alert(color, hdr, body) {
	$('#alert rect').attr('fill', color);
	$('#alert-header').text(hdr);
	$('#alert-body').text(body);
	$('#alert').toast('show');
}


function copyLink() {
	var el = document.getElementById("details-permalink");
	el.select();
	el.setSelectionRange(0, 99999);
	document.execCommand("copy");
	alert('green', 'Link copied', el.value);
}
