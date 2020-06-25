<?php 
include '../../data_include/autoload.php';
$request = mapIncomingRequest($_GET);
$model = new Model();

if (!$request)
	$data = [];
elseif ($request['trg'] == 'select')
	$data = ['events' => $model->erEvents()];
elseif ($request['trg'] == 'expr')
	//$data = $model->erResults($request['event'], $request['division']);
	$data = mapResults($model->erResults($request['event'], $request['division']), $request['division'], $request['display']);

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


function mapResults($raw, $division, $display)
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
		if (!isset($ll[$r['loc']]))
		{
			$ee[] = $r['loc'];
			if (!$r['tally'])
				$ee[] = 'rgba(0,0,0,0)';
			else
				switch ($display)
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
	return ['geojson' => mapGeoJson($raw, $division, $display), 'expr' => $ee, 'legend' => $legend];
}


function mapGeoJson($raw, $division, $display)
{
	$f = [
		'ad' => './sa.geojson', 
		'aded' => './ed.geojson', 
		'county' => './bb.geojson', 
	][$division];
	$jj = json_decode(file_get_contents($f), true);
	
	$pp = [];
	foreach ($raw as $r)
		$pp[$r['loc']][] = ['name' => $r['participant'], 'perc' => $r['perc'], 'tally' => $r['tally']];
	
	foreach ($jj['features'] as $i=>$r)
		if (isset($pp[$r['properties']['nameCol']]))
		{
			$jj['features'][$i]['properties']['results'] = $pp[$r['properties']['nameCol']];
			$jj['features'][$i]['properties']['winner'] = $pp[$r['properties']['nameCol']][0]['name'];
			$jj['features'][$i]['properties']['winnerEnh'] = sprintf('%s - %.1f%%', $pp[$r['properties']['nameCol']][0]['name'], $pp[$r['properties']['nameCol']][0]['perc']);
		}
	
	//$res = json_encode($jj);
	return $jj;
}