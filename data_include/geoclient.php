<?php 
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