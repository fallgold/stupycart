<?php echo $header; ?>
<div id="main" role="main">
	<?php echo $content_top; ?>

	<ul id="breadcrumbs">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	<?php } ?>
	</ul>

	<h1><?php echo $heading_title; ?></h1>
	<?php if ($thumb || $description) { ?>
	<div class="category-info">
		<?php if ($thumb) { ?>
		<img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" />
		<?php } ?>
		<?php if ($description) { ?>
		<?php echo $description; ?>
		<?php } ?>
	</div>
	<?php } ?>
	<?php if ($categories) { ?>
	<h2><?php echo $text_refine; ?></h2>
	<?php if (count($categories) <= 5) { ?>
	<ul id="secondary" class="nav">
		<?php foreach ($categories as $category) { ?>
		<li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
		<?php } ?>
	</ul>
	<?php } else { ?>
	<?php for ($i = 0; $i < count($categories);) { ?>
	<ul id="secondary" class="nav">
		<?php $j = $i + ceil(count($categories) / 4); ?>
		<?php for (; $i < $j; $i++) { ?>
		<?php if (isset($categories[$i])) { ?>
		<li><a href="<?php echo $categories[$i]['href']; ?>"><?php echo $categories[$i]['name']; ?></a></li>
		<?php } ?>
		<?php } ?>
	</ul>
	<?php } ?>
	<?php } ?>
	<?php } ?>
	<?php if ($products) { ?>
	<ul class="product-list">
		<?php foreach ($products as $product) { ?>
		<li>
			<?php if ($product['thumb']) { ?>
			<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a>
			<?php } ?>
			<a href="<?php echo $product['href']; ?>" class="name"><?php echo $product['name']; ?></a>
			<p class="description"><?php echo $product['description']; ?> <a href="<?php echo $product['href']; ?>#tab-description"><?php echo $text_more; ?></a></p>
			<?php if ($product['price']) { ?>
			<div class="price">
				<?php if (!$product['special']) { ?>
				<?php echo $product['price']; ?>
				<?php } else { ?>
				<del class="price-old"><?php echo $product['price']; ?></del> <span class="price-new"><?php echo $product['special']; ?></span>
				<?php } ?>
				<?php if ($product['tax']) { ?>
				<br />
				<span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if ($product['rating']) { ?>
			<img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" class="rating">
			<?php } ?>

			<?php /* if ( is_null($this->config->get('config_wishlist_disabled')) or (bool)$this->config->get('config_wishlist_disabled') == false) { ?>
			<form action="index.php?route=account/wishlist/update" method="post">
				<input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>" />
				<input class="wishlist-button" id="wishlist-product-<?php echo $product['product_id']; ?>" type="submit" value="<?php echo $button_wishlist; ?>" />
			</form>
			<?php } */ ?>

			<form action="index.php?route=account/wishlist/update" method="post">
				<input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>" />
				<input class="wishlist-button" id="wishlist-product-<?php echo $product['product_id']; ?>" type="submit" value="<?php echo $button_cart; ?>" />
			</form>
		</li>
		<?php } ?>
	</ul>
	<div class="pagination"><?php echo $pagination; ?></div>
	<?php } ?>
	<?php if (!$categories && !$products) { ?>
	<p><?php echo $text_empty; ?></p>
	<div class="buttons">
		<a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a>
	</div>
	<?php } ?>
	<?php echo $content_bottom; ?>
</div>

<script>
$(document).ready(function(){
	$(".wishlist-button").on("click", function(e){

		var $this = $(e.target);
		e.preventDefault();
		var product_id = $(e.target).attr("id").replace(/wishlist-product-/, '');

		$.ajax({
			<?php /* if (defined('VERSION') && (version_compare(VERSION, '1.5.2', '<') == true)) { ?>
				url: 'index.php?route=account/wishlist/update',
			<?php } else { ?>
				url: 'index.php?route=account/wishlist/add',
			<?php } */ ?>
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				alert(json);
				$('.success, .warning, .attention, .information, .error').remove();
				if (json['redirect']) {
					location = json['redirect'];
				}
				if (json['success']) {
					var message_type = ( json.success.indexOf('Success') != -1 ? 'success' : 'warning' );
					$this.after('<div class="'+message_type+'">' + json.success + '</div>')
					//$("."+ message_type).fadeOut(1000)
					$('#cart-total').html(json['total']);
					$('#wishlist_total').html(json['total']);
				}
			}
		});
	})
});
</script>
<?php echo $footer; ?>
