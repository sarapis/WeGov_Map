<pre><?php 
include '../data_include/autoload.php';

$ff = [
		'https://vote.nyc/sites/default/files/pdf/election_results/2019/20191105General%20Election/00001200000Citywide%20Public%20Advocate%20Citywide%20EDLevel.csv',
		'https://vote.nyc/sites/default/files/pdf/election_results/2019/20190625Primary%20Election/00302200045Kings%20Member%20of%20the%20City%20Council%2045th%20Council%20District%20EDLevel.csv',
		'https://vote.nyc/sites/default/files/pdf/election_results/2017/20171107General%20Election/00001200000Citywide%20Public%20Advocate%20Citywide%20EDLevel.csv',
		'https://vote.nyc/sites/default/files/pdf/election_results/2017/20171107General%20Election/00101100000New%20York%20Mayor%20Citywide%20EDLevel.csv',
		'https://vote.nyc/sites/default/files/pdf/election_results/2017/20171107General%20Election/00101800010New%20York%20Borough%20President%20New%20York%20EDLevel.csv',
		'https://vote.nyc/sites/default/files/pdf/election_results/2017/20171107General%20Election/00101900010New%20York%20District%20Attorney%20New%20York%20EDLevel.csv',
		'https://vote.nyc/sites/default/files/pdf/election_results/2017/20171107General%20Election/00102100026New%20York%20State%20Senator%2026th%20Senatorial%20District%20EDLevel.csv',
		'https://vote.nyc/sites/default/files/pdf/election_results/2017/20171107General%20Election/00102200001New%20York%20Member%20of%20the%20City%20Council%201st%20Council%20District%20EDLevel.csv',
		'https://vote.nyc/sites/default/files/pdf/election_results/2017/20171107General%20Election/00001300000Citywide%20City%20Comptroller%20Citywide%20EDLevel.csv',
		'https://vote.nyc/sites/default/files/pdf/election_results/2017/20170912Primary%20Election/01001100000Citywide%20Democratic%20Mayor%20Citywide%20EDLevel.csv',
		'https://vote.nyc/sites/default/files/pdf/election_results/2018/20181106General%20Election/00000500000Citywide%20Governor%20Lieutenant%20Governor%20Citywide%20EDLevel.csv',
		'https://vote.nyc/sites/default/files/pdf/election_results/2018/20181106General%20Election/00100900000New%20York%20United%20States%20Senator%20Citywide%20EDLevel.csv',
		'https://vote.nyc/sites/default/files/pdf/election_results/2018/20181106General%20Election/00102000010New%20York%20Representative%20in%20Congress%2010th%20Congressional%20District%20EDLevel.csv',
		'https://vote.nyc/sites/default/files/pdf/election_results/2018/20181106General%20Election/00102300070New%20York%20Member%20of%20the%20Assembly%2070th%20Assembly%20District%20EDLevel.csv',
		'https://vote.nyc/sites/default/files/pdf/election_results/2018/20181106General%20Election/00050200000Citywide%20Civic%20Engagement%20Commission%20Citywide%20EDLevel.csv',
		'https://vote.nyc/sites/default/files/pdf/election_results/2018/20180913Primary%20Election/01000400000Citywide%20Democratic%20Governor%20Citywide%20EDLevel.csv'
	];



$db = new db(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$db->q('TRUNCATE el_results');
$db->q('TRUNCATE el_resultsm');
foreach ($ff as $f)
	import($f);

fill_dicts();




function import($url)
{
	global $db;
	$c = Curl::exec($url);
	echo "\n{$url}\n";
	flush();
	$csv = csv::parseCSV($c, ',', '"', "\r\n", 0, false);
	$ff = ['AD','ED','County','EDAD Status','Event','Party/Independent Body','Office/Position Title','District Key','VoteFor','Unit Name','Tally'];
	$mm = $cc = [];
	foreach ($csv as $r)
	{
		$r = array_diff($r, $ff);
		$n++;
		if ($r)
		{
			$m = array_combine($ff, $r);
			if (strstr($m['Unit Name'], '('))
				$mm[] = maprow($m);
			elseif ($m['EDAD Status'] <> 'IN-PLAY')
				$cc[] = maprow($m);
				
			if (!($n % 500))
			{
				$db->insert_ignore('el_results', $mm);
				$mm = [];
				$db->insert_ignore('el_resultsm', $cc);
				$cc = [];
				echo sprintf("%.02f%%-", $n / count($csv) * 100);
				flush();
			}
		}
	}
	$db->insert_ignore('el_results', $mm);
	echo sprintf("%.02f%%", $n / count($csv) * 100);
	flush();
}

function maprow($m)
{
	return $m['EDAD Status'] == 'IN-PLAY' 
	? [
		'ad' => $m['AD'],
		'aded' => sprintf('%02u%03u', $m['AD'], $m['ED']),
		'county' => $m['County'],
		'district' => $m['District Key'],
		'event' => $m['Event'],
		'position' => $m['Office/Position Title'],
		'party' => $m['Party/Independent Body'],
		'participant' => $m['Unit Name'],
		'tally' => $m['Tally'],
	]
	: [
		'ad' => $m['AD'],
		'aded' => sprintf('%02u%03u', $m['AD'], $m['ED']),
		'county' => $m['County'],
		'district' => $m['District Key'],
		'event' => $m['Event'],
		'position' => $m['Office/Position Title'],
		'party' => $m['Party/Independent Body'],
		'msg' => $m['EDAD Status'],
	];
	
}

function fill_dicts()
{
	global $db;
	$q = <<<SQL
	
	/* ad */
	UPDATE el_results e
	INNER JOIN 
	(SELECT p.event, p.position, p.party, p.participant, CASE WHEN s.ss THEN p.ss/s.ss * 100 ELSE 0.00 END as perc,
			p.ad
	FROM
		(SELECT sum(tally) as ss, event, position, party, participant,
				ad
			FROM (SELECT * FROM el_results) r
			GROUP BY event, position, party, participant,
				ad) p
	INNER JOIN
		(SELECT sum(tally) as ss, event, position, party,
				ad
			FROM (SELECT * FROM el_results) r
			GROUP BY event, position, party,
				ad) s
	ON p.event=s.event AND p.position=s.position AND p.party=s.party AND	
				p.ad=s.ad
	) p
	ON p.event=e.event AND p.position=e.position AND p.party=e.party AND p.participant=e.participant AND
				p.ad=e.ad
	SET perc_ad=perc;


	/* aded */
	UPDATE el_results e
	INNER JOIN 
	(SELECT p.event, p.position, p.party, p.participant, CASE WHEN s.ss THEN p.ss/s.ss * 100 ELSE 0.00 END as perc,
			p.aded
	FROM
		(SELECT sum(tally) as ss, event, position, party, participant,
				aded
			FROM (SELECT * FROM el_results) r
			GROUP BY event, position, party, participant,
				aded) p
	INNER JOIN
		(SELECT sum(tally) as ss, event, position, party,
				aded
			FROM (SELECT * FROM el_results) r
			GROUP BY event, position, party,
				aded) s
	ON p.event=s.event AND p.position=s.position AND p.party=s.party AND	
				p.aded=s.aded
	) p
	ON p.event=e.event AND p.position=e.position AND p.party=e.party AND p.participant=e.participant AND
				p.aded=e.aded
	SET perc_aded=perc;


	/* county */
	UPDATE el_results e
	INNER JOIN 
	(SELECT p.event, p.position, p.party, p.participant, CASE WHEN s.ss THEN p.ss/s.ss * 100 ELSE 0.00 END as perc,
			p.county
	FROM
		(SELECT sum(tally) as ss, event, position, party, participant,
				county
			FROM (SELECT * FROM el_results) r
			GROUP BY event, position, party, participant,
				county) p
	INNER JOIN
		(SELECT sum(tally) as ss, event, position, party,
				county
			FROM (SELECT * FROM el_results) r
			GROUP BY event, position, party,
				county) s
	ON p.event=s.event AND p.position=s.position AND p.party=s.party AND	
				p.county=s.county
	) p
	ON p.event=e.event AND p.position=e.position AND p.party=e.party AND p.participant=e.participant AND
				p.county=e.county
	SET perc_county=perc;


	/* el_participants id|event|position|party|participant */
	TRUNCATE el_participants;
	INSERT IGNORE INTO el_participants (event,position,party,participant) SELECT DISTINCT event,position,party,participant FROM el_results;

	/* el_events_list event|position|party|district */
	TRUNCATE el_events_list;
	INSERT IGNORE INTO el_events_list (event,position,party,district) SELECT DISTINCT event,position,party,district FROM el_results;

SQL;

	$db->q($q);
}