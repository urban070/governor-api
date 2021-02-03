<?php

function checkInclude($urlInput) {
	if(is_file($urlInput)) {
		include_once($urlInput);
		exit;
	} else {
		header('Location: /404');
		exit;
	}
}

function error403() {
	header('HTTP/1.1 403 Forbidden');
	echo("403");
	exit;
}

function error404() {
	header('HTTP/1.1 404 Not Found');
	echo("404");
	exit;
}

function error500() {
	header('HTTP/1.1 500 Internal Server Error');
	echo("500");
	exit;
}


$wholeUrl = $_SERVER['REQUEST_URI'];

$urlAttributes = explode('?', $wholeUrl);

if(count($urlAttributes) > 1) {
	$wholeUrl = $urlAttributes[0];

	parse_str($urlAttributes[1], $attributeArray);
	
	foreach($attributeArray as $index=>$val){    
		$_GET[$index] = $val;
	}

}

$url = explode('/', $wholeUrl);
$url = array_filter($url);
$urlItems = count($url);

$redirects = array (
	array("/example.php", "http://api.governordao.org/example")
);

$paths = array (
	array("/gdao/circulatingSupply","api/circulatingSupply.php"),
	array("/gdao/totalSupply","api/totalSupply.php"),
	array("/gdao/price","api/price.php")
);

$redirectsCount = count($redirects);
$pathsCount = count($paths);

if($urlItems == 0) {
	echo("Wrong request");
} else {
	
	if($wholeUrl == '/403') {
		error403();
	} else if($wholeUrl == '/404') {
		error404();
	} else if($wholeUrl == '/500') {
		error500();
	}
	
	for($i=0; $i<$redirectsCount; $i++) {
		if($wholeUrl == $redirects[$i][0]) {
			header("Location: ".$redirects[$i][1], true, 301);
			exit();
		}
	}
	
	for($i=0; $i<$pathsCount; $i++) {
		if($wholeUrl == $paths[$i][0]) {
			checkInclude($paths[$i][1]);
			exit();
		}
	}
	
	header('Location: /404');
	exit;
}

?>