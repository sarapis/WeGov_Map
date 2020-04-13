<?php 
class Airtable 
{
	static function getPage($type, $offs = null)
	{
		$tt = [ 'submissions' => [SUBMISSIONS_DOC, 'Submissions'], 
				'covid_prj' => [COVID_DOC, 'Projects'], 
				'covid_pods' => [COVID_DOC, 'Pods'], 
				'nta' => [COVID_DOC, 'Neighborhood'], 
			  ];
		$hh = ["Authorization: Bearer " . AIRTABLE_KEY];
		$url = sprintf( 'https://api.airtable.com/v0/%s/%s?view=Grid+view%s', 
						$tt[$type][0],
						$tt[$type][1],
						($offs ? "&offset={$offs}" : '')
					);
		$resp = Curl::exec($url, [CURLOPT_HTTPHEADER => $hh]);
		$res = json_decode($resp, true);
		return $res;
	}

	
	static function getAll($type)
	{
		global $ntaIdx;
		$res = [];
		$offs = null;
		while (True)
		{
			$resp = self::getPage($type, $offs);
			foreach (self::mapResponce($resp['records'], $type) as $r)
				$res[$r['id']] = $r;
			$offs = $resp['offset'];
			if (!$offs)
			{	
				if ($type == 'nta')
					$ntaIdx = $res;
				return $res;
			}
		}
	}


	static function parseUrl($s)
	{
		preg_match_all('~(https?://[-_#$&?/\.\w]+)~si', $s, $rr);
		return $rr[0][0];
	}
	

	static function mapResponce($raw, $type)
	{
		$m = "{$type}Mapper";
		return self::$m($raw);
	}
	
	
	static function submissionsMapper($raw)
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
	
	
	static function covid_prjMapper($raw)
	{
		$res = [];
		foreach ($raw as $r)
			if ($r['fields']['Publish'] && $r['fields']['Name'])
				if ($r['fields']['Neighborhood'])
					foreach((array)$r['fields']['Neighborhood'] as $ntaId)
						$res[] = [
							'id' => $r['id'],
							'name' => $r['fields']['Name'],
							'type' => implode(',', $r['fields']['Type']),
							'tags' => $r['fields']['Tags'],
							'description' => $r['fields']['Description'],
							'email_pub' => $r['fields']['Public Email'],
							'email_private' => $r['fields']['Private Email'],
							'url' => $r['fields']['URL'],
							'images' => self::attachmentsMapper($r['fields']['Cover Image']),
							'nta' => self::ntaBoundMapper($ntaId, 'code'),
							'ntas' => self::ntaBoundMapper($r['fields']['Neighborhood'], 'names'),
							'comment' => $r['fields']['Comment'],
							'sites' => $r['fields']['Sites'],
						];
				elseif (preg_match('~National|City-Wide~si', $r['fields']['Location']))
				$res[] = [
							'id' => $r['id'],
							'name' => $r['fields']['Name'],
							'type' => implode(',', $r['fields']['Type']),
							'tags' => $r['fields']['Tags'],
							'description' => $r['fields']['Description'],
							'email_pub' => $r['fields']['Public Email'],
							'email_private' => $r['fields']['Private Email'],
							'url' => $r['fields']['URL'],
							'images' => self::attachmentsMapper($r['fields']['Cover Image']),
							'nta' => '',
							'ntas' => $r['fields']['Location'],
							'comment' => $r['fields']['Comment'],
							'sites' => $r['fields']['Sites'],
						];
		return $res;
	}
	
	
	static function attachmentsMapper($ii)
	{
		$r = [];
		foreach ((array)$ii as $i)
			$r[] = ['url' => $i['url'], 'icon' => $i['thumbnails']['small']['url'], 'bigIcon' => $i['thumbnails']['large']['url']];
		return json_encode($r);
	}
	
	
	static function ntaBoundMapper($nn, $option)
	{
		global $ntaIdx;
		$ntaIdx = $ntaIdx ?? self::getAll('nta');
		if ($option == 'code')
			return $ntaIdx[$nn]['code'];
		
		$r = [];
		foreach ((array)$nn as $n)
			$r[] = $ntaIdx[$n]['name'];
		return implode(', ', $r);
	}
	
	
	static function covid_podsMapper($raw)
	{
		$res = [];
		foreach ($raw as $r)
			if ($r['fields']['Name'] && $r['fields']['Public'])
				foreach((array)$r['fields']['Neighborhoods'] as $ntaId)
					$res[] = [
						'id' => $r['id'],
						'name' => $r['fields']['Name'],
						'description' => $r['fields']['Description'],
						'email' => $r['fields']['Email'],
						'link' => $r['fields']['Link'],
						'nta' => self::ntaBoundMapper($ntaId, 'code'),
						'ntas' => self::ntaBoundMapper($r['fields']['Neighborhoods'], 'names'),
						'mapped' => $r['fields']['Mapped'],
						'merged' => $r['fields']['Merged'],
						'address' => $r['fields']['Address'],
						'attachments' => self::attachmentsMapper($r['fields']['Attachments']),
						'contacts' => $r['fields']['Contacts'],
						'phone_private' => $r['fields']['Private Phone'],
						'email_private' => $r['fields']['Private Email'],
						'change_request' => $r['fields']['Change Request'],
					];
		return $res;
	}


	static function ntaMapper($raw)
	{
		global $ntaIdx;
		$res = [];
		foreach ($raw as $r)
			$res[$r['id']] = [
				'id' => $r['id'],
				'name' => $r['fields']['Neighborhood Name'],
				'shortname' => $r['fields']['NTAName'],
				'code' => $r['fields']['NTACode'],
			];
		return $res;
	}
}