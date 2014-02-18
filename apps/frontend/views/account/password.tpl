{include '../common/header.tpl'}{include '../common/column_left.tpl'}{include '../common/column_right.tpl'}
<div id="content">{include '../common/content_top.tpl'}
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <h2><?php echo $text_password; ?></h2>
    <div class="content">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_password; ?></td>
          <td><input type="password" name="password" value="<?php echo $password; ?>" />
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_confirm; ?></td>
          <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
            <?php if ($error_confirm) { ?>
            <span class="error"><?php echo $error_confirm; ?></span>
            <?php } ?></td>
        </tr>
      </table>
    </div>
    <div class="buttons">
      <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
      <div class="right"><input type="submit" value="<?php echo $button_continue; ?>" class="button" /></div>
    </div>
  </form>
  {include '../common/content_bottom.tpl'}</div>
{include '../common/footer.tpl'}