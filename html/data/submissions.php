<?php 
include '../../data_include/autoload.php';
$request = mapIncomingRequest($_GET);
$model = new Model();

$data = $request ? $model->getCards($request) : [];

header('content-type: application/json');
echo json_encode(['geojson' => $request ? mapGeoJson($data) : [], 'container' => calcContainer($data)]);



function mapIncomingRequest($rr)
{
	if (!$rr['trg'] || !($rr['id'] || $rr['plate'] || $rr['dates'] || $rr['pid'] || $rr['address'] || ($rr['btype'] && $rr['boundary'])))
		return null;
	
	if ($rr['dates'])
	{
		list($b, $e) = explode(' - ', $rr['dates']);
		$rr['start'] = date('Y-m-d', strtotime($b));
		$rr['end'] = date('Y-m-d', strtotime($e));
		unset($rr['dates']);
		//print_r($rr);
	}
	array_walk_recursive($rr, function ($el) {
		return addslashes($el);
	});
	
	return $rr;
}


function calcContainer($dd)
{
	if (!$dd)
	{	
		$t = '
		{
			"center": [
				-73.957, 40.727
			],
			"zoom": 11
		}';
		return json_decode($t, true);
	}	
	$lat1 = $lng1 = 999; 
	$lat2 = $lng2 = -999;
	foreach ($dd as $d)
	{
		$lat1 = min($lat1, $d['lat']);
		$lng1 = min($lng1, $d['lng']);
		$lat2 = max($lat2, $d['lat']);
		$lng2 = max($lng2, $d['lng']);
		
	}
	$dLat = $lat2 - $lat1;
	$dLng = $lng2 - $lng1;
	$aLat = ($lat2 + $lat1) / 2;
	$aLng = ($lng2 + $lng1) / 2;
	return ['center' => [$aLng, $aLat], 'zoom' => mapScale($dLat, $dLng)];
}

function mapScale($dLat, $dLon)
{
	if ($dLat === 0.0 && $dLon === 0.0)
		return 15;
	foreach (
		[
			4 => 11,
			5 => 6,
			6 => 3,
			7 => 1.5,
			8 => 0.75,
			9 => 0.5,
			10 => 0.22,
			11 => 0.14,
			12 => 0,
		] as $v=>$lim)
		if ($dLat > $lim)
		{
			$sLat = $v;
			break;
		}	
	foreach (
		[
			8 => 2.5,
			9 => 0.72,
			10 => 0.5,
			11 => 0.27,
			12 => 0.15,
			13 => 0.085,
			14 => 0.05,
			15 => 0,
		] as $v=>$lim)
		if ($dLon > $lim)
		{
			$sLon = $v;
			break;
		}
	//print_r(compact(['dLat', 'dLon', 'sLat', 'sLon']));
	return min($sLat, $sLon) - 1;
}

function mapGeoJson($raw)
{
	$rr = ['type' => 'FeatureCollection', 'features' => []];
	foreach ($raw as $r)
	{
		$g = [
				'type' =>  'Point',
				'coordinates' =>  [$r['lng'], $r['lat']]
			];
		$ii = array_filter([$r['img1'], $r['img2'], $r['img3'], $r['img4']], 'strlen');
		$ll = [
						'Placard Abuse' => ['path' => 'placabuse.php', 'req' => "?id={$r['id']}"],
						'Capital Project' => ['path' => 'capprojects.php', 'req' => "?id={$r['id']}"],
						'Boundaries' => ['path' => 'cityboundaries.php', 'req' => '?addr=' . urlencode($r['addr'])],
					];
		$url = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$r = [
			'coordinates' => "{$r['lng']}, {$r['lat']}",
			'images' => ['main' => (array)array_chunk($ii, 2)[0], 'additional' => (array)array_chunk($ii, 2)[1]],
			'permalink' => preg_replace('~data/submissions.php.*~si', $ll[$r['type']]['path'], $url) . $ll[$r['type']]['req']
		] + $r;
		$r = array_diff_key($r, ['lat' => null, 'lng' => null, 'img1' => null, 'img2' => null, 'img3' => null, 'img4' => null]);
		$rr['features'][] = [
			'type' =>  'Feature',
			'properties' => $r,
			'geometry' => $g
		];
	}
	//print_r($rr);
	return $rr;
}
