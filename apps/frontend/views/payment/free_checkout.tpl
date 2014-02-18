<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({ 
		type: 'get',
		url: '{$this->url->getBaseUrl()}payment/free_checkout/confirm',
		success: function() {
			location = '<?php echo $continue; ?>';
		}		
	});
});
//--></script> 
