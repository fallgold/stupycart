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
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="edit">
		<h2><?php echo $text_your_details; ?></h2>      
		<fieldset>			
			<ul>
				<li>
					<label for="firstname"><span class="required">*</span> <?php echo $entry_firstname; ?></label>
					<input type="text" id="firstname" name="firstname" value="<?php echo $firstname; ?>" class="large-field" />
					<?php if(isset($errors['firstname'])) echo '<span class="s-error">'. $errors['firstname'] .'</span>';?>
				</li>				
				<li>
					<label for="lastname"><span class="required">*</span> <?php echo $entry_lastname; ?></label>
					<input type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>" class="large-field" />
					<?php if(isset($errors['lastname'])) echo '<span class="s-error">'. $errors['lastname'] .'</span>';?>
				</li>
				<li>
					<label for="email"><span class="required">*</span> <?php echo $entry_email; ?></label>
					<input type="email" id="email" name="email" value="<?php echo $email; ?>" class="large-field" />
					<?php if(isset($errors['email'])) echo '<span class="s-error">'. $errors['email'] .'</span>';?>
				</li>
				<li>
					<label for="telephone"><span class="required">*</span> <?php echo $entry_telephone; ?></label>
					<input type="text" name="telephone" value="<?php echo $telephone; ?>" class="large-field" />
					<?php if(isset($errors['telephone'])) echo '<span class="s-error">'. $errors['telephone'] .'</span>';?>			
				</li>
			</ul>
			<input type="hidden" name="fax" value="<?php echo $fax; ?>" class="large-field" /> 			
			<input type="submit" value="<?php echo $button_continue; ?>" />
		</fieldset>    
		<a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a>
	</form>
  <?php echo $content_bottom; ?>
	</div>
<?php echo $footer; ?>