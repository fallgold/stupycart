<div class="m-cart">
	<h2 class="cart-heading"><?php echo $heading_title; ?></h2>	
	<?php if(isset($errors)) echo '<a name="error"><span class="s-error">'. $errors .'</span></a>';?>
	<form action="index.php?route=total/voucher/calculate"  method="post" class="inline-form">		
		<label for="voucher"><?php echo $entry_voucher; ?></label>
		<input type="text" name="voucher" value="<?php echo $voucher; ?>" />				
		<input type="submit" id="button-voucher" class="button" value="<?php echo $button_apply; ?>" />						
	</form>
</div>