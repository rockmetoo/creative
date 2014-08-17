<?php

	global $CREATIVE_SYSTEM_DEF;
	global $JS_STRING;
	global $JS_FILES;
	global $CSS_FILES;
	
	// XXX: all global js to js array.
	$globalJS_FILES = array('jquery.min.js', 'jquery.ui.js');
	
	if(isset($JS_FILES) && is_array($JS_FILES))
	{
		$JS_FILES = array_unique(array_merge($globalJS_FILES, $JS_FILES));
	}
	else $JS_FILES = &$globalJS_FILES;

	//set js script text
	$JS_STRING = trim($JS_STRING);

	//set all css to css array
	$globalCSS = array('screen.css' => 'all');
	
	if(isset($CSS_FILES) && is_array($CSS_FILES))
	{
		$CSS_FILES = array_merge($globalCSS, $CSS_FILES);
	}
	else $CSS_FILES = &$globalCSS;

	// Net_UserAgent_Detect::getBrowser we need to use
	$isStdBrowser = '';

	// Set content types depending on browser standard compliance.
	// This will generate more reliable output in none IE browsers.
	$contentType = array(
		'script'	=> ($isStdBrowser) ? 'application/javascript' : 'text/javascript',
		'html'		=> ($isStdBrowser) ? 'application/xhtml+xml' : 'text/html'
	);

	// what type of documents we are serving
	mb_internal_encoding('UTF-8');
	header('Content-Type: ' . $contentType['html'] . '; charset=utf-8');
	$contentLanguage = ($CREATIVE_SYSTEM_DEF['lang']) ? $CREATIVE_SYSTEM_DEF['lang'] : 'en';
	header('Content-Language: ' . $contentLanguage);
	// prevent document caching
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', false);
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $contentLanguage; ?>" lang="<?php echo $contentLanguage; ?>">
		<head>
			<meta http-equiv="Content-Type" content="<?php echo $contentType['html']; ?>;charset=utf-8" />
			<title>
				<?php
					if(isset($title)) echo $title . ' : ' . 'Creative model test for you';
					else
					{
						echo 'Creative model test for you';
					}
				?>
			</title>
		<meta content="all" name="audience" />
		<meta content="index,all" name="robots" />
		<meta content="2 days" name="revisit-after" />
		<meta name="Author" content="Mituz Car" />
		<meta name="description" content="<?php if(isset($metaDescription)) echo $metaDescription; else echo 'model test'; ?>" />
		<meta name="keywords" content="<?php if(isset($metaKeywords)) echo $metaKeywords; else echo 'open question and exam site'; ?>" />
		<?php
			foreach($JS_FILES as $scriptName)
			{
		?>
		<script
			<?php if(!$isStdBrowser) echo 'language="javascript"';?>
			src="js/<?php echo htmlspecialchars($scriptName);
			echo '?version=' . filemtime(CSettings::$BASE_DIRECTORY . '/js/'
			. htmlspecialchars($scriptName)); ?>" type="<?php echo $contentType['script']; ?>"
		></script>
	<?php
		}
		if(isset($JS_STRING))
		{
	?>
			<script <?php if(!$isStdBrowser) echo 'language="javascript"'; ?>
				type="<?php echo $contentType['script']; ?>">
				<?php
					if($isStdBrowser) echo "// <![CDATA[ \n";
					echo $JS_STRING;
					if($isStdBrowser) echo "// ]]>";
				?>
			</script>
		<?php
		}
		foreach($CSS_FILES as $file => $media)
		{
		?>
			<link rel="stylesheet" type="text/css"
				href="css/<?php echo htmlspecialchars($file); echo '?version=' .
				filemtime(CSettings::$BASE_DIRECTORY . '/css/' . htmlspecialchars($file)); ?>"
				media="<?php if($media) echo $media; else echo 'all'; ?>"
			/>
		<?php
		}
		?>
</head>
<body>