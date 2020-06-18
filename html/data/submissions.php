<?php 
include '../../data_include/autoload.php';
$request = mapIncomingRequest($_GET);
$model = new Model();

$data = $request ? $model->getCards($request) : [];

header('content-type: application/json');
echo json_encode(['geojson' => $request ? $model->mapGeoJson($data) : [], 'container' => calcContainer($data)]);



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