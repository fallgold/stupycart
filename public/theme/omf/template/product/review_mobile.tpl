<?php if ($reviews) { ?>
<?php foreach ($reviews as $review) { ?>
<div class="content">
	<h4><?php echo $review['author']; ?> | <img src="catalog/view/theme/default/image/stars-<?php echo $review['rating'] . '.png'; ?>" alt="<?php echo $review['reviews']; ?>" /></h4>
	<?php echo $review['date_added']; ?>
	<p><?php echo $review['text']; ?></p>
</div>
<?php } ?>
<div class="pagination"><?php echo $pagination; ?></div>
<?php } else { ?>
<div class="content"><?php echo $text_no_reviews; ?></div>
<?php } ?>
