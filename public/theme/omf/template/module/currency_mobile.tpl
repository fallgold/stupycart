					<?php if (count($currencies) > 1) { //Currencies ?>
					<form action="<?php echo $action; ?>" method="post" id="currency" enctype="multipart/form-data">					
						<!-- <label for="currency_code"><?php echo $text_currency; ?></label> -->
						<select name="currency_code">
						<?php foreach ($currencies as $currency) { ?>
							<?php if ($currency['code'] == $currency_code) { ?>
								<?php if ($currency['symbol_left']) { ?>													
							<option  selected="selected" disabled="disabled" value="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_left'] . ' ' . $currency['title'];  ?></option>
								<?php } else { ?>													
							<option  selected="selected" disabled="disabled" value="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_right'] . ' ' . $currency['title'];  ?></option>
								<?php } ?>
							<?php } else { ?>
								<?php if ($currency['symbol_left']) { ?>					
							<option value="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_left'] . ' ' . $currency['title'];  ?></option>
								<?php } else { ?>						
							<option value="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_right'] . ' ' . $currency['title'] ;  ?></option>
								<?php } ?>
							<?php } ?>
						<?php } ?>		
						</select>						
						<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
					</form>
					<?php } ?>