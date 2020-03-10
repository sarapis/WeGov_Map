<?php 
class Airtable 
{
	static function getPage($offs = null)
	{
		$hh = ["Authorization: Bearer " . AIRTABLE_KEY];
		$url = sprintf(
						'https://api.airtable.com/v0/%s/Submissions?maxRecords=100&view=Grid+view%s', 
						AIRTABLE_DOC,
						($offs ? "&offset={$offs}" : '')
					);
		$resp = Curl::exec($url, [CURLOPT_HTTPHEADER => $hh]);
		return json_decode($resp, true);
	}
	
	function mapResponce($raw)
	{
		$res = [];
		foreach ($raw as $r)
			$res[] = [
				'id' => $r['id'],
				'date' => date('Y-m-d H:i:s', strtotime($r['fields']['timestamp'])),
				'plateNum' => $r['fields']['Plate Number'],
				'plateState' => $r['fields']['Plate State'],
				'capID' => $r['fields']['Capital Project ID'],
				'type' => $r['fields']['Form Type'],
				'lat' => $r['fields']['Latitude'],
				'lng' => $r['fields']['Longitude'],
				'address' => $r['fields']['Address'],
				'note' => trim($r['fields']['Private Note']),
				'message' => trim($r['fields']['Public Message']),
				'img1' => self::parseUrl($r['fields']['Photo 1']),
				'img2' => self::parseUrl($r['fields']['Photo 2']),
				'img3' => self::parseUrl($r['fields']['Photo 3']),
				'img4' => self::parseUrl($r['fields']['Photo 4']),
				'tweetLink' => $r['fields']['Tweet'],
				'commentLink' => $r['fields']['Comment'],
			];
		return $res;
	}
	
	static function parseUrl($s)
	{
		preg_match_all('~(https?://[-_#$&?/\.\w]+)~si', $s, $rr);
		return $rr[0][0];
	}
	
	static function getAll()
	{
		$res = [];
		$offs = null;
		while (True)
		{
			$resp = self::getPage($offs);
			foreach (self::mapResponce($resp['records']) as $r)
				$res[$r['id']] = $r;
			$offs = $resp['offset'];
			if (!$offs)
				return $res;
		}
	}
}