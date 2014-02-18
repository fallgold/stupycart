<?php if ($comments) { ?>
   <?php $class = 'odd'; ?>
   <ul class="commentList">
   <?php foreach ($comments as $comment) { ?>
      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
      <li class="<?php echo $class; ?>" id="comment-<?php echo $comment['comment_id'];?>" itemprop="comment" itemscope itemtype="http://schema.org/UserComments">
  
         <div class="name">
         <?php if($comment['website']) { ?>
            <a href="<?php echo $comment['website'] ?>"><span itemprop="creator"><?php echo $comment['name']; ?></span></a>
         <?php } else { echo $comment['name']; }?>
         </div>
         <div class="created">
         <?php if ($comment['badge']) { ?><span class="badge <?php echo strtolower($comment['badge']); ?>"><?php echo $comment['badge']; ?></span><?php } ?>
            <span itemprop="commentTime"><?php echo $comment['created']; ?></span>
         </div>
         <p itemprop="commentText"><?php echo $comment['content']; ?></p>
         <a id="replyButton" class="button" onclick='return addComment.moveForm("comment-<?php echo $comment['comment_id']; ?>", "<?php echo $comment['comment_id']; ?>", "commentRespond")'><?php echo $text_reply; ?></a>

         <?php if ($comment['replyComments']) { ?>
            <?php $class2 = 'odd'; ?>
            <ul class="commentList replyComment">
            <?php foreach ($comment['replyComments'] as $replyComment) { ?>
               <?php $class2 = ($class2 == 'even' ? 'odd' : 'even'); ?>
               <li class="<?php echo $class2; ?>">
                  <div class="name">
                  <?php if($replyComment['website']) { echo '<a href="'.$replyComment['website'].'">'.$replyComment['name'].'</a>'; } else { echo $replyComment['name']; }?>
                  </div>
                  <div class="created">
                     <?php if ($replyComment['badge']) { ?><span class="badge <?php echo strtolower($replyComment['badge']); ?>"><?php echo $replyComment['badge']; ?></span><?php } ?>
                  <?php echo $replyComment['created']; ?>
                  </div>
                  <p><?php echo $replyComment['content']; ?></p>
               </li>
            <?php } ?>
            </ul>
         <?php } ?>
         
      </li>
   <?php } ?>
   </ul>
   <div class="pagination"><?php echo $pagination; ?></div>
<?php } else { ?>
   <div class="commentEmpty"><?php echo $text_no_comments; ?></div>
<?php } ?>