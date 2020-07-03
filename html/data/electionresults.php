<?php 
include '../../data_include/autoload.php';
$request = mapIncomingRequest($_GET);
$model = new Model();

if (!$request)
	$data = [];
elseif ($request['trg'] == 'csv')
{
	$data = $model->erRanksCSV($request['event'], $request['division']);
	//$disp = ['er' => 'Votes', 'su' => 'Top Districts'][$request['display']];
	$div = ['ad' => 'AD', 'aded' => 'ED', 'county' => 'County'][$request['division']];
	$fn = preg_replace(['~\|$~si', '~\|~si'], ['', ' # '], $request['event']) . " # {$div}.csv";
	header('Content-Type: application/csv');
	header('Content-Disposition: attachment; filename="' . $fn . '";');
	echo Csv::encodeCSV($data, ",", '"');
	die();
}
elseif ($request['trg'] == 'select')
	$data = ['events' => $model->erEvents()];
elseif ($request['trg'] == 'expr')
	$data = $request['display'] == 'er'
			? mapResults( $model->erResults($request['event'], $request['division']), 
						  $request['division'], 
						  $request['design']
					    )
			: mapRanks(   $model->erRanks($request['event'], $request['division']), 
						  $request['division'], 
						  $request['design']
					    );

header('content-type: application/json');
echo json_encode($data);




function mapIncomingRequest($rr)
{
	if (!$rr['trg'] || !(($rr['trg'] == 'select') || ($rr['division'] && $rr['display'] && $rr['event'])))
		return null;
	
	array_walk_recursive($rr, function ($el) {
		return addslashes($el);
	});
	
	return $rr;
}


function mapResults($raw, $division, $design)
{
	$cc = [
		'Democratic' => '0,0,153',
		'Republican' => '204,51,0',
		'Green' => '0,102,0',
		'Libertarian' => '255,255,0',
						'230,138,0','128,0,0','102,0,102','102,102,153','51,102,153','0,102,102','102,102,51','51,102,0'
	];
	$legend = [];
	$ee = ['match', ['get', 'nameCol']];
	$ll = [];

	foreach ($raw as $r)
	{
		$pp = preg_replace('~^.*\(|\).*$~si', '', $r['participant']);
		$color = $legend[$r['participant']] ?? $cc[$pp] ?? array_pop($cc);
		$legend[$r['participant']] = $color;
		if (isset($r['tally']) && !isset($ll[$r['loc']]))
		{
			$ee[] = $r['loc'];
			if (!$r['tally'])
				$ee[] = 'rgba(0,0,0,0)';
			else
				switch ($design)
				{
					case 'fill-solid':
					case 'names-solid':
						$ee[] = "rgba({$color},0.9)";
						break;
					case 'fill-shading':
					case 'names-shading':
						$ee[] = sprintf("rgba(%s,%.2f)", $color, round((float)$r['perc']/100 * 0.7 + 0.1, 2));
						break;
				}
			$ll[$r['loc']] = true;
		}
	}
	$ee[] = 'rgba(0,0,0,0)';
	return ['geojson' => mapGeoJson($raw, $division, $design), 'expr' => $ee, 'legend' => $legend];
}


function mapRanks($raw, $division, $design)
{
	$cc = [
		'Democratic' => '0,0,153',
		'Republican' => '204,51,0',
		'Green' => '0,102,0',
		'Libertarian' => '255,255,0',
						'230,138,0','128,0,0','102,0,102','102,102,153','51,102,153','0,102,102','102,102,51','51,102,0'
	];
	$legend = [];
	$ee = ['match', ['get', 'nameCol']];
	$ll = [];
	$perc = [];

	foreach ($raw as $r)
	{
		$pp = preg_replace('~^.*\(|\).*$~si', '', $r['participant']);
		$color = $legend[$r['participant']] ?? $cc[$pp] ?? array_pop($cc);
		$legend[$r['participant']] = $color;
		
		if (isset($r['tally']) && !isset($ll[$r['loc']]))
		{
			$ll[$r['loc']] = $r;
			$perc[$r['participant']]['min'] = min((float)$perc[$r['participant']]['min'], $r['perc']);
			$perc[$r['participant']]['max'] = max((float)$perc[$r['participant']]['max'], $r['perc']);			
		}
	}

	foreach ($ll as $r)
	{
		$ee[] = $r['loc'];
		switch ($design)
		{
			case 'fill-solid':
			case 'names-solid':
				$ee[] = "rgba({$color},0.9)";
				break;
			case 'fill-shading':
			case 'names-shading':
				$ee[] = sprintf("rgba(%s,%.2f)", 
								$legend[$r['participant']], 
								round((float)(($r['perc'] - $perc[$r['participant']]['min']) / ($perc[$r['participant']]['max'] - $perc[$r['participant']]['min'])) * 0.7 + 0.1, 2)
							   );
				break;
		}
	}
	$ee[] = 'rgba(0,0,0,0)';
	return ['geojson' => mapGeoJson($raw, $division, $design, $ll), 'expr' => $ee];
}


function mapGeoJson($raw, $division, $design, $ll=[])
{
	$f = [
		'ad' => './sa.geojson', 
		'aded' => './ed.geojson', 
		'county' => './bb.geojson', 
	][$division];
	$jj = json_decode(file_get_contents($f), true);
	
	$pp = $mm = [];
	usort($raw, function ($a, $b) { return $b['perc'] <=> $a['perc']; });
	
	foreach ($raw as $r)
		if (isset($r['tally']))
			$pp[$r['loc']][] = ['name' => $r['participant'], 'perc' => $r['perc'], 'tally' => $r['tally']];
		elseif (isset($r['msg']))
			$mm[$r['loc']] = $r['msg'];
	
	foreach ($jj['features'] as $i=>$r)
		if (isset($pp[$r['properties']['nameCol']]))
		{
			$rr = $pp[$r['properties']['nameCol']];
			$jj['features'][$i]['properties']['results'] = $rr;
			$jj['features'][$i]['properties']['winner'] = $rr[0]['name'];
			$jj['features'][$i]['properties']['winnerEnh'] = 
				$ll
				? sprintf('%s - %.1f%%', $ll[$r['properties']['nameCol']]['participant'], $ll[$r['properties']['nameCol']]['perc'])
				: sprintf('%s - %.1f%%', $rr[0]['name'], $rr[0]['perc']);
		}
		elseif (isset($mm[$r['properties']['nameCol']]))
			$jj['features'][$i]['properties']['msg'] = $mm[$r['properties']['nameCol']];
	//$res = json_encode($jj);
	return $jj;
}