					<?php if (count($languages) > 1) {  ?>
					<form action="<?php echo $action; ?>" method="post" id="language" enctype="multipart/form-data">
						<?php /*<label for="language_code"><?php echo $text_language; ?></label>*/?>
						<select name="language_code">
						<?php foreach ($languages as $language) { ?>													
							<?php if($language['code'] == $language_code) { ?>
							<option selected="selected" disabled="disabled" value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
							<?php } else { ?>
							<option  value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
							<?php } ?>
						<?php } ?>						
						</select>
						<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />						
					</form>
					<?php } ?>				