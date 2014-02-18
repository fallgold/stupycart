<?php echo $header; ?>
<div id="main" role="main">
	<?php echo $content_top; ?>
	<ul id="breadcrumbs">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	<?php } ?>
	</ul>
	<h1><?php echo $heading_title; ?></h1>
	<?php if ($orders) { ?>
	<ul>
		<?php foreach ($orders as $order) { ?>
		<li class="order-item">
			<h2 class="order-id"><?php echo $text_order_id; ?> #<?php echo $order['order_id']; ?></h2>
			<p class="order-status"><strong title="<?php echo $text_status; ?>"><?php echo $order['status']; ?></strong></p>
			<table class="order-content">				
				<tr><th><?php echo $text_date_added; ?></th><td><?php echo $order['date_added']; ?></td></tr>
				<tr><th><?php echo $text_products; ?></th><td><?php echo $order['products']; ?></td></tr>
				<tr><th><?php echo $text_customer; ?></th><td><?php echo $order['name']; ?></td></tr>
				<tr><th><?php echo $text_total; ?></th><td><?php echo $order['total']; ?></td></tr>
			</table>
			<a href="<?php echo $order['href']; ?>" class="order-info button"><?php echo $button_view; ?></a> 
		</li>
		<?php } ?>
	</ul>
	<div class="pagination"><?php echo $pagination; ?></div>
	<?php } else { ?>
	<div class="content"><?php echo $text_empty; ?></div>
	<?php } ?>
	<div class="buttons">
		<a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a>	
	</div>
	<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>