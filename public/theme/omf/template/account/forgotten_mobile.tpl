<?php echo $header; ?>
<div id="main" role="main">
	<?php echo $content_top; ?>	
	<ul id="breadcrumbs">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	<?php } ?>
	</ul>
	<h1><?php echo $heading_title; ?></h1>
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>	
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="forgotten">
		<ul>
			<li>
				<?php echo $text_email; ?>
			</li>
			<li>
				<label for="email"><?php echo $entry_email; ?></label>
				<input type="text" name="email" value="" />
			</li>
		</ul>
		<input type="submit" value="<?php echo $button_continue; ?>" />		
		<?php /*<div class="left"><a href="<?php echo $back; ?>" class="button"><span><?php echo $button_back; ?></span></a></div>  */ ?>
	</form>
	<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>