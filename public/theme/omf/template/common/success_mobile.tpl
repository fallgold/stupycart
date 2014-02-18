<?php echo $header; ?>
<div id="main" role="main">
	<?php echo $content_top; ?>
	<ul id="breadcrumbs">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	<?php } ?>
	</ul>
	<h1><?php echo $heading_title; ?></h1>
	<?php echo $text_message; ?>
	<div class="buttons">
		<a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a>
	</div>
	<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>