<?php 
include '../../data_include/autoload.php';
session_start();
$model = new AirtableAssembliesModel();
if (!$_SESSION['ntas'])
	$_SESSION['ntas'] = $model->getNtaList();

//print_r($_SESSION);
header('content-type: application/json');
echo json_encode($_SESSION['ntas']);

