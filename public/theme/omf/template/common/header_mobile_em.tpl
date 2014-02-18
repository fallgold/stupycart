<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/omf/s.php?p=mobile.scss" >
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<script type="text/javascript" src="catalog/view/javascript/common.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!--[if IE 7]> 
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie7.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('#logo img');
</script>
<![endif]-->
<?php if ($stores) { ?>
<script type="text/javascript"><!--
$(document).ready(function() {
<?php foreach ($stores as $store) { ?>
$('body').prepend('<iframe src="<?php echo $store; ?>" style="display: none;"></iframe>');
<?php } ?>
});
//--></script>
<?php } ?>
<?php echo $google_analytics; ?>

<script type="text/javascript">
</script>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet-mobile.css" />
<style>
.show, .show-table-cell {
	display: none;
}
input[type='email'],input[type='tel'] {
	background: none repeat scroll 0 0 #F8F8F8;
	border: 1px solid #CCCCCC;
	padding: 3px;
	margin-left: 0px;
	margin-right: 0px;
}
#breadcrumb,.breadcrumb{display:none;}
</style>
<script type="text/javascript" src="catalog/view/javascript/mobile/mobile.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	/* Search */
	$('.button-search-mobile').bind('click', function() {
		url = $('base').attr('href') + 'index.php?route=product/search';
				 
		var search = $('#search_mobile input').attr('value');
		
		if (search) {
			url += '&search=' + encodeURIComponent(search);
		}
		
		location = url;
	});
});
</script>
			
</head>
<body>
<div id="container">
<div id="header">
  <?php echo $language; ?>
  <?php echo $currency; ?>
  <?php echo $cart; ?>
  <div id="welcome">
    <?php if (!$logged) { ?>
    <?php echo $text_welcome; ?>
    <?php } else { ?>
    <?php echo $text_logged; ?>
    <?php } ?>
  </div>
  <div class="links"><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a><a href="<?php echo $wishlist; ?>" id="wishlist-total"><?php echo $text_wishlist; ?></a><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a><a href="<?php echo $shopping_cart; ?>"><?php echo $text_shopping_cart; ?></a><a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></div>
</div>
<?php if ($categories) { ?>

<div id="menu-mobile" style="display: none;">
  	<ul class="closed">
    	<?php foreach ($categories as $category) { ?>
    		<li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
    	<?php } ?>
  	</ul>
</div>
<div id="links-mobile" style="display: none;">
	<a href="<?php echo $home; ?>"><?php echo $text_home; ?></a>
	<a href="<?php echo $account; ?>"><?php echo $text_account; ?></a>
	<a href="<?php echo $shopping_cart; ?>"><?php echo $text_shopping_cart; ?></a>
	<a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a>
</div>

				<form action="index.php?route=product/search_mobile" method="post" id="search" class="inline-form module">
					<fieldset >
						<?php
						$field_name = (defined('VERSION') && (version_compare(VERSION, '1.5.5', '>=') == true)) ? 'search' : 'filter_name';
						if (!empty($filter_name)) { ?>
						<input type="search" name="<?php echo $field_name; ?>" placeholder="<?php echo $filter_name; ?>" />
						<?php } else { ?>
						<input type="search" name="<?php echo $field_name; ?>"  placeholder="<?php echo $text_search; ?>" />
						<?php } ?>
						<input type="submit" value="<?php echo $text_search_link; ?>" />
					</fieldset>
				</form>

<div id="all_categories" style="display:none;width:90%;margin:0 auto !important;">
				<?php if (isset($categories)) { ?>
				<nav id="secondary">
					<ul class="nav">
						<?php $i = 0; ?>
						<?php foreach ($categories as $category) { ?>
						<li>
							<a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
						</li>
						<?php  /* what is this???  if (++$i == $config_mobile_front_page_cat_list) break; **/ ?>
						<?php } ?>
					</ul>
					<?php if (mysql_num_rows( mysql_query("SHOW TABLES LIKE '". DB_PREFIX ."blog_setting'")) == '1') { ?>
            		<a href="<?php echo $this->url->link('blog/category/home'); ?>"> <?php echo $text_blog; ?> </a>
         			<?php } ?>
				</nav>
				<?php } ?>
</div>

<div id="menu">
  <ul>
    <?php foreach ($categories as $category) { ?>
    <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
      <?php if ($category['children']) { ?>
      <div>
        <?php for ($i = 0; $i < count($category['children']);) { ?>
        <ul>
          <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
          <?php for (; $i < $j; $i++) { ?>
          <?php if (isset($category['children'][$i])) { ?>
          <li><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></li>
          <?php } ?>
          <?php } ?>
        </ul>
        <?php } ?>
      </div>
      <?php } ?>
    </li>
    <?php } ?>
  </ul>
</div>
<?php } ?>
<div id="notification"></div>
