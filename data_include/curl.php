<?php
class Curl
{
	public static $cookieFolder = DATADIR . '/cookies';
	public const SAVECOOKIESBYDEF = false;
	public static $curl_options = 
		array(
			CURLOPT_RETURNTRANSFER    => 1,
			CURLOPT_BINARYTRANSFER    => 1,
			CURLOPT_CONNECTTIMEOUT    => 45,
			CURLOPT_TIMEOUT            => 90,
			CURLOPT_USERAGENT        => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome',
			CURLOPT_VERBOSE            => 0,
//			CURLOPT_STDERR            => null,
			CURLOPT_HEADER            => 1,
			CURLOPT_FOLLOWLOCATION    => 1,
//			CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_0,  // лечение от transfer closed with 10088805 bytes remaining to read
			CURLOPT_SSL_VERIFYPEER    => 0,
			CURLOPT_SSL_VERIFYHOST    => 0,
			CURLOPT_MAXREDIRS        => 7, 
			CURLOPT_AUTOREFERER        => 1,
			CURLINFO_HEADER_OUT        => 1,
		);
		
	public static function exec($url, $cParam = false, $postdata='', $posttype='post', $saveCookies = self::SAVECOOKIESBYDEF, &$redirect=null) 
	{
		$ch = self::init($url, $cParam, $postdata, $posttype, $saveCookies);
		$res = curl_exec($ch);
		if (curl_errno ($ch)) 
		{
			curl_close($ch);
			return false;
		}
		list($headers, $res) = preg_split('/(\r?\n){2}/', $res, 2);
		$redirect = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		curl_close($ch);
		return $res;
	}
	
	public static function execExt($url, $cParam = false, $postdata='', $posttype='post', $saveCookies = self::SAVECOOKIESBYDEF, &$info=null)
	{
		$ch = self::init($url, $cParam, $postdata, $posttype, $saveCookies);
		$res = curl_exec($ch);
		$info = curl_getinfo($ch);
		$info['cookies'] = curl_getinfo($ch, CURLINFO_COOKIELIST);
		
		if (curl_errno ($ch)) 
		{
			curl_close($ch);
			return false;
		}
		$res = self::processExt($res, $info);
		curl_close($ch);
		return $res;
	}

	public static function init($url, $cParam = false, $postdata='', $posttype='post', $saveCookies = self::SAVECOOKIESBYDEF)
	{
		$ch = curl_init();
		$options = (is_array($cParam)) 
				? $cParam + self::$curl_options 
				: self::$curl_options;
		$options[CURLOPT_URL] = $url; 
		$options[CURLOPT_USERAGENT] = $options[CURLOPT_USERAGENT];
		$cookieFn = $options[CURLOPT_COOKIEJAR] ?? $options[CURLOPT_COOKIEFILE] ?? self::cookieFn($options);

		if (file_exists($cookieFn) && !isset($options[CURLOPT_COOKIEFILE]))
			$options[CURLOPT_COOKIEFILE] = $cookieFn;
		if (!$options[CURLOPT_COOKIEFILE])					// if $options[CURLOPT_COOKIEFILE] set to false - do not set the option
			unset($options[CURLOPT_COOKIEFILE]);
		if ($saveCookies)
		{
			self::cookieMkDir();
			$options[CURLOPT_COOKIEJAR] = $cookieFn; 
		}	
		
		if ($postdata || $postdata == [])
		{
			if (strtolower($posttype) == 'post') {
				if (is_array($postdata)) 		
					$postdata = http_build_query($postdata);
				$options[CURLOPT_HTTPHEADER][] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8"; 
				$options[CURLOPT_HTTPHEADER][] = "Content-Length: " . strlen($postdata); 
				$options[CURLOPT_POST] = 1; 				
				$options[CURLOPT_POSTFIELDS] = $postdata; 	
			} else {
				if (is_array($postdata)) 		
					$postdata = json_encode($postdata);
				$options[CURLOPT_POST] = 1; 				
				$options[CURLOPT_POSTFIELDS] = $postdata; 	
				$options[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
				$options[CURLOPT_HTTPHEADER][] = 'Content-Length: ' . strlen($postdata);
				$options[CURLOPT_CUSTOMREQUEST] = "POST";
			}
		}
		switch (strtolower($posttype))
		{
			case 'delete':
				$options[CURLOPT_CUSTOMREQUEST] = "DELETE";
				break;
			case 'put':
				$options[CURLOPT_CUSTOMREQUEST] = "PUT";
			case 'options':
				$options[CURLOPT_CUSTOMREQUEST] = "OPTIONS";
				break;
		}		
		curl_setopt_array($ch, $options);

		if (VERBOSE_CURL_PARAM === true)
			print_r($options);
		return $ch;
	}
	
	public static function processExt($responce, &$info=null)
	{
		//file_put_contents(DATADIR . '/' . uniqid() . '.dat', $res);
		//echo "\n5symb " . substr($res, 0, 5) . "\n";
		while (substr($responce, 0, 5) == 'HTTP/')
		{	
			//echo substr($responce, 0, 5) . " ";
			list($headersTxt, $responce) = preg_split('/(\r?\n){2}/', $responce, 2);
			//echo "###### Headers: ######\n{$headers}\n######\n";
		}	

		$headersArray = preg_split('/\r?\n/', $headersTxt);
		$headersArray = array_map(function($h) {
			return preg_split('/:\s{1,}/', $h, 2);
		}, $headersArray);
		$headers = [];
		foreach($headersArray as $h) {
			$headers[strtolower($h[0])] = isset($h[1]) ? $h[1] : $h[0];
		}
		if (is_array($info))
		{
			$info['responce_headers'] = $headers;
			$info['responce_headers_raw'] = $headersTxt;
		}	
		if ((($headers['content-encoding'] ?? '') == 'gzip') && $responce)
		{
			$responce = @gzdecode($responce);
			if (!$responce)
				throw new \Exception('Error decoding gzipped content. File may be broken');
		}	
		
		return $responce;
	}
	
	public static function gzipDecodeExt($responce, &$info=null)
	{
		$info = $info ?? [];
		$responce = self::processExt($responce, $info);
		$headers = $info['responce_headers_raw'] 
			? (preg_replace('~Content-Encoding: gzip\r?\n?~si', '', $info['responce_headers_raw']) ?? '') . "\r\n\r\n"
			: '';
		return $headers . $responce;
	}
		
	public static function cookieFn($options)
	{
		$fn = md5(
			parse_url($options[CURLOPT_URL], PHP_URL_HOST) . 
			$options[CURLOPT_PROXY] . 
			$options[CURLOPT_USERAGENT]
		);
		return self::$cookieFolder . "/{$fn}";
	}
	
	public static function cookieMkDir()
	{
		if (!is_dir(dirname(self::$cookieFolder)))
			mkdir(dirname(self::$cookieFolder), 0766);
		if (!is_dir(self::$cookieFolder))
			mkdir(self::$cookieFolder, 0766);
	}
	
	public static function cleanCookieOpt($options)
	{
		$fn = self::cookieFn($options);
		if (file_exists($fn))
			return unlink($fn);
		return false;
	}

	public static function cleanCookie($url, $proxy, $ua)
	{
		return self::cleanCookieOpt([
			CURLOPT_URL => $url,
			CURLOPT_PROXY => $proxy,
			CURLOPT_USERAGENT => $ua,
		]);
	}
	
	public static function cleanCookies($ageLim=null)
	{
		foreach (scandir(self::$cookieFolder) as $fn)
			if (!preg_match('~\.$~si', $fn))
			{
				$fullFn = self::$cookieFolder . "/{$fn}";
				if (!isset($ageLim) || (time() - filemtime($fullFn)) > $ageLim)
					unlink($fullFn);
			}	
	}
	
}