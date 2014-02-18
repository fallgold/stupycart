<div class="box blogModule <?php echo $classSuffix; ?>">
   <div class="box-heading"><span><?php if ($catTitle) { echo $catTitle; } else { echo $heading_title; } ?> </span></div>
   <div class="box-content">
      <ul class="popularArticles">
      <?php foreach ($articles as $article) { ?>
         <li>
            <?php if ($article['titlePos'] == 'top') { ?>
               <a href="<?php echo $article['link']; ?>" title="<?php echo $article['title']; ?>" class="title"><?php echo $article['title']; ?></a>
            <?php } ?>
            <?php if ($article['image']) { ?>
               <a href="<?php echo $article['link']; ?>" title="<?php echo $article['title']; ?>" class="image"> <img src="<?php echo $article['image']; ?>" alt="<?php echo $article['title']; ?>"/></a>
            <?php } ?>
            <?php if (!$article['titlePos']) { ?>
               <a href="<?php echo $article['link']; ?>" title="<?php echo $article['title']; ?>" class="title"><?php echo $article['title']; ?></a>
            <?php } ?>
            <span class="info"><?php echo $article['created']; ?></span>
            <p><?php echo $article['description']; ?></p>
         </li>
      <?php } ?>
      </ul>
   </div>
   <div class="box-footer"><span></span></div>
</div>
