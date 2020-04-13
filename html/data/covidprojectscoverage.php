<?php 
include '../../data_include/autoload.php';
session_start();
$model = new AirtableCovidModel();
if (!$_SESSION['ntas'])
	$_SESSION['ntas'] = $model->getNtaList();

if (!$_SESSION['coverage'] || $_SESSION['coverage_ts'] <= time())
	$_SESSION['coverage'] = $model->getCoverage();
	$_SESSION['coverage_ts'] = time() + 600;

//print_r($_SESSION);
header('content-type: application/json');
echo json_encode(ntas());



function ntas()
{
	$rr = [];
	foreach ($_SESSION['ntas'] as $nta)
		if (array_search($nta['id'], $_SESSION['coverage']))
			$rr[] = ['nta' => $nta['code']];
	return $rr;
}
