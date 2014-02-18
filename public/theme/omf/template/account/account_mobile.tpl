<?php echo $header; ?>
<div id="main" role="main">
	<?php echo $content_top; ?>	
	<ul id="breadcrumbs">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	<?php } ?>
	</ul>
	<h1><?php echo $heading_title; ?></h1>
	<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<section>
		<?php /*<h2><?php echo $text_my_account; ?></h2>	*/?>
		<ul class="nav">
			<li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
			<li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
			<li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
			<li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
		</ul>
	</section>
	
	<section>
		<h2><?php echo $text_my_orders; ?></h2>	
		<ul class="nav">
			<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
			<li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>			
			<li><a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></li>
			<li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
			<li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
		</ul>	
	</section>
	
	<section>
		<h2><?php echo $text_my_newsletter; ?></h2>	
		<ul class="nav">
			<li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
		</ul>	
	</section> 
	<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?> 
