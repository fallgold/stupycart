<?php echo $header; ?>
<div id="main" role="main">
	<?php echo $content_top; ?>
	
	<ul id="breadcrumbs">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	<?php } ?>
	</ul>
	
	<h1><?php echo $heading_title; ?></h1>
	<?php if ($categories) { ?>
	<p>
		<?php echo $text_index; ?>
		<ul>
			<?php foreach ($categories as $category) { ?>
			<li><a href="index.php?route=product/manufacturer#<?php echo $category['name']; ?>"><?php echo $category['name']; ?></a></li>
			<?php } ?>
		</ul>
	</p>
	<?php foreach ($categories as $category) { ?>
	<div class="manufacturer-list">
		<span id="<?php echo $category['name']; ?>" class="manufacturer-heading"><?php echo $category['name']; ?></span>		
		<?php if ($category['manufacturer']) { ?>
		<?php for ($i = 0; $i < count($category['manufacturer']);) { ?>
		<ul class="manufacturer-content">
			<?php $j = $i + ceil(count($category['manufacturer']) / 4); ?>
			<?php for (; $i < $j; $i++) { ?>
			<?php if (isset($category['manufacturer'][$i])) { ?>
			<li><a href="<?php echo $category['manufacturer'][$i]['href']; ?>"><?php echo $category['manufacturer'][$i]['name']; ?></a></li>
			<?php } ?>
			<?php } ?>
		</ul>
		<?php } ?>
		<?php } ?>	
	</div>
	<?php } ?>
	<?php } else { ?>
	<div class="content">
		<?php echo $text_empty; ?>
	</div>
	<div class="buttons">
		<div class="right"><a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_continue; ?></span></a></div>
	</div>
	<?php } ?>
	<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>