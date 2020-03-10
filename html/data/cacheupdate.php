<pre><?php 
include '../../data_include/autoload.php';

$model = new Model();
$aCards = Airtable::getAll();
$dCards = $model->getAll();
$newDS = [];
foreach ($aCards as $id=>$aCard)
{
	if ($dCards[$id]['zipcode'] && $dCards[$id]['lat'])
		$fullCard = mergeRec($dCards[$id], $aCard);
	elseif ($aCard['address'])
		$fullCard = mergeRec($aCard, Geoclient::match($aCard['address']));
	else
		$fullCard = $aCard;
	$newDS[] = normRec($fullCard);
}
$model->db->replace('dd', $newDS);
echo "updated records: " . count($newDS);



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

function normRec($r)
{
	$ff = ['id', 'date', 'plateNum', 'plateState', 'capID', 'type', 'lat', 'lng', 'address', 'note', 'message', 'img1', 'img2', 'img3', 'img4', 'tweetLink', 'commentLink', 'cd', 'pp', 'dsny', 'fb', 'sd', 'hc', 'cc', 'nycongress', 'sa', 'ss', 'nta', 'zipcode'];
	return $r + array_fill_keys($ff, null);
}