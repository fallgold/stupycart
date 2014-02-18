<?php echo $header; ?>
<div id="main" role="main">
   <?php echo $content_top; ?>
   <div class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
      <?php } ?>
   </div>
   
   <h1><?php echo $heading_title; ?></h1>
   <div id="blogCatArticles" class="<?php echo $suffix; ?>">
      <?php if ($categories || $catDescription) { ?>
         <div id="blogCategory">
            <?php if ($catDescription) { ?>
               <?//php if ($catImage) { ?>
                  <!-- <img class="imageFeatured" src="<?php echo $catImage; ?>" alt="<?php echo $heading_title; ?>"/> -->
               <?//php } ?>
               <?php echo $catDescription; ?>
            <?php } ?>
            <?php if ($categories) { ?>
               <h2><?php echo $text_subcategory; ?></h4>
               <ul id="secondary" class="nav">
                  <?php foreach ($categories as $category) { ?>
                     <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
                  <?php } ?>
               </ul>
            <?php } ?>
         </div>
      <?php } ?>
   
      <?php if ($articles) { ?>
         <?php foreach ($articles as $article) { ?>
            <article class="blogArticle" itemscope itemtype="http://schema.org/Article">
               <h3 itemprop="name"><a href="<?php echo $article['readmore']; ?>" title="<?php echo $article['title']; ?>" itemprop="url"><?php echo $article['title']; ?></a></h3>
               <?php if ($article['art_infoName'] || $article['art_infoCategory'] || $article['art_infoDate']) { ?>
                  <span class="articleInfo">
                     <?php echo $article['art_infoName'] . $article['art_infoCategory'] . $article['art_infoDate']; ?>
                     <?php if ($article['art_infoName'] || $article['art_infoCategory'] || $article['art_infoDate']) { ?>. <?php }?>
                  </span>
               <?php }?>
               <section class="articleContent">
                  <?php if ($article['image']) { ?>
                     <a href="<?php echo $article['readmore']; ?>" title="<?php echo $article['title']; ?>" itemprop="url"><img class="imageFeatured" src="<?php echo $article['image']; ?>" alt="<?php echo $article['title']; ?>" itemprop="image" /></a>
                  <?php } ?>
                  <span itemprop="description"><?php echo $article['description']; ?></span>
                  <div class="readMore">
                     <?php if ($article['comments'] && $article['art_infoComment']) { ?>
                        <span class="comment"><a href="<?php echo $article['comments_href']; ?>#articleComments" title="<?php echo $article['comments']; ?>"><?php echo $article['comments']; ?></a></span>
                     <?php } ?>
                     <span class="more"><a href="<?php echo $article['readmore']; ?>" title="<?php echo $article['title']; ?>"><?php echo $read_more; ?></a></span>
                  </div>
               </section>
            </article>
         <?php } ?>
      <?php } ?>
   </div>
   
   <div class="pagination"><?php echo $pagination; ?></div>
   <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?> 