	<section>
		<h2><?php echo $text_new_customer; ?></h2>			
		<ul >
	<?php if ($guest_checkout) { ?>		
			<li><a href="<?php echo $guest_checkout_url; ?>" class="button" id="guestCheckout"><?php echo $text_guest; ?></a></li>			
	<?php } ?>			
			<li><a href="<?php echo $register_checkout_url; ?>" class="button" id="registerAccount"><?php echo $text_register; ?></a></li>
		</ul>
		<p><?php echo $text_register_account; ?></p>
	</section>
	
	<section>
		<h2><?php echo $text_returning_customer; ?></h2>
		<?php if(isset($errors['warning'])) echo '<span class="warning">'. $errors['warning'] . '</span>';?>			
<?php if (defined('VERSION') && (version_compare(VERSION, '1.5.2', '<') == true)) { ?>
		<form action="index.php?route=checkout/login" method="post">
<?php } else { ?>
		<form action="index.php?route=checkout/login/validate" method="post">
<?php } ?>
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
			<input type="submit" value="<?php echo $button_login; ?>" id="button-login"/>				
		</form>
	</section>	