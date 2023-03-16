<?php
	function get_domain($url) {
		// Parse the URL into its components
		$parsed_url = parse_url($url);

		// Get the host (e.g. www.example.com)
		$host = $parsed_url['host'];

		// Split the host into its subdomains and the top-level domain
		$subdomains = explode('.', $host);

		// Get the number of subdomains
		$num_subdomains = count($subdomains);

		// If the number of subdomains is greater than 2, the main domain is the last two subdomains
		if ($num_subdomains > 2) {
			//$main_domain = $subdomains[$num_subdomains - 2] . '.' . $subdomains[$num_subdomains - 1];
		} else {
		}
		
		$main_domain = $host;
		return $main_domain;
	}

	header("Content-Type: text/html; charset=UTF-8");
	$url = $_POST["url"];
	$html = file_get_contents($url);

	libxml_use_internal_errors(true);
	$doc = new DOMDocument();
	$doc->recover = true; // attempt to recover from errors
	$doc->loadHTML($html);
	libxml_clear_errors();


	$metas = $doc->getElementsByTagName("meta");
	$head = $doc->getElementsByTagName("head")->item(0);
	$title_elem = $head->getElementsByTagName("title")->item(0);

	$title = $title_elem ? $title_elem->nodeValue : "";
	$description = "";
	$image = "";

	foreach ($metas as $meta) {
		if ($meta->getAttribute("property") == "og:title") {
			$title = $meta->getAttribute("content");
		}

		if ($meta->getAttribute("property") == "og:description" || $meta->getAttribute("name") == "description") {
			$description = $meta->getAttribute("content");
		}
		if ($meta->getAttribute("property") == "og:image") {
			$image = $meta->getAttribute("content");
		}
	}

	$data = [
		"url" => $url,
		"title" => $title,
		"description" => $description,
		"image" => $image,
		"main_domain" => get_domain($url)
	];
	echo json_encode($data);
?>