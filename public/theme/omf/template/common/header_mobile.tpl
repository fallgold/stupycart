<?php if (isset($_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6') && strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n" ."<!-- This is here for the Old Opera mobile  -->"; ?>
<!DOCTYPE html>
<!-- OMFramework 1.6.5 Lite www.omframework.com -->
<!--[if IEMobile 7 ]>    <html class="no-js iem7"> <![endif]-->
<!--[if (gt IEMobile 7)|!(IEMobile)]><!--> <html> <!--<![endif]-->
	<head>
		<meta charset="utf-8">

		<title><?php echo $title; ?></title>
		<?php if ($description) { ?><meta name="description" content="<?php echo $description; ?>"><?php } ?>
		<meta name="author" content="">

		<base href="<?php echo $base; ?>" >

		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<meta name="apple-touch-fullscreen" content="YES">

		<meta http-equiv="cleartype" content="on">

		<?php if ($keywords) { ?>
		<meta name="keywords" content="<?php echo $keywords; ?>" >
		<?php } ?>
		<?php if ($icon) { ?>
		<link href="<?php echo $icon; ?>" rel="icon" >
		<?php } ?>
		<?php foreach ($links as $link) { ?>
		<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" >
		<?php } ?>
		<script>
		document.cookie='resolution='+Math.max(screen.width,screen.height)+'; path=/';
		window.location.hash = '#container';
		(function(w,d,u){w.readyQ=[];w.bindReadyQ=[];function p(x,y){if(x=="ready"){w.bindReadyQ.push(y);}else{w.readyQ.push(x);}};var a={ready:p,bind:p};w.$=function(f){if(f===d||f===u){return a}else{p(f)}}})(window,document)</script>
		<?php if (file_exists(DIR_TEMPLATE . $this->config->get('config_mobile_theme') . '/stylesheet/mobile.scss')) { ?>

		<link rel="stylesheet" type="text/css" href="<?php echo 'catalog/view/theme/' . $this->config->get('config_mobile_theme') ?>/s.php?p=mobile.scss" >
		<?php } else {?>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/omf/s.php?p=mobile.scss" >
		<?php } ?>
	</head>
	<body>
		<div id="container">
			<header id="header">
				<?php if ($mobile_logo) { ?>
				<a href="<?php echo $home; ?>" id="logo"><img src="<?php echo $mobile_logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>"></a>
				<?php } ?>
				<ul>

			<?php if ( is_null($this->config->get('config_wishlist_disabled')) or (bool)$this->config->get('config_wishlist_disabled') == false) { ?>
					<li><a href="<?php echo $wishlist; ?>" id="wishlist_total"><?php echo $text_wishlist; ?></a></li>
			<?php } ?>
					<li><a href="#search" tabindex="3" id="search_link"><?php echo $text_search_link; ?></a></li>
				</ul>
			</header>
			</header>
