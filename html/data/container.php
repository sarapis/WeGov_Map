<?php 
header('content-type: application/json');

$t = <<<EOD

{
	"center": [
		-73.9576740104957,
		40.7274924718489
	],
	"zoom": 11
}
EOD;

echo json_encode(json_decode($t, true));