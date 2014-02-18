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
	<p><?php echo $text_account_already; ?></p>
  
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="register">
		<fieldset>
			<h2><?php echo $text_your_details; ?></h2>
			<ul>
				<li>
					<label for="firstname"><span class="required">*</span> <?php echo $entry_firstname; ?></label>
					<input type="text" id="firstname" name="firstname" value="<?php echo $firstname; ?>" />
					<?php if ($error_firstname) { ?>
					<span class="s-error"><?php echo $error_firstname; ?></span>
					<?php } ?>
				</li>				
				<li>
					<label for="lastname"><span class="required">*</span> <?php echo $entry_lastname; ?></label>
					<input type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>" />
					<?php if ($error_lastname) { ?>
					<span class="s-error"><?php echo $error_lastname; ?></span>
					<?php } ?>
				</li>
				<li>
					<label for="email"><span class="required">*</span> <?php echo $entry_email; ?></label>
					<input type="email" id="email" name="email" value="<?php echo $email; ?>" />
					<?php if ($error_email) { ?>
					<span class="s-error"><?php echo $error_email; ?></span>
					<?php } ?>
				</li>
				<li>
					<label for="telephone"><span class="required">*</span> <?php echo $entry_telephone; ?></label>
					<input type="text" id="telephone" name="telephone" value="<?php echo $telephone; ?>" />
					<?php if ($error_telephone) { ?>
					<span class="s-error"><?php echo $error_telephone; ?></span>
					<?php } ?>
				</li>
			</ul>
			<input type="hidden" id="fax" name="fax" value="<?php echo $fax; ?>" /> 			
		</fieldset>
	
		<fieldset>
			<h2><?php echo $text_your_address; ?></h2> 			
			<input type="hidden" id="company" name="company" value="<?php echo $company; ?>" />
			<ul>
			<?php if (defined('VERSION') && (version_compare(VERSION, '1.5.3', '>=') == true)) { ?>
				<li style="display: <?php echo (count($customer_groups) > 1 ? 'block' : 'none'); ?>;">
				<?php if (defined('VERSION') && (version_compare(VERSION, '1.5.3', '==') == true)) { ?>
					<label for="customer_group_id"><?php echo $entry_account; ?></label>
				<?php } else { ?>
					<label for="customer_group_id"><?php echo $entry_customer_group; ?></label>
				<?php } ?>
					<select id="customer_group_id" name="customer_group_id">
					<?php foreach ($customer_groups as $customer_group) { ?>
					<?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
					<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
					<?php } ?>
					<?php } ?>
					</select>
				</li>         
				<li id="company-id-display">
					<label for="company_id"><span id="company-id-required" class="required">*</span> <?php echo $entry_company_id; ?></label>
					<input type="text" name="company_id" value="<?php echo $company_id; ?>" />
					<?php if ($error_company_id) { ?>
					<span class="error"><?php echo $error_company_id; ?></span>
					<?php } ?>
				</li>
				<li id="tax-id-display">
					<label for="tax_id"><span id="tax-id-required" class="required">*</span> <?php echo $entry_tax_id; ?></label>
					<input type="text" name="tax_id" value="<?php echo $tax_id; ?>" />
					<?php if ($error_tax_id) { ?>
					<span class="error"><?php echo $error_tax_id; ?></span>
					<?php } ?>
				</li>
			<?php } ?>

				<li>
					<label for="address_1"><span class="required">*</span> <?php echo $entry_address_1; ?></label>
					<input type="text" id="address_1" name="address_1" value="<?php echo $address_1; ?>" />
					<?php if ($error_address_1) { ?>
					<span class="s-error"><?php echo $error_address_1; ?></span>
					<?php } ?>
				</li>
				<li>
					<label for="address_2"><?php echo $entry_address_2; ?></label>
					<input type="text" name="address_2" value="<?php echo $address_2; ?>" />
				</li>
				<li>
					<label for="city"><span class="required">*</span> <?php echo $entry_city; ?></label>
					<input type="text" id="city" name="city" value="<?php echo $city; ?>" />					
					<?php if ($error_city) { ?>
					<span class="s-error"><?php echo $error_city; ?></span>
					<?php } ?>
				</li>
				<li>
					<label for="postcode"><span id="postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></label>
					<input type="text" name="postcode" value="<?php echo $postcode; ?>" />
					<?php if ($error_postcode) { ?>
					<span class="s-error"><?php echo $error_postcode; ?></span>
					<?php } ?>
				</li>
				<li>
					<label for="country_id"><span class="required">*</span> <?php echo $entry_country; ?></label>
					<select name="country_id" >
						<option value=""><?php echo $text_select; ?></option>
						<?php foreach ($countries as $country) { ?>
						<?php if ($country['country_id'] == $country_id) { ?>
						<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>			
					<?php if ($error_country) { ?>
					<span class="s-error"><?php echo $error_country; ?></span>
					<?php } ?>
				</li>
				<li>
					<label for="zone_id"><span class="required">*</span> <?php echo $entry_zone; ?></label>
					<select name="zone_id" >					
					</select>
					<?php if ($error_zone) { ?>
					<span class="s-error"><?php echo $error_zone; ?></span>
					<?php } ?>					
				</li>
			</ul>
		</fieldset>
		
		<fieldset>	
			<h2><?php echo $text_your_password; ?></h2>
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
		</fieldset>
		<fieldset>
			<h2><?php echo $text_newsletter; ?></h2>
			<ul>
				<li>
				<label for="newsletter"><?php echo $entry_newsletter; ?></label>
				<?php if ($newsletter == 1) { ?>
					<label for="newsletter">
						<input type="radio" name="newsletter" value="1" checked="checked" />
						<?php echo $text_yes; ?>
					</label>
					<label for="newsletter">
						<input type="radio" name="newsletter" value="0" />
						<?php echo $text_no; ?>
					</label>
				<?php } else { ?>
					<label for="newsletter">
						<input type="radio" name="newsletter" value="1" />
						<?php echo $text_yes; ?>
					</label>
					<label for="newsletter">
						<input type="radio" name="newsletter" value="0" checked="checked" />
						<?php echo $text_no; ?>
					</label>
				<?php } ?>
				</li>
		  </ul>
		</fieldset>
		<?php if ($text_agree) { ?>
		<fieldset>
			<ul>
				<li>
					<label for="agree">
						<?php echo $text_agree; ?>
						<?php if ($agree) { ?>
						<input type="checkbox" name="agree" value="1" checked="checked" />
						<?php } else { ?>
						<input type="checkbox" name="agree" value="1" />
						<?php } ?>
					</label>
				</li>
			</ul>
		</fieldset>
		<?php } ?>
		<input type="submit" value="<?php echo $button_continue; ?>" />
	</form>
  <?php echo $content_bottom; ?>
</div>

<script type="text/javascript"><!--
$(document).ready(function() {
<?php if (defined('VERSION') && (version_compare(VERSION, '1.5.3', '>=') == true)) { ?>
		$('select[name=\'customer_group_id\']').bind('change', function() {
		var customer_group = [];
		
	<?php foreach ($customer_groups as $customer_group) { ?>
		customer_group[<?php echo $customer_group['customer_group_id']; ?>] = [];
		customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_display'] = '<?php echo $customer_group['company_id_display']; ?>';
		customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_required'] = '<?php echo $customer_group['company_id_required']; ?>';
		customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_display'] = '<?php echo $customer_group['tax_id_display']; ?>';
		customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_required'] = '<?php echo $customer_group['tax_id_required']; ?>';
	<?php } ?>	

		if (customer_group[this.value]) {
			if (customer_group[this.value]['company_id_display'] == '1') {
				$('#company-id-display').show();
			} else {
				$('#company-id-display').hide();
			}
			
			if (customer_group[this.value]['company_id_required'] == '1') {
				$('#company-id-required').show();
			} else {
				$('#company-id-required').hide();
			}
			
			if (customer_group[this.value]['tax_id_display'] == '1') {
				$('#tax-id-display').show();
			} else {
				$('#tax-id-display').hide();
			}
			
			if (customer_group[this.value]['tax_id_required'] == '1') {
				$('#tax-id-required').show();
			} else {
				$('#tax-id-required').hide();
			}	
		}
	});

	$('select[name=\'customer_group_id\']').trigger('change');	

	$('select[name=\'country_id\']').bind('change', function() {
		$.ajax({
			url: 'index.php?route=account/register/country&country_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>').insertAfter($('select[name=\'country_id\']'));
			},
			complete: function() {
				$('.wait').remove();
			},			
			success: function(json) {
				if (json['postcode_required'] == '1') {
					$('#postcode-required').show();
				} else {
					$('#postcode-required').hide();
				}
				
				html = '<option value=""><?php echo $text_select; ?></option>';
				
				if (json['zone'] != '') {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';
						
						if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
							html += ' selected="selected"';
						}
		
						html += '>' + json['zone'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
				}
				
<?php } else { ?>
		$('select[name=\'country_id\']').bind('change', function() {
		$.ajax({
			url: 'index.php?route=account/register/zone&country_id=' + this.value,
			dataType: 'html',
			beforeSend: function() {
				$('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>').insertAfter($('select[name=\'country_id\']'));
			},
			complete: function() {
				$('.wait').remove();
			},			
			success: function(html) {
<?php } ?>	
				$('select[name=\'zone_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('select[name=\'country_id\']').trigger('change');
});
//--></script>
<?php echo $footer; ?>