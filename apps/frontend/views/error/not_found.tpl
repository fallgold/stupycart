{include '../common/header.tpl'}{include '../common/column_left.tpl'}{include '../common/column_right.tpl'}
<div id="content">{include '../common/content_top.tpl'}
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div class="content"><?php echo $text_error; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  {include '../common/content_bottom.tpl'}</div>
{include '../common/footer.tpl'}