<pre><?php 
include '../../data_include/autoload.php';

$model = new Model();

// submissions
$aCards = Airtable::getAll('submissions');
echo count($aCards) . ' ';
$dCards = $model->getAll('submissions');
$newDS = [];
foreach ($aCards as $id=>$aCard)
{
	if ($dCards[$id]['zipcode'] && $dCards[$id]['lat'])
		$fullCard = mergeRec($dCards[$id], $aCard);
	elseif ($aCard['address'])
		$fullCard = mergeRec($aCard, Geoclient::match($aCard['address']));
	else
		$fullCard = $aCard;
	$newDS[] = normRec($fullCard, 'submissions');
}
$model->db->replace('dd', $newDS);
echo "updated submissions: " . count($newDS) . "\n";
flush();

/*
$cprjRecs = Airtable::getAll('covid_prj');
echo count($cprjRecs) . ' ';
$cprj = [];
foreach ($cprjRecs as $rec)
	$cprj[] = normRec($rec, 'covid_prj');
$model->db->replace('covid_prj', $cprj);
echo "updated covid prjs: " . count($cprj) . "\n";
flush();


$cpodsRecs = Airtable::getAll('covid_pods');
echo count($cpodsRecs) . ' ';
$cpods = [];
foreach ($cpodsRecs as $rec)
	$cpods[] = normRec($rec, 'covid_pods');
$model->db->replace('covid_pods', $cpods);
echo "updated covid pods: " . count($cpods) . "\n";
flush();
*/


// if lat and lng not present in Airtable rec - trying to get them from Geoclient API
function mergeRec($aCard, $gCard)
{
	foreach (['lat', 'lng'] as $f)
	{
		if (!$aCard[$f])
			unset($aCard[$f]);
		if (!$gCard[$f])
			unset($gCard[$f]);
	}
	return array_merge($aCard, $gCard);
}

/*
function normRec($r, $type)
{
	$fff = [
			'submissions' => ['id', 'date', 'plateNum', 'plateState', 'capID', 'type', 'lat', 'lng', 'address', 'note', 'message', 'img1', 'img2', 'img3', 'img4', 'tweetLink', 'commentLink', 'cd', 'pp', 'dsny', 'fb', 'sd', 'hc', 'cc', 'nycongress', 'sa', 'ss', 'nta', 'zipcode'],
			'covid_prj' => ['id', 'name', 'type', 'tags', 'description', 'email_pub', 'email_private', 'url', 'images', 'nta', 'comment', 'sites'],
			'covid_pods' => ['id', 'name', 'description', 'email', 'link', 'nta', 'mapped', 'merged', 'address', 'attachments', 'contacts', 'phone_private', 'email_private', 'change_request'],
		];
	return $r + array_fill_keys($fff[$type], null);
}
*/