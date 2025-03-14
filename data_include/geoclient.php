<?php 
// Now https://api-portal.nyc.gov/    maxim.pokrovsky@gmail.com Maxim2011!
class Geoclient
{
	static function match($addr)
	{
		return self::mapResponce(self::req($addr));
	}

	static function getNtaCode($addr)
	{
		$r = self::req($addr);
		$r = $r['results'][0]['response'];
		return $r['nta'];
	}

	static function req($addr)
	{
		$url = sprintf(
						'https://api.nyc.gov/geo/geoclient/v1/search.json?input=%s', 
						urlencode($addr)
					);
		$hh = ['Ocp-Apim-Subscription-Key: ' . GEOCLIENT_KEY];
		$resp = Curl::exec($url, [CURLOPT_HTTPHEADER => $hh]);
		#var_dump($resp);
		return json_decode($resp, true);
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
		$r = $r['results'][0]['response'];
		return [
			'cd' => $r['communityDistrict'],
			'ed' => $r['electionDistrict'],
			'pp' => $r['policePrecinct'],
			'dsny' => $r['sanitationDistrict'],
			'fb' => $r['fireBattalion'],
			'sd' => $r['communitySchoolDistrict'],
			'hc' => $r['healthCenterDistrict'],
			'cc' => $r['cityCouncilDistrict'],
			'nycongress' => $r['congressionalDistrict'],
			'sa' => $r['assemblyDistrict'],
			'ss' => $r['stateSenatorialDistrict'],
			'nta' => $r['ntaName'],
			'zipcode' => $r['zipCode'],
			'lat' => $r['latitude'],
			'lng' => $r['longitude'],
		];
	}
}