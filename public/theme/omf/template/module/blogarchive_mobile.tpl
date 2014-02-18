<div class="box blogModule <?php echo $classSuffix; ?>">
   <div class="box-heading"><span><?php echo $heading_title; ?></span></div>
   <div class="box-content">
      <div class="blogArchives">
         <?php if ($archives) { ?>
            <ul id="blogArchive" class="blogYear">
            <?php foreach ($archives as $archive) { ?>
               <li><a><?php echo $archive['value']; ?></a>
               
                  <?php if ($archive['months']) { ?>
                     <ul class="blogMonth">
                     <?php foreach ($archive['months'] as $month) { ?>
                        <li><a><?php echo $month['value']; ?></a>
                        
                           <?php if ($month['articles']) { ?>
                              <ul>
                              <?php foreach ($month['articles'] as $article) { ?>
                                 <li><a href="<?php echo $article['link']; ?>" title="<?php echo $article['title']; ?>"><?php echo $article['title']; ?></a></li>
                              <?php } ?>
                              </ul>
                           <?php } ?>
                           
                        </li>
                     <?php } ?>
                     </ul>
                  <?php } ?>
                  
               </li>
            <?php } ?>
            </ul>
         <?php } ?>
      </div>
   </div>
</div>
<script type="text/javascript"><!--
$('#blogArchive a').click(function() {
   $('+ ul', this).slideToggle();
});
$('.blogYear > li > a, .blogMonth > li > a').click(function() {
   $(this).toggleClass('blogActive');
});
//--></script>