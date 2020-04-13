<?php 
include '../../data_include/autoload.php';
$request = mapIncomingRequest($_GET);
$model = new Model();

$data = $request ? $model->getCards($request) : [];

header('content-type: application/json');
echo json_encode($data);



function mapIncomingRequest($rr)
{
	if (!$rr['trg'] || !($rr['id'] || $rr['plate'] || $rr['dates'] || $rr['pid'] || ($rr['btype'] && $rr['boundary'])))
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