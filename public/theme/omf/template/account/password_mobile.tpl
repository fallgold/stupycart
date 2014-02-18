<?php echo $header; ?>
<div id="main" role="main">
	<?php echo $content_top; ?>	
	<ul id="breadcrumbs">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	<?php } ?>
	</ul>
	<h1><?php echo $heading_title; ?></h1>
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="password">
		<h2><?php echo $text_password; ?></h2>
		<fieldset>
		<ul>
			<li>
			  <label for="password"><span class="required">*</span> <?php echo $entry_password; ?></label>
			  <input type="password" name="password" value="<?php echo $password; ?>" />
				<?php if ($error_password) { ?>
				<span class="s-error"><?php echo $error_password; ?></span>
				<?php } ?>
			</li>
			<li>
			  <label for="confirm"><span class="required">*</span> <?php echo $entry_confirm; ?></label>
			  <input type="password" name="confirm" value="<?php echo $confirm; ?>" />
				<?php if ($error_confirm) { ?>
				<span class="s-error"><?php echo $error_confirm; ?></span>
				<?php } ?>
			</li>
        </ul>
		<input type="submit" value="<?php echo $button_continue; ?>" />
	</fieldset>
	<a href="<?php echo $back; ?>" class="button"><span><?php echo $button_back; ?></span></a>
  </form>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>