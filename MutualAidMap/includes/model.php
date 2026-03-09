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

	
///// covid ////////////////////////////////////////////////

	function getCovidPrj($nta)
	{
		$ntas = (array)$nta;
		$whereParts = [];
		foreach ($ntas as $n) {
			$whereParts[] = "nta LIKE '%\"{$n}\"%'";
		}
		$whereSql = implode(' OR ', $whereParts);
		
		$rr = $this->db->q("SELECT *, 'project' as object_type FROM covid_prj WHERE ({$whereSql}) OR nta LIKE '' OR nta IS NULL ORDER BY name");
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
	
}