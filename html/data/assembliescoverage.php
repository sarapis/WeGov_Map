<?php 
include '../../data_include/autoload.php';
session_start();
$model = new AirtableAssembliesModel();
if (!$_SESSION['assemblies_ntas'] || (!$_SESSION['assemblies_ntas_ts'] < time()))
{
	$_SESSION['assemblies_ntas'] = $model->getNtaList();
	$_SESSION['assemblies_ntas_ts'] = time() + 300;
}

//print_r($_SESSION);
header('content-type: application/json');
echo json_encode($_SESSION['assemblies_ntas']);

