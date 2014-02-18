<div class="m-cart">
	<h2 class="cart-heading"><?php echo $heading_title; ?></h2>	
	<?php if(isset($errors)) echo '<a name="error"><span class="s-error">'. $errors .'</span></a>';?>	
	<form action="index.php?route=total/coupon/calculate"  method="post" class="inline-form">		
		<label for="coupon"><?php echo $entry_coupon; ?></label>		
		<input type="text" name="coupon" value="<?php echo $coupon; ?>" />		
		<input type="submit" id="button-coupon" class="button" value="<?php echo $button_apply; ?>" />				
	</form>
</div>