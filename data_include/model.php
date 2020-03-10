<?php 

class Model
{
	var $db;
	function __construct()
	{
		$this->db = new db(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	}
	
	function getAll()
	{
		return $this->q('SELECT * FROM dd');
	}
	
	function mapAssoc($raw, $f='id')
	{
		$res = [];
		foreach ($raw as $r)
			$res[$r[$f]] = $r;
		return $res;
	}
	
	function q($q)
	{
		//echo $q . "\n";
		return $this->mapAssoc($this->db->q($q));
	}

	
///// requests ////////////////////////////////////////////////
	
	function getCards($pp)
	{
		return $this->mapResults($this->apiRequest($pp));
	}
	
	function getCentroid($pp)
	{
		return $this->mapCentroid($this->apiRequest($pp));
	}
	
	function apiRequest($pp)
	{
		$aggrField = ['pa' => 'plateNum', 'cp' => 'capID'][$pp['trg']];
		$sql = "SELECT d.*, p.reportsNum
					FROM dd d
				LEFT JOIN 
					(SELECT {$aggrField} as pn, count(*) as reportsNum FROM dd GROUP BY {$aggrField}) p
					ON d.{$aggrField} = p.pn";
		$type = ['pa' => 'Placard Abuse', 'cp' => 'Capital Project'][$pp['trg']];
		if ($pp['id'])
			$rr = $this->q("{$sql} WHERE type LIKE '{$type}' AND id LIKE '{$pp['id']}'");
		elseif ($pp['plate'])
			$rr = $this->q("{$sql} WHERE type LIKE '{$type}' AND plateNum LIKE '%{$pp['plate']}%'");
		elseif ($pp['pid'])
			$rr = $this->q("{$sql} WHERE type LIKE '{$type}' AND capID LIKE '%{$pp['pid']}%'");
		elseif ($pp['start'])
			$rr = $this->q("{$sql} WHERE type LIKE '{$type}' AND date >= '{$pp['start']}' AND date < '{$pp['end']}' + INTERVAL 1 DAY");
		elseif ($pp['btype'] == 'nta')
			$rr = $this->q("{$sql} WHERE type LIKE '{$type}' AND nta LIKE '{$pp['boundary']}'");
		elseif ($pp['btype'] && $pp['btype'] <> 'nta')
			$rr = $this->q("{$sql} WHERE type LIKE '{$type}' AND {$pp['btype']} = {$pp['boundary']}");
		return $rr;
	}
	
	function mapResults($raw)
	{
		$rr = ['type' => 'FeatureCollection', 'features' => []];
		foreach ($raw as $r)
		{
			$g = [
					'type' =>  'Point',
					'coordinates' =>  [$r['lng'], $r['lat']]
				];
			$ii = array_filter([$r['img1'], $r['img2'], $r['img3'], $r['img4']], 'strlen');
			$r = [
				'coordinates' => "{$r['lng']}, {$r['lat']}",
				'images' => ['main' => (array)array_chunk($ii, 2)[0], 'additional' => (array)array_chunk($ii, 2)[1]],
				'permalink' => preg_replace( '~data/submissions.php.*~si', 
											($r['type'] == 'Placard Abuse' ? 'placabuse.php' : 'capprojects.php'), 
											"{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"
										) . "?id={$r['id']}"
			] + $r;
			$r = array_diff_key($r, ['lat' => null, 'lng' => null, 'img1' => null, 'img2' => null, 'img3' => null, 'img4' => null]);
			$rr['features'][] = [
				'type' =>  'Feature',
				'properties' => $r,
				'geometry' => $g
			];
		}
		return $rr;
	}
	
	function mapCentroid($raw)
	{
		
	}
}