<?php echo $header; ?>
<div id="main" role="main">
	<?php echo $content_top; ?>
	<ul id="breadcrumbs">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	<?php } ?>
	</ul>
	<h1>
		<?php echo $heading_title; ?>

	</h1>
<?php if ($products) { ?>
	<!--<form action="<?php //echo $action; ?>" method="post" enctype="multipart/form-data" id="wishlist">-->
		<!--<div class="cart-info">-->
		<div class="wishlist-product">
			<ul>
				<?php foreach ($products as $product) { ?>
				<li style="clear: both;" id='product-container-<?php echo $product['product_id']; ?>'>
					<div class="image"><?php if ($product['thumb']) { ?>
						<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
						<?php } ?>
					</div>
					<div class="name">
						<br />
						<br />
						<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
						<div>
						<?php echo $product['model']; ?>
						</div>
						<div>
							<?php echo $product['stock']; ?>
						</div>
						<?php if ($product['price']) { ?>
						<div class="price">
						  <?php if (!$product['special']) { ?>
						  <?php echo $product['price']; ?>
						  <?php } else { ?>
						  <s><?php echo $product['price']; ?></s> <b><?php echo $product['special']; ?></b>
						  <?php } ?>
						</div>
						<?php } ?>

						<?php if (defined('VERSION') && (version_compare(VERSION, '1.5.2', '<') == true)) { ?>
						<form action="index.php?route=account/wishlist" method="post">
							<input type="hidden" name="remove[]" value="<?php echo $product['product_id']; ?>" />
							<input style="clear: none; width: 49%; float:left; margin-left: 2%;" type="submit" value="<?php echo $button_remove; ?>" class="remove-button" id="product-id-<?php echo $product['product_id']; ?>" />
						</form>
						<?php } else { ?>
						<form action="<?php echo $product['remove']; ?>" method="post">
							<input style="clear: none; width: 49%; float:left; margin-left: 2%;" type="submit" value="<?php echo $button_remove; ?>" />
						</form>
						<?php } ?>

					</div>

<?php if (defined('VERSION') && (version_compare(VERSION, '1.5.2', '<') == true)) { ?>
					<!--<div class="remove"><input type="checkbox" name="remove[]" value="<?php //echo $product['key']; ?>" /> <?php //echo $column_remove; ?></div>-->
<?php } else { ?>
					<!--<div class="remove"><a href="<?php //echo $product['remove']; ?>"><?php //echo $button_remove; ?></a></div>-->
<?php } ?>

				</li>
				<?php } ?>

			</ul>
		</div>
		<!--<input type="submit" value="<?php //echo $button_update; ?>" />-->
	<!--</form>-->

<div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_continue; ?></span></a></div>
  </div>
	<?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_continue; ?></span></a></div>
  </div>
  <?php } ?>
	<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>