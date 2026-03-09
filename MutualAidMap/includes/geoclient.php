<?php 
// NYC Geoclient API - https://api-portal.nyc.gov/
class Geoclient
{
	static function match($addr)
	{
		return self::mapResponce(self::req($addr));
	}

	static function getNtaCode($addr)
	{
		if (defined('GEOCLIENT_KEY') && GEOCLIENT_KEY) {
			$r = self::req($addr);
			if (isset($r['results'][0]['response']['nta']))
				return $r['results'][0]['response']['nta'];
		}
		
		// Fallback to Mapbox if Geoclient is not configured
		return self::getNtaCodeMapbox($addr);
	}

	static function req($addr)
	{
		$url = sprintf(
						'https://api.nyc.gov/geo/geoclient/v1/search.json?input=%s', 
						urlencode($addr)
					);
		$hh = ['Ocp-Apim-Subscription-Key: ' . (defined('GEOCLIENT_KEY') ? GEOCLIENT_KEY : '')];
		$resp = Curl::exec($url, [CURLOPT_HTTPHEADER => $hh]);
		#var_dump($resp);
		return json_decode($resp, true);
	}
	
	// Mapbox Fallback Implementation
	static function getNtaCodeMapbox($addr)
	{
		$token = defined('MAPBOX_KEY') ? MAPBOX_KEY : '';
		$url = sprintf("https://api.mapbox.com/geocoding/v5/mapbox.places/%s.json?access_token=%s&bbox=-74.259,40.477,-73.700,40.917", urlencode($addr), $token);
		
		$resp = Curl::exec($url);
		$json = json_decode($resp, true);
		
		if (empty($json['features'])) return null;
		
		$lng = $json['features'][0]['center'][0];
		$lat = $json['features'][0]['center'][1];
		
		// Load NTA GeoJSON
		$geoJsonFile = __DIR__ . '/../data/nta.geojson';
		if (!file_exists($geoJsonFile)) return null;
		
		$geoJson = json_decode(file_get_contents($geoJsonFile), true);
		
		foreach ($geoJson['features'] as $f) {
			if (self::pointInPolygon([$lng, $lat], $f['geometry'])) {
				return $f['properties']['nameAlt'];
			}
		}
		return null;
	}

	static function pointInPolygon($point, $geometry)
	{
		$polygons = [];
		if ($geometry['type'] == 'Polygon') {
			$polygons[] = $geometry['coordinates'][0]; // Exterior ring
		} elseif ($geometry['type'] == 'MultiPolygon') {
			foreach ($geometry['coordinates'] as $poly) {
				$polygons[] = $poly[0];
			}
		}

		$lng = $point[0];
		$lat = $point[1];
		$inside = false;

		foreach ($polygons as $poly) {
			$count = count($poly);
			for ($i = 0, $j = $count - 1; $i < $count; $j = $i++) {
				$xi = $poly[$i][0]; $yi = $poly[$i][1];
				$xj = $poly[$j][0]; $yj = $poly[$j][1];

				$intersect = (($yi > $lat) != ($yj > $lat)) &&
					($lng < ($xj - $xi) * ($lat - $yi) / ($yj - $yi) + $xi);
				if ($intersect) $inside = !$inside;
			}
			if ($inside) return true;
		}
		return false;
	}
	
	static function req_depr($addr)
	{
		$url = sprintf(
						'https://api.cityofnewyork.us/geoclient/v1/search.json?input=%s&app_id=%s&app_key=%s', 
						urlencode($addr),
						GEOCLIENT_APP,
						GEOCLIENT_KEY
					);
		$resp = Curl::exec($url);
		return json_decode($resp, true);
	}
	
	static function mapResponce($r)
	{
		$r = $r['results'][0]['response'] ?? [];
		return [
			'cd' => $r['communityDistrict'] ?? '',
			'ed' => $r['electionDistrict'] ?? '',
			'pp' => $r['policePrecinct'] ?? '',
			'dsny' => $r['sanitationDistrict'] ?? '',
			'fb' => $r['fireBattalion'] ?? '',
			'sd' => $r['communitySchoolDistrict'] ?? '',
			'hc' => $r['healthCenterDistrict'] ?? '',
			'cc' => $r['cityCouncilDistrict'] ?? '',
			'nycongress' => $r['congressionalDistrict'] ?? '',
			'sa' => $r['assemblyDistrict'] ?? '',
			'ss' => $r['stateSenatorialDistrict'] ?? '',
			'nta' => $r['ntaName'] ?? '',
			'zipcode' => $r['zipCode'] ?? '',
			'lat' => $r['latitude'] ?? '',
			'lng' => $r['longitude'] ?? '',
		];
	}
}