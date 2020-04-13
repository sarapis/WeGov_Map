<?php 
include '../../data_include/autoload.php';
session_start();

$model = new AirtableCovidModel();
if (!$_SESSION['ntas'])
	$_SESSION['ntas'] = $model->getNtaList();
$ntaName = mapIncomingRequest($_GET);

$cards = $ntaName ? $model->findOrgsByNta($ntaName) : null;

header('content-type: text/html; charset=UTF-8');
//echo json_encode($cards);
viewResponse($cards);



function mapIncomingRequest($rr)
{
	if (!$rr['nta'] && !$rr['address'])
		return null;
	if (is_array($rr['nta']))
		$rr['nta'] = array_shift($rr['nta']);
	if ($rr['address'])
	{
		$gcNta = Geoclient::getNtaCode($rr['address']);
		if ($gcNta)
			$rr['nta'] = $gcNta;
	}	
	
	return $_SESSION['ntas'][$rr['nta']]['name'];
}


/// viewer ////////////////////////////////////////////////

function viewResponse($cc)
{
	if ($cc === null)
	{
		echo 'It is not possible to detect neighborhood zone by entered address. Please try again';
		die();
	}
	if (!$cc)
	{
		echo 'Sadly nothing found. Please try another neighbourhood zone';
		die();
	}
	?>
	<div>
	  <?php foreach ($cc as $c)
	  {
			viewGroup($c);
	  }
	  ?>
	</div>
	
	<?php
}


function viewGroup($gr)
{
	$vv = new grViewer($gr);
	$ii = $vv->images();
?>
	<div class="card mb-3">
		<div class="card-body">
			<?php if ($ii) : ?>
			  <?php $img = array_pop($ii); ?>
			  <div class="galleryIcons">
				<a href="<?php echo $img['url']; ?>" target="_blank">
					<img class="bigIcon" src="<?php echo $img['bigIcon']; ?>"/>
				</a>
				<?php if ($ii) : ?>
				  <div>
					<?php foreach($ii as $img) : ?>
						<a href="<?php echo $img['url']; ?>" target="_blank">
							<img class="smallIcon" src="<?php echo $img['icon']; ?>"/>
						</a>
					<?php endforeach; ?>
				  </div>
				<?php endif; ?>
			  </div>
			<?php endif; ?>
			<h5 class="title col-11 pl-0"><?php echo $vv->v('Group Name'); ?></h5>
			<div class="project-desc mb-2"><?php echo nl2br($vv->v('Short Description')); ?></div>
			
			<p>
				<?php if ($vv->dd['Group Phone']) : ?>
					<a title="<?php $vv->v('Group Phone'); ?>" href="tel:<?php $vv->v('Group Phone'); ?>"><i data-feather="phone-outgoing"></i></a>
				<?php endif; ?>
				<?php if ($vv->dd['Group Email']) : ?>
					<a title="<?php $vv->v('Group Email'); ?>" href="mailto:<?php $vv->v('Group Email'); ?>"><i data-feather="mail"></i></a>
				<?php endif; ?>
				<?php if ($vv->dd['Website']) : ?>
					<a title="<?php $vv->v('Website'); ?>" href="<?php $vv->v('Website'); ?>" target="_blank"><i data-feather="external-link"></i></a>
				<?php endif; ?>
				<?php if ($vv->dd['Facebook']) : ?>
					<a title="<?php echo $vv->dd['Facebook']; ?>" href="<?php $vv->v('Facebook'); ?>" target="_blank"><i data-feather="facebook"></i></a>
				<?php endif; ?>
				<?php if ($vv->dd['Instagram']) : ?>
					<a title="<?php echo $vv->dd['Instagram']; ?>" href="<?php $vv->v('Instagram'); ?>" target="_blank"><i data-feather="instagram"></i></a>
				<?php endif; ?>
				<?php if ($vv->dd['Twitter']) : ?>
					<a title="<?php echo $vv->dd['Twitter']; ?>" href="<?php $vv->v('Twitter'); ?>" target="_blank"><i data-feather="twitter"></i></a>
				<?php endif; ?>
				
				<div class="ml-0 mt-1"><i data-feather="map-pin"></i>
					<small>
						<?php echo implode(' | ', $vv->ntas()); ?>
					</small>
				</div>
			</p>
			
			<div class="modalPills">
				<?php foreach(mapTypes($vv->dd['Geographical Scope']) as $type => $style) : ?>
					<span class="badge badge-pill" <?php echo $style; ?>><?php echo $type; ?></span>
				<?php endforeach; ?>
			</div>
			
		</div>
	</div>
<?php
}

function mapTypes($tt)
{
	$idx = [
		'Global' => 'style="background-color: #550D00; color: #EEEEEE;"',
		'National' => 'style="background-color: #4E9C68; color: #EEEEEE;"',
		'New York City' => 'style="background-color: #022835; color: #EEEEEE;"', 
		'New York State' => 'style="background-color: #003E15; color: #EEEEEE;"',
		'Bronx' => 'style="background-color: #802615; color: #EEEEEE;"',
		'Brooklyn' => 'style="background-color: #804C15; color: #EEEEEE;"',
		'Queens' => 'style="background-color: #104050; color: #EEEEEE;"',
		'Manhattan' => 'style="background-color: #105D2A; color: #EEEEEE;"',
		'Staten Island' => 'style="background-color: #FFB7AA; color: #550D00;"',
		'Long Island' => 'style="background-color: #FFD6AA; color: #552C00;"',
		'New Jersey' => 'style="background-color: #7CA3AF; color: #022835;"',
		/*
		'food' => 'style="background-color: #9CDBB2; color: #003E15;"',
		'advocacy' => 'style="background-color: #D47B6A; color: #000000;"',
		'housing' => 'style="background-color: #D4A16A; color: #000000;"',
		'mental health' => 'style="background-color: #6595B5; color: #000000;"',
		'information' => 'style="background-color: #4E9C68; color: #000000;"',
		'digital access' => 'style="background-color: #3F002E; color: #EEEEEE;"',
		'delivery/transport' => 'style="background-color: #5F1049; color: #EEEEEE;"',
		'Medical' => 'style="background-color: #BE7FAD; color: #3F002E;"'
		*/
	];
	$rr = [];
	foreach ((array)$tt as $t)
		$rr[$t] = $idx[$t];
	return $rr;
}


class grViewer
{
	var $dd;
	function __construct($gr)
	{
		$this->dd = ['id' => $gr['id']] + $gr['fields'];
	}
	
	function v($f)
	{
		$d = $this->dd[$f] ?? '';
		switch ($f)
		{
			case 'Facebook':
				$d = str_replace('@', 'https://www.facebook.com/', $d);
				break;
			case 'Instagram':
				$d = str_replace('@', 'https://www.instagram.com/', $d);
				break;
			case 'Twitter':
				$d = str_replace('@', 'https://twitter.com/', $d);
				break;
		}
		echo $d;
	}
	
	function images()
	{
		$rr = [];
		foreach ((array)$this->dd['Cover Image'] as $i)
			$rr[] = ['url' => $i['url'], 'icon' => $i['thumbnails']['small']['url'], 'bigIcon' => $i['thumbnails']['large']['url']];
		return $rr;
	}
	
	function ntas()
	{
		if (!$_SESSION['ntasById'])
		{
			$_SESSION['ntasById'] = [];
			foreach($_SESSION['ntas'] as $nta)
				$_SESSION['ntasById'][$nta['id']] = $nta;
		}	
		$rr = [];
		foreach ($this->dd['Neighborhoods'] as $ntaId)
			$rr[] = $_SESSION['ntasById'][$ntaId]['name'];
		return $rr;	
	}
}