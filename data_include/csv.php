<?php
class csv
{
	static protected $trimRows = true;
	
	static function encodeCSV($dd, $delim=",", $quot='"', $strDelim="\n", $saveHeaders=true, $decimalConv=true)
	{
		if (!$dd)
			return '';
		$tt = [];
		foreach ($dd as $k=>$v)
			$tt = array_merge($tt, $v);
		$ff = array_keys($tt);
		$rr = $saveHeaders ? [$quot . implode($quot . $delim . $quot, $ff) . $quot] : [];
		foreach ($dd as $d) {
			$tmp = [];
			foreach ($ff as $f)
			{
				$v = $d[$f];
				if (is_real($v))
					$v = $decimalConv ? str_replace(".", ",", (string)$d[$f]) : (string)$d[$f];
				$v = $quot ? str_replace($quot, $quot . $quot, $v) : $v;
				$tmp[$f] = $quot . $v . $quot;
			}
			$rr[] = implode($delim, $tmp);
		}
		$res = implode($strDelim, $rr);
		return $res;
	}

	static function parseCSV($content, $delim=";", $quot='"', $strDelim="\r\n", $shift=0, $index=true) 
	{
		$rr = explode($strDelim, $content);
		if ($shift)		
			$rr = array_slice($rr, $shift);
		$arr = [];
		$hh = str_getcsv(array_shift($rr), $delim, $quot);
		if (!$index) 		
			$hh = range(0, count($hh) - 1);
		foreach ($rr as $r) 	{
			if (!$r) 	
				continue;
			$vv = str_getcsv($r, $delim, $quot);
			if (count($hh) <> count($vv) && self::$trimRows)
			{
				$l = min(count($hh), count($vv));
				$hhh = array_slice($hh, 0, $l);
				$vvv = array_slice($vv, 0, $l);
				$arr[] = array_combine($hhh, $vvv);
			}
			else 
				$arr[] = array_combine($hh, $vv);
		}
		return $arr;
	}
	
	static function parseFile($fileName, $delim=";", $quot='"', $strDelim="\r\n", $shift=0, $index=true) 
	{
		if (!file_exists($fileName))
			throw new Exception('CSV::parseFile: File not found');
		$content = file_get_contents($fileName);
		return self::parseCSV($content, $delim, $quot, $strDelim, $shift, $index);
	}
}	