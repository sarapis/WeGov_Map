<pre><?php 
include '../data_include/autoload.php';

# elimportshould be imported before this script

$db = new db(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$nycdb = new db(DB_HOST, DB_USER, DB_PASS, 'devinbalkind_nycvoters_2023');
#$db->q('TRUNCATE el_results');
#$db->q('TRUNCATE el_resultsm');

$dd = $nycdb->q(<<<EOL
	SELECT 
			Assembly_District as ad,
			CONCAT(Assembly_District, Election_District) as aded,
			County as county,
			'NYC' as district,
			'REP vs. DEM' as event,
			'NYC VOTERS' as position,
			'' as party,
			Political_Party as participant,
			count(*) as tally
		FROM voter_data
		WHERE Political_Party IN ('DEM', 'REP')
		GROUP BY ad, aded, county, participant
EOL);

$db->q('DELETE FROM el_results WHERE position = "NYC VOTERS"');
$db->insert_ignore('el_results', $dd);

echo sprintf("REP vs. DEM \n");
flush();


$dd = $nycdb->q(<<<EOL
	SELECT 
			Assembly_District as ad,
			CONCAT(Assembly_District, Election_District) as aded,
			County as county,
			'NYC' as district,
			'Major Parties vs Everyone Else' as event,
			'NYC VOTERS' as position,
			'' as party,
			CASE WHEN Political_Party IN ('REP', 'DEM', 'WOR', 'CON') THEN 'Major Parties' WHEN Political_Party IN ('BLK', 'OTH') THEN 'Minor Parties' END as participant,
			count(*) as tally
		FROM voter_data
		WHERE Political_Party IN ('REP', 'DEM', 'WOR', 'CON', 'BLK', 'OTH')
		GROUP BY ad, aded, county, participant
EOL);
$db->insert_ignore('el_results', $dd);

echo sprintf("Major Parties vs Everyone Else \n");
flush();


$dd = $nycdb->q(<<<EOL
	SELECT
			Assembly_District as ad,
			CONCAT(Assembly_District, Election_District) as aded,
			County as county,
			'NYC' as district,
			'Map of Formerly IND Members' as event,
			'NYC VOTERS' as position,
			'' as party,
			CASE WHEN Former_IND = TRUE THEN 'YES' ELSE 'NO' END as participant,
			count(*) as tally
		FROM voter_data
		GROUP BY ad, aded, county, participant
EOL);
$db->insert_ignore('el_results', $dd);

echo sprintf("Former IND \n");
flush();


fill_dicts();





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