<?php 
class AirtableAssembliesModel
{
	/*
	static function getOrg($id, $email)
	{
		$hh = ["Authorization: Bearer " . AIRTABLE_KEY];
		$url = sprintf(
						'https://api.airtable.com/v0/%s/%s/%s', 
						rawurlencode(COVID_DOC),
						rawurlencode(COVID_SHEET_GR),
						rawurlencode($id)
					);
		$resp = Curl::exec($url, [CURLOPT_HTTPHEADER => $hh]);
		$rr = json_decode($resp, true);
		return $rr['id'] ? $rr : self::newOrg(['Email' => $email, 'Organization Type' => ['Vendor']])['records'][0];
	}

	
	static function findUserByEmail($email)
	{
		$hh = ["Authorization: Bearer " . AIRTABLE_KEY];
		$url = sprintf(
						'https://api.airtable.com/v0/%s/%s?filterByFormula=%s', 
						rawurlencode(COVID_DOC),
						rawurlencode(COVID_SHEET_USR),
						rawurlencode("AND({Email}='{$email}', {Role}='admin')")
					);
		$resp = Curl::exec($url, [CURLOPT_HTTPHEADER => $hh]);
		$rr = json_decode($resp, true);
		return $rr['records'] ? $rr : self::newUser(['Email' => $email, 'Role' => 'admin']);
	}


	static function findOrgsByNta($ntaName)
	{
		$hh = ["Authorization: Bearer " . AIRTABLE_KEY];
		$url = sprintf(
						'https://api.airtable.com/v0/%s/%s?view=%s&filterByFormula=%s', 
						rawurlencode(COVID_DOC),
						rawurlencode(COVID_SHEET_GR),
						rawurlencode(COVID_VIEW_GR),
						rawurlencode("FIND('{$ntaName}', {Neighborhoods})")
					);
		$resp = Curl::exec($url, [CURLOPT_HTTPHEADER => $hh]);
		$rr = json_decode($resp, true);
		return $rr['records'] ? $rr['records'] : [];
	}

	
	static function newUser($fields=['Name' => ''])
	{
		$hh = ['Authorization: Bearer ' . AIRTABLE_KEY, 'Content-Type: application/json'];
		$url = sprintf(
						'https://api.airtable.com/v0/%s/%s', 
						rawurlencode(COVID_DOC),
						rawurlencode(COVID_SHEET_USR)
					);
		$resp = Curl::exec($url, [CURLOPT_HTTPHEADER => $hh], ['records' => [['fields' => $fields]]], 'json');
		$rr = json_decode($resp, true);
		if ($rr['records'])
			$rr['records'][0]['is_new'] = true;
		return $rr;
	}

	
	static function newOrg($fields=['Name' => ''])
	{
		$hh = ['Authorization: Bearer ' . AIRTABLE_KEY, 'Content-Type: application/json'];
		$url = sprintf(
						'https://api.airtable.com/v0/%s/%s', 
						rawurlencode(COVID_DOC),
						rawurlencode(COVID_SHEET_GR)
					);
		$resp = Curl::exec($url, [CURLOPT_HTTPHEADER => $hh], ['records' => [['fields' => $fields]]], 'json');
		//return json_decode($resp, true)['records'][0]['id'];
		$rr = json_decode($resp, true);
		if ($rr['records'])
			$rr['records'][0]['is_new'] = true;
		return $rr;
	}

	
	static function updateOrg($id, $data)
	{
		$hh = ['Authorization: Bearer ' . AIRTABLE_KEY, 'Content-Type: application/json'];
		$url = sprintf(
						'https://api.airtable.com/v0/%s/%s',
						rawurlencode(COVID_DOC),
						rawurlencode(COVID_SHEET_GR)
					);
		$dd = ['records' => [['id' => $id, 'fields' => $data]]];
		//print_r(json_encode($dd));
		$resp = Curl::exec($url, [CURLOPT_HTTPHEADER => $hh], $dd, 'PATCH');
		//return json_decode($resp, true)['records'][0]['id'];
		return json_decode($resp, true);
	}

	
	static function updateUser($id, $data)
	{
		$hh = ['Authorization: Bearer ' . AIRTABLE_KEY, 'Content-Type: application/json'];
		$url = sprintf(
						'https://api.airtable.com/v0/%s/%s',
						rawurlencode(COVID_DOC),
						rawurlencode(COVID_SHEET_USR)
					);
		$dd = ['records' => [['id' => $id, 'fields' => $data]]];
		//print_r($dd);
		$resp = Curl::exec($url, [CURLOPT_HTTPHEADER => $hh], $dd, 'PATCH');
		//return json_decode($resp, true)['records'][0]['id'];
		return json_decode($resp, true);
	}


	static function deleteOrg($id)
	{
		$hh = ['Authorization: Bearer ' . AIRTABLE_KEY, 'Content-Type: application/json'];
		$url = sprintf(
						'https://api.airtable.com/v0/%s/%s/%s',
						rawurlencode(COVID_DOC),
						rawurlencode(COVID_SHEET_GR),
						rawurlencode($id)
					);
		$resp = Curl::exec($url, [CURLOPT_HTTPHEADER => $hh, CURLOPT_CUSTOMREQUEST => 'DELETE']);
		return json_decode($resp, true);
	}

	*/
	static function getNtaList()
	{
		$rr = [];
		foreach (self::readTable(ASSEMBLIES_NTA_SHEET) as $n)
			$rr[$n['fields']['NTACode']] = [
					'code' => $n['fields']['NTACode'], 
					'name' => $n['fields']['NTAName'], 
					'covered' => $n['fields']['status'] == 'started',
					'url' => $n['fields']['url'],
					'id' => $n['id'], 
				];
		return $rr;
	}

	/*
	static function getCoverage()
	{
		$rr = [];
		foreach (self::readTable(COVID_SHEET_GR, COVID_VIEW_GR) as $n)
		{
			$rr = array_merge($rr, (array)$n['fields']['Neighborhoods']);
		}
		return array_unique($rr);
	}	
	*/
	
	static function readTable($table, $view=null)
	{
		$res = [];
		$offs = null;
		while (True)
		{
			$resp = self::readTablePage($table, $view, $offs);
			foreach ($resp['records'] as $r)
				$res[$r['id']] = $r;
			$offs = $resp['offset'];
			if (!$offs)
				return $res;
		}
	}
	
	static function readTablePage($table, $view=null, $offs=null)
	{
		$hh = ["Authorization: Bearer " . AIRTABLE_KEY];
		$url = sprintf(
						'https://api.airtable.com/v0/%s/%s?%s%s',
						rawurlencode(ASSEMBLIES_DOC),
						rawurlencode($table),
						($view ? 'view=' . rawurlencode($view) . '&' : ''),
						($offs ? "offset={$offs}" : '')
					);
		$resp = Curl::exec($url, [CURLOPT_HTTPHEADER => $hh]);
		//echo $resp;
		return json_decode($resp, true);
	}

}