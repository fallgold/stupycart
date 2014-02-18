<div class="box blogModule blogTags <?php echo $classSuffix; ?>">
   <div class="box-heading"><span><?php echo $heading_title; ?> </span></div>
   <div class="box-content">
      <?php if ($tagsData) {
         echo $tagsData;
      } else {
         echo $text_notags;
      } ?>
   </div>
   <div class="box-footer"><span></span></div>
</div>
