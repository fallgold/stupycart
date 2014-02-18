<?php echo $header; ?>
<div id="main" role="main">
   <?php echo $content_top; ?>

   <div class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
      <?php } ?>
   </div>

   <article class="blogArticle" itemscope itemtype="http://schema.org/Article">
      <h1 itemprop="headline"><?php echo $title; ?></h1>
      <?php if ($art_infoName || $art_infoCategory || $art_infoDate || $art_infoComment) { ?>
         <span class="articleInfo">
            <?php echo $art_infoName . $art_infoCategory . $art_infoDate; ?>
            <?php if ($art_infoName || $art_infoCategory || $art_infoDate) { ?>. <?php }?>
             <?php if ($art_infoComment) { ?><?php echo $comments; ?>.<?php }?>
         </span>
      <?php }?>
      <!-- <a href="<?php echo $link; ?>" itemprop="url" style="display:none" title=""></a> -->

      <section class="articleContent" itemprop="articleBody">
         <?php echo $description; ?>
      </section>

      <?php if ($tags || $art_infoUpdate) { ?>

            <?php if ($tags) { ?>
               <span class="tags"><?php echo $text_tags; ?>   <?php echo $tags; ?></span>
            <?php } ?>
            <?php if ($art_infoUpdate) { ?>
               <span class="updateInfo"><?php echo $text_update ?> <span itemprop="dateModified"><?php echo $modified; ?></span></span>
            <?php } ?>

      <?php } ?>

      <?php if ($socMedia || $artRelateds || $prodRelateds) { ?>
         <?php if ($socMedia && !$socMedCode) { ?>
            <div class="addthis_toolbox addthis_default_style">
               <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
               <a class="addthis_button_tweet"></a>
               <a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
            </div>
         <?php } else { ?>
            <?php echo $socMedCode; ?>
         <?php } ?>

         <?php if ($artRelateds) { ?>
            <section id="articleRelated">
               <h4><?php echo $text_related; ?></h4>
               <?php if (count($artRelateds) <= 5) { ?>
                  <ul>
                  <?php foreach ($artRelateds as $artRelated) { ?>
                     <li><a href="<?php echo $this->url->link('blog/article', 'article_id=' . $artRelated['article_id']); ?>"><?php echo $artRelated['title']; ?></a></li>
                  <?php } ?>
                  </ul>
               <?php } else { ?>
                  <?php for ($i = 0; $i < count($artRelateds);) { ?>
                     <ul>
                     <?php $j = $i + ceil(count($artRelateds) / 2); ?>
                     <?php for (; $i < $j; $i++) { ?>
                        <?php if (isset($artRelateds[$i])) { ?>
                           <li><a href="<?php echo $this->url->link('blog/article', 'article_id=' . $artRelateds[$i]['article_id']); ?>"><?php echo $artRelateds[$i]['title']; ?></a></li>
                        <?php } ?>
                     <?php } ?>
                     </ul>
                  <?php } ?>
               <?php } ?>
            </section>
         <?php } ?>
         <?php if ($prodRelateds) { ?>
            <section id="productRelated">
               <h4><?php echo $text_related_product; ?></h4>
               <ul class="product-list">
               <?php foreach ($prodRelateds as $prodRelated) { ?>
                  <li>
                  <?php if ($prodRelated['thumb']) { ?>
                     <a href="<?php echo $prodRelated['href']; ?>"><img src="<?php echo $prodRelated['thumb']; ?>" alt="<?php echo $prodRelated['name']; ?>" /></a>
                  <?php } ?>
                  <a href="<?php echo $prodRelated['href']; ?>"><?php echo $prodRelated['name']; ?></a>
                  <?php if ($prodRelated['price']) { ?>
                  <div class="price">
                        <?php if (!$prodRelated['special']) { ?>
                           <?php echo $prodRelated['price']; ?>
                        <?php } else { ?>
                           <span class="price-old"><?php echo $prodRelated['price']; ?></span> <span class="price-new"><?php echo $prodRelated['special']; ?></span>
                        <?php } ?>
                  </div>
                  <?php } ?>
                  <?php if ($prodRelated['rating']) { ?>
                     <img src="catalog/view/theme/default/image/stars-<?php echo $prodRelated['rating']; ?>.png" alt="<?php echo $prodRelated['reviews']; ?>" />
                  <?php } ?>
               </li>
               <?php } ?>
               </ul>
            </section>
         <?php } ?>
      <?php } ?>

      <?php echo $content_bottom; ?>

      <a name="articleComments"></a>
      <?php if ($commentStatus) { ?>
         <section id="articleComments">
            <h4><?php echo $heading_comment; ?> <span><?php echo $replies; ?></span></h4>
            <div id="comments"></div>

            <div id="commentRespond">
               <h4 id="commentTitle"><?php echo $text_postComment; ?></h4>
               <h4 id="replyTitle"><?php echo $text_postReply; ?></h4>
               <a id="cancelCommentReply" href="#commentRespond" style="display:none;">[Cancel Reply]</a>
               <form id="commentInput">
                  <ul>
                     <li>
                        <label for="name"><span class="required">*</span> <?php echo $entry_name; ?></label>
                        <?php if ($customerID) { ?>
                        <input type="text" name="commentName" value="<?php echo $customerName; ?>" class="inputName" tabindex="1" disabled="disabled" />
                        <?php } else { ?>
                        <input type="text" name="commentName" value="" class="inputName" tabindex="1" />
                        <?php } ?>
                     </li>
                     <li>
                        <label for="email"><span class="required">*</span> <?php echo $entry_email; ?><span class="note"><?php echo $text_note_publish; ?></span></label>
                        <?php if ($customerID) { ?>
                        <input type="email" name="commentMail" value="<?php echo $customerMail; ?>" class="inputMail" tabindex="2" disabled="disabled" />
                        <?php } else { ?>
                        <input type="text" name="commentMail" value="" class="inputMail" tabindex="2"  />
                        <?php } ?>
                     </li>
                     <li>
                        <label for="site"><?php echo $entry_site; ?><span class="note"><?php echo $text_note_website; ?></span></label>
                        <input type="url" name="commentSite" value="" tabindex="3" />
                     </li>
                     <li>
                        <label for="comment"><span class="required">*</span> <?php echo $entry_comment; ?></label>
                        <textarea name="commentContent" cols="50" rows="8" class="inputContent" tabindex="4" ></textarea>
                     </li>
                     <?php if ($commentCaptha) { ?>
                     <li>
                        <label for="captcha"><span class="captchaInput"><?php echo $entry_captcha; ?></label>
                        <input type="text" name="commentCaptcha" value="" tabindex="5" />
                        <span class="captchaImage">
                           <img src="index.php?route=blog/article/captcha" alt="" id="captcha" />
                        </span>
                     </li>
                     <?php } ?>
                     <input type="hidden" name="comment_parent_id" id="parent_id" value="0" />
                     <input type="submit" id="submitComment" value="<?php echo $button_submit; ?>" />
                  </ul>
               </form>
            </div>
         </section>
      <?php } ?>
   </article>

<script src="catalog/view/theme/omf/js/jq.mobi.min.js" type="text/javascript" ></script>
<script>window.$ = window.jq;</script>

<?php if ($socMedia) { ?>
   <script type="text/javascript">var addthis_config = {"data_track_clickback":true,"ui_click": true};</script>
   <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js<?php if($pubID) { echo '#pubid=' . $pubID; } ?>"></script>
<?php } ?>

<?php if ($commentStatus) { ?>
   <script>
   $.get("index.php?route=blog/article/comment&article_id=<?php echo $article_id; ?>",function(data){
         $('#comments').html(data);
      });

   $('#comments .pagination a').bind('click', function() {
      $('#articleComments').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" />&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $text_wait; ?></div>');
      $.get(this.href,
         function(data){
            $('#comments').html(data);
         });
      setTimeout(function(){
         $('.attention').remove();
         //$('#articleComments').slideDown(2000)
      },2500);
      return false;
   });

   $('#submitComment').bind('click', function() {
      $.ajax({
         type: 'POST',
         url: 'index.php?route=blog/article/write&article_id=<?php echo $article_id; ?>',
         dataType: 'json',
         data: 'name=' + encodeURIComponent($('input[name=\'commentName\']').val()) + '&email=' + encodeURIComponent($('input[name=\'commentMail\']').val()) + '&website=' + encodeURIComponent($('input[name=\'commentSite\']').val()) + '&content=' + encodeURIComponent($('textarea[name=\'commentContent\']').val()) + '&captcha=' + encodeURIComponent($('input[name=\'commentCaptcha\']').val()) + '&customer_id=' + encodeURIComponent($('input[name=\'customer_id\']').val()) + '&parent_id=' + encodeURIComponent($('input[name=\'comment_parent_id\']').val()),
         beforeSend: function() {
            $('.success, .warning, .error').remove();
            $('#submitComment').attr('disabled', true);
            $('#commentInput').before('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" />&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $text_wait; ?></div>');
         },
         complete: function() {
            $('#submitComment').attr('disabled', false);
            $('.attention').remove();
         },
         success: function(json) {
            if (json['error']) {
               $('#commentInput').before('<div class="warning">' + json['error']['common'] + '</div>');

               if (json['error']['name']) {
                  $('.inputName').after('<span class="error">' + json['error']['name'] + '</span>');
               }
               if (json['error']['email']) {
                  $('.inputMail').after('<span class="error">' + json['error']['email'] + '</span>');
               }
               if (json['error']['content']) {
                  $('.inputContent').after('<span class="error">' + json['error']['content'] + '</span>');
               }
               if (json['error']['captcha']) {
                  $('.inputCaptcha').after('<span class="error">' + json['error']['captcha'] + '</span>');
               }
            }

            if (json['success']) {
               <?php if (!$customerID) { ?>
                  $('input[name=\'commentName\']').val('');
                  $('input[name=\'commentMail\']').val('');
               <?php } ?>
               $('input[name=\'commentSite\']').val('');
               $('textarea[name=\'commentContent\']').val('');

               $('#commentInput').before('<div class="success">' + json['success'] + '</div>');
               setTimeout(function(){
                  $('.success').remove();
                  <?php if ($autoApprove) { ?>
                     //$('#cancelCommentReply').trigger('click');
                     $.get("index.php?route=blog/article/comment&article_id=<?php echo $article_id; ?>",function(data){
                           $('#comments').html(data);
                        });
                  <?php } ?>
               },2500);
            }
         }
      });
   });
   addComment={
      moveForm:
      function(d,f,i){
         document.getElementById("commentTitle").style.display="none";
         document.getElementById("replyTitle").style.display="block";
         document.getElementById(i).style.display="block";
         var m=this,
         a,
         h=m.I(d),
         b=m.I(i),
         l=m.I("cancelCommentReply"),
         j=m.I("parent_id");

         if(!h||!b||!l||!j){
            return
         }
         m.respondId=i;
         if(!m.I("tempCommentRespond")){
            a=document.createElement("div");
            a.id="tempCommentRespond";
            a.style.display="none";
            b.parentNode.insertBefore(a,b)
         }
         h.parentNode.insertBefore(b,h.nextSibling);
         j.value=f;
         l.style.display="";
         l.onclick=function(){
            var n=addComment,e=n.I("tempCommentRespond"),o=n.I(n.respondId);
            document.getElementById(i).style.display="";
            document.getElementById("commentTitle").style.display="block";
            document.getElementById("replyTitle").style.display="none";
            if(!e||!o){
               return
            }
            n.I("parent_id").value="0";
            e.parentNode.insertBefore(o,e);
            e.parentNode.removeChild(e);
            this.style.display="none";
            this.onclick=null;
            return false
         };
         try{
            m.I("comment").focus()
         }
         catch(g){}
         return false
      }
      ,I:function(a){
         return document.getElementById(a)
      }
   };
   //--></script>
<?php } ?>
</div>
<?php echo $footer; ?>