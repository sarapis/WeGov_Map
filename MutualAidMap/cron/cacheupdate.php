<?php 
include '../includes/init.php';

$model = new Model();

// submissions
/*
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
*/

// covid_prj
$aCards = AirtableCovidModel::readTable(COVID_SHEET_GR, COVID_VIEW_GR);
$ntas = AirtableCovidModel::getNtaList(); // returns [code => [id, code, name...]]
$ntaMap = [];
foreach ($ntas as $n) $ntaMap[$n['id']] = $n['name'];

#print_r($aCards);
$newDS = [];
foreach ($aCards as $id=>$aCard)
{
	// Map keys
	$f = $aCard['fields'];
	$rec = [
		'id' => $aCard['id'],
		'name' => $f['name'] ?? null,
		'type' => $f['Group Structure'] ?? null,
		'tags' => $f['Issue Focus'] ?? null,
		'description' => $f['description'] ?? null,
		'email_pub' => $f['email'] ?? null,
		'email_private' => null, // Not in public airtable usually?
		'url' => $f['website'] ?? null,
		'images' => $f['Cover Image'] ?? null,
		'nta' => array_map(function($nid) use ($ntaMap) { return $ntaMap[$nid] ?? $nid; }, (array)($f['Neighborhoods'] ?? [])),
		'geo_scope' => $f['Geographical Scope'] ?? null,
		'facebook' => $f['Facebook'] ?? null,
		'instagram' => $f['Instagram'] ?? null,
		'twitter' => $f['Twitter'] ?? null,
		'comment' => null,
		'sites' => null
	];
	
	// Encode arrays
	// Encode arrays
	foreach ($rec as $k=>$v) {
		if ($k == 'images' && is_array($v)) {
			foreach ($v as $idx => $img) {
				// Process Large/Full Image
				$remoteUrl = $img['thumbnails']['large']['url'] ?? $img['url'];
				$localName = "{$rec['id']}_{$idx}.jpg";
				$localPathFull = "images/covid_groups/{$localName}"; // File system path
				$webPath = "images/covid_groups/{$localName}"; // Web path relative to root
				
				if (downloadImage($remoteUrl, __DIR__ . "/../{$localPathFull}")) {
					$v[$idx]['thumbnails']['large']['url'] = $webPath;
					// Update full URL too, usually not used but good for consistency
					$v[$idx]['url'] = $webPath; 
				}

				// Process Small Thumbnail
				$remoteUrlSmall = $img['thumbnails']['small']['url'] ?? $remoteUrl;
				$localNameSmall = "{$rec['id']}_{$idx}_small.jpg";
				$localPathFullSmall = "images/covid_groups/{$localNameSmall}";
				$webPathSmall = "images/covid_groups/{$localNameSmall}";

				if (downloadImage($remoteUrlSmall, __DIR__ . "/../{$localPathFullSmall}")) {
					$v[$idx]['thumbnails']['small']['url'] = $webPathSmall;
				}
			}
			$rec[$k] = json_encode($v);
		} elseif (is_array($v)) {
			$rec[$k] = json_encode($v);
		}
	}
	
	$newDS[] = normRec($rec, 'covid_prj');
}

// Truncate table to remove old/deleted records
if (count($newDS) > 0) {
    $model->db->qi("TRUNCATE TABLE covid_prj");
    $model->db->replace('covid_prj', $newDS);
    echo "updated covid_prj: " . count($newDS) . "\n";
} else {
    echo "No records found to update (skipping truncate).\n";
}

flush();


// Generate static JSON cache files for fast page load
// (avoids live Airtable API calls on every web request)

// 1. NTA list cache — used by projects.php to resolve NTA codes to names
$ntaListCache = json_encode($ntas, JSON_UNESCAPED_UNICODE);
file_put_contents(__DIR__ . '/../data/nta_list_cache.json', $ntaListCache);
echo "cached nta_list_cache.json: " . count($ntas) . " entries\n";

// 2. Coverage cache — used by coverage.php to highlight covered neighborhoods
$coverageNtas = [];
foreach ($ntas as $nta) {
    if (($nta['covered'] ?? null) !== null) {
        $coverageNtas[] = ['nta' => $nta['code']];
    }
}
// Also derive coverage from actual DB records (more accurate)
$dbCoverage = $model->db->q("SELECT DISTINCT nta FROM covid_prj WHERE nta IS NOT NULL AND nta != '' AND nta != '[]'");
$coveredCodes = [];
foreach ($dbCoverage as $row) {
    $decoded = json_decode($row['nta'], true);
    if (is_array($decoded)) {
        foreach ($decoded as $name) {
            // Reverse-map name back to code
            foreach ($ntas as $code => $n) {
                if ($n['name'] === $name) {
                    $coveredCodes[$code] = true;
                }
            }
        }
    }
}
$coverageNtas = [];
foreach ($coveredCodes as $code => $_) {
    $coverageNtas[] = ['nta' => $code];
}
file_put_contents(__DIR__ . '/../data/coverage_cache.json', json_encode($coverageNtas, JSON_UNESCAPED_UNICODE));
echo "cached coverage_cache.json: " . count($coverageNtas) . " entries\n";


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


function normRec($r, $type)
{
	$fff = [
			'submissions' => ['id', 'date', 'plateNum', 'plateState', 'capID', 'type', 'lat', 'lng', 'address', 'note', 'message', 'img1', 'img2', 'img3', 'img4', 'tweetLink', 'commentLink', 'cd', 'pp', 'dsny', 'fb', 'sd', 'hc', 'cc', 'nycongress', 'sa', 'ss', 'nta', 'zipcode'],
			'covid_prj' => ['id', 'name', 'type', 'tags', 'description', 'email_pub', 'email_private', 'url', 'images', 'nta', 'geo_scope', 'comment', 'sites', 'facebook', 'instagram', 'twitter'],
			'covid_pods' => ['id', 'name', 'description', 'email', 'link', 'nta', 'mapped', 'merged', 'address', 'attachments', 'contacts', 'phone_private', 'email_private', 'change_request'],
		];
	$allowed = array_fill_keys($fff[$type], null);
	return array_intersect_key($r + $allowed, $allowed);
}

function downloadImage($url, $path)
{
	if (!$url) return false;
	
	// If file exists and is not empty, skip download (assuming immutable IDs/content for now)
	if (file_exists($path) && filesize($path) > 0) return true;

	$c = curl_init($url);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
	$data = curl_exec($c);
	$code = curl_getinfo($c, CURLINFO_HTTP_CODE);
	curl_close($c);

	if ($code == 200 && $data) {
		file_put_contents($path, $data);
		return true;
	}
	return false;
}
