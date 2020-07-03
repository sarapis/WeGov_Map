<?php 

class Model
{
	var $db;
	function __construct()
	{
		$this->db = new db(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	}
	
	function getAll($type)
	{
		$t = ['submissions' => 'dd', 'covid_prj' => 'covid_prj', 'covid_pods' => 'covid_pods', ][$type];
		return $this->q("SELECT * FROM {$t}");
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

	
///// submissions ////////////////////////////////////////////////
	
	function getCards($pp)
	{
		return $pp['trg'] == 'cb'
				? [$this->boundaryRequest($pp)]
				: $this->apiRequest($pp);
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
	

	function mapCentroid($raw)
	{
		
	}

	function boundaryRequest($pp)
	{
		return $pp['address']
			? Geoclient::match($pp['address']) + ['type' => 'Boundaries', 'addr' => $pp['address']]
			: [];
	}
		
		
///// covid ////////////////////////////////////////////////

	function getCovidPrj($nta)
	{
		$ntas = implode("','", (array)$nta);
		$rr = $this->db->q("SELECT *, 'project' as object_type FROM covid_prj WHERE nta IN ('{$ntas}') OR nta LIKE '' ORDER BY name");
		return $this->mapCovidPrj($rr);
	}
	
	
	function mapCovidPrj($rr)
	{
		return $rr;
	}
	
	
	function getCovidPods($nta)
	{
		$ntas = implode("','", (array)$nta);
		$rr = $this->db->q("SELECT *, 'pod' as object_type FROM covid_pods WHERE nta IN ('{$ntas}') ORDER BY name");
		return $this->mapCovidPods($rr);
	}
	
	
	function mapCovidPods($rr)
	{
		return $rr;
	}
	
	
	function getCovidAll($req)
	{
		$nta = $req['nta'];
		$pp = array_merge($this->getCovidPods($nta), $this->getCovidPrj($nta));
		$rr = [];
		foreach ($pp as $p)
		{
			$tab = ['National' => 'National', 'City-Wide' => 'City-Wide'][$p['ntas']] ?? 'Neighborhood';
			$rr[$tab][] = $p;
		}
		$rr = array_merge(array_fill_keys(['Neighborhood', 'City-Wide', 'National'], []), $rr);
		return array_filter($rr);
	}
	
	
	function getCovidCoverage()
	{
		return $this->db->q("SELECT nta FROM covid_prj  WHERE nta NOT LIKE ''
							UNION 
							SELECT nta FROM covid_pods WHERE nta NOT LIKE ''");
	}
	
///// elections results ////////////////////////////////////////////////

	function erEvents()
	{
		$rr = [];
		foreach ($this->db->q('SELECT event,position,party,district FROM el_events_list ORDER BY id DESC') as $r)
			$rr[implode('|', [$r['event'], $r['position'], $r['party']])] = implode(' / ', array_filter([$r['event'], $r['position'], $r['party']])) . " ({$r['district']})";
		return $rr;
	}

	function erResults($ev, $division)
	{
		list($event, $position, $party) = explode('|', $ev);
		$q = [
			'ad' => 'ad as loc, MAX(perc_ad) as perc',
			'aded' => 'aded as loc, MAX(perc_aded) as perc',
			'county' => 'county as loc, MAX(perc_county) as perc',
		][$division];
		return array_merge(
			$this->db->q("SELECT participant,SUM(tally) as tally,{$q} FROM el_results WHERE event LIKE '{$event}' AND position LIKE '{$position}' AND party LIKE '{$party}' GROUP BY participant, {$division} ORDER BY perc DESC"),
			$this->db->q("SELECT DISTINCT msg,{$division} as loc FROM el_resultsm WHERE event LIKE '{$event}' AND position LIKE '{$position}' AND party LIKE '{$party}'")
		);
	}

	function erResultsCSV($ev, $division)
	{
		list($event, $position, $party) = explode('|', $ev);
		$q = [
			'ad' => 'ad, MAX(perc_ad) as percentage',
			'aded' => 'aded, MAX(perc_aded) as percentage',
			'county' => 'county, MAX(perc_county) as percentage',
		][$division];
		return $this->db->q("SELECT participant,SUM(tally) as tally,{$q} FROM el_results WHERE event LIKE '{$event}' AND position LIKE '{$position}' AND party LIKE '{$party}' GROUP BY participant, {$division} ORDER BY {$division}, percentage DESC");
	}
	
	function erRanks($ev, $division)
	{
		list($event, $position, $party) = explode('|', $ev);
		return array_merge(
			$this->db->q("SELECT 
				participant,
				SUM(tally) as tally,
				{$division} as loc,
				MAX(perc_{$division}) as perc,
				ROW_NUMBER() OVER (
				  PARTITION BY participant
				  ORDER BY perc DESC) rank
			FROM el_results
			WHERE event LIKE '{$event}' AND position LIKE '{$position}' AND party LIKE '{$party}'
			GROUP BY participant, {$division}
			ORDER BY rank, perc DESC"),
			$this->db->q("SELECT DISTINCT msg,{$division} as loc FROM el_resultsm WHERE event LIKE '{$event}' AND position LIKE '{$position}' AND party LIKE '{$party}'")
		);
	}

	function erRanksCSV($ev, $division)
	{
		list($event, $position, $party) = explode('|', $ev);
		return 
			$this->db->q("SELECT 
				participant,
				SUM(tally) as tally,
				{$division},
				MAX(perc_{$division}) as percentage,
				ROW_NUMBER() OVER (
				  PARTITION BY participant
				  ORDER BY percentage DESC) district_rank
			FROM el_results
			WHERE event LIKE '{$event}' AND position LIKE '{$position}' AND party LIKE '{$party}'
			GROUP BY participant, {$division}
			ORDER BY district_rank, percentage DESC");
	}
}