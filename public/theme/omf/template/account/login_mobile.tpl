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
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div>
		<form action="<?php echo $action; ?>" method="post">
			<ul>
				<li>
					<label for="email"><?php echo $entry_email; ?></label>
					<input type="email" id="email" name="email" value="" />
				</li>
				<li>
					<label for="password"><?php echo $entry_password; ?></label>
					<input type="password" id="password" name="password" value="" />	
				</li>
			</ul>
			<a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
			<?php if ($redirect) { ?>
			<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
			<?php } ?>			
			<input type="submit" value="<?php echo $button_login; ?>" />				
		</form>	  
    </div>  
  <?php echo $content_bottom; ?>
  </div>
<?php echo $footer; ?>