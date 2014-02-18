			<footer>
				<?php if ((strrpos($route, "checkout") === false) &&
							   (strrpos($route, "account") === false)) { ?>
				<form action="index.php?route=product/search_mobile" method="post" id="search" class="inline-form module">
					<fieldset >
						<?php
						$field_name = (defined('VERSION') && (version_compare(VERSION, '1.5.5', '>=') == true)) ? 'search' : 'filter_name';
						if ($filter_name) { ?>
						<input type="search" name="<?php echo $field_name; ?>" placeholder="<?php echo $filter_name; ?>" />
						<?php } else { ?>
						<input type="search" name="<?php echo $field_name; ?>"  placeholder="<?php echo $text_search; ?>" />
						<?php } ?>
						<input type="submit" value="<?php echo $text_search_link; ?>" />
					</fieldset>
				</form>
				<?php } ?>
				<?php if (isset($categories)) { ?>
				<nav id="secondary">
					<ul class="nav">
						<?php $i = 0; ?>
						<?php foreach ($categories as $category) { ?>
						<li>
							<a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
						</li>
						<?php  if (++$i == $config_mobile_front_page_cat_list) break; ?>
						<?php } ?>
					</ul>
					<a href="<?php echo $all_categories; ?>" class="stack"><?php echo $text_all_categories; ?></a>
					<?php if (mysql_num_rows( mysql_query("SHOW TABLES LIKE '". DB_PREFIX ."blog_setting'")) == '1') { ?>
            		<a href="<?php echo $this->url->link('blog/category/home'); ?>"> <?php echo $text_blog; ?> </a>
         			<?php } ?>
				</nav>
				<?php } ?>
				<?php if (strrpos($route, "checkout/checkout") === false) { ?>
				<nav id="primary">
					<ul class="nav">
						<li><a href="<?php echo $home; ?>" class="n-home"><?php echo $text_home; ?></a></li>
						<li><a href="<?php echo $info; ?>" class="n-info"><?php echo $text_information; ?></a>
							<?php if (!empty($informations)) { ?>
						    <ul>
								<?php foreach ($informations as $information) { ?>
								<li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
								<?php } ?>
							</ul>
							<?php } ?>
						</li>
						<li><a href="<?php echo $account; ?>" class="n-account"><?php echo $text_account; ?></a>
							<ul>
								<li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
								<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
							</ul>
						</li>
						<li><a href="<?php echo $contact; ?>" class="n-contact"><?php echo $text_contact; ?></a>
							<ul>
								<li><a href="tel:<?php echo $telephone; ?>"><?php echo $text_call; ?></a></li>
								<li><a href="<?php echo $contact; ?>"><?php echo $text_enquiry; ?></a></li>
								<li><a href="http://maps.google.com/maps?q=<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
							</ul>
						</li>
					</ul>
				</nav>
<?php if (defined('VERSION') && (version_compare(VERSION, '1.5.2', '<') == true)) { ?>
				<aside id="settings">
					<?php if (count($languages) > 1) {  ?>
					<form action="<?php echo $action; ?>" method="post" id="language" enctype="multipart/form-data">
							<?php /*<label for="language_code"><?php echo $text_language; ?></label>*/?>
							<select name="language_code">
							<?php foreach ($languages as $language) { ?>
							<?php /*<button type="submit" name="language_code" value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></button> */?>
								<?php if($language['code'] == $language_code) { ?>
								<option selected="selected" disabled="disabled" value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
								<?php } else { ?>
								<option  value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
								<?php } ?>
							<?php } ?>
							</select>
							<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
					</form>
					<?php } ?>

					<?php if (count($currencies) > 1) { //Currencies ?>
					<form action="<?php echo $action; ?>" method="post" id="currency" enctype="multipart/form-data">
						<!-- <label for="currency_code"><?php echo $text_currency; ?></label> -->
							<select name="currency_code">
							<?php foreach ($currencies as $currency) { ?>
								<?php if ($currency['code'] == $currency_code) { ?>
									<?php if ($currency['symbol_left']) { ?>
								<option  selected="selected" disabled="disabled" value="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_left'] . ' ' . $currency['title'];  ?></option>
									<?php } else { ?>
								<option  selected="selected" disabled="disabled" value="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_right'] . ' ' . $currency['title'];  ?></option>
									<?php } ?>
								<?php } else { ?>
									<?php if ($currency['symbol_left']) { ?>
								<option value="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_left'] . ' ' . $currency['title'];  ?></option>
									<?php } else { ?>
								<option value="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_right'] . ' ' . $currency['title'] ;  ?></option>
									<?php } ?>
								<?php } ?>
							<?php } ?>
							</select>
							<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
					</form>
				</aside>
				<?php } ?>
<?php } else { ?>
				<aside id="settings">
					<?php if(isset($language)) echo $language; ?>
					<?php if(isset($currency)) echo $currency; ?>
				</aside>
<?php } ?>
				<span id="welcome">
					<?php if (!$logged) { ?>
						<?php echo $text_welcome; ?>
					<?php } else { ?>
						<?php echo $text_logged; ?>
					<?php } ?>
				</span>
				<?php } ?>
				<ul class="tools">
					<li><a href="<?php echo $_SERVER['REQUEST_URI'] ?>#header"><?php echo $text_top; ?></a></li>
					<li><?php echo $text_view; ?> <?php echo $text_mobile; ?> / <a href="<?php if (strpos($_SERVER['QUERY_STRING'], 'view=mobile') === false) {
									echo $_SERVER['REQUEST_URI'] . (empty($_SERVER['QUERY_STRING']) ? '?view=desktop' : '&view=desktop');
								} else {
									echo str_replace('view=mobile', 'view=desktop', $_SERVER['REQUEST_URI']);
								} ?>"><?php echo $text_standard; ?></a></li>
				</ul>
<p style="text-align:center; font-size: smaller; padding-top: 2em">Powered by <a href="http://www.omframework.com">OMFramework 1.6.5 Lite</a></p>			</footer>
		</div>
		<?php echo $google_analytics; ?>
		<?php if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != '') { ?>
		<script src="catalog/view/theme/omf/js/jq.mobi.min.js" type="text/javascript" ></script>
		<?php } else { ?>
		<!--<script src="http://cdn.jqmobi.com/jq.mobi.min.js" type="text/javascript" ></script>-->
		<script src="catalog/view/theme/omf/js/jq.mobi.min.js" type="text/javascript" ></script>
		<?php } ?>
		<script>window.$ = window.jq;</script>
		<script>(function($,d){$.each(readyQ,function(i,f){$(f)});$.each(bindReadyQ,function(i,f){$(d).bind("ready",f)})})(jq, document)</script>
		<?php if (file_exists(DIR_TEMPLATE . $this->config->get('config_mobile_theme') . '/js/script.js')) { ?>
		<script type="text/javascript"  src="<?php echo 'catalog/view/theme/' . $this->config->get('config_mobile_theme') ?>/js/script.js"></script>
		<?php } else {?>
		<script type="text/javascript" src="catalog/view/theme/omf/js/script.js"></script>
		<?php } ?>
	</body>
	</body>
