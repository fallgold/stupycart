
/\$this\->load\->model(['"]\([^\/]*\)\/\([^\/]*\)['"])/ {
# aaa/bbb => Aaa\Bbb
	s//$this->model_\1_\2 = new \\Stupycart\\Common\\Models\\Admin\\\u\1\\\u\2()/g;
# aaa/bbb_ccc => AAA\BbbCcc
	s/new \\Stupycart\(.*\)_\(.\)/new \\Stupycart\1\u\2/g 
# aaa_bbb/ccc_ddd => AAABbb\CccDdd
	s/new \\Stupycart\(.*\)_\(.\)/new \\Stupycart\1\u\2/g 
# aaa_bbb/ccc_ddd_eee => AAABbb\CccDddEee
	s/new \\Stupycart\(.*\)_\(.\)/new \\Stupycart\1\u\2/g 
}

# dynamic model name
s/\$this\->load\->model(['"]\([^\/]*\)\/['"]\s*\.\s*\([^)]*\));/$_model_class_name = "\\\\Stupycart\\\\Common\\\\Models\\\\Admin\\\\\u\1\\\\". ucfirst(\\Phalcon\\Text::camelize(\2));\n\t\t$this->{'model_\1_'. \2} = new $_model_class_name;/g;

# not compatible with Phalcon\Mvc\ModelInterface::update
s/model_checkout_order\->update(/model_checkout_order->updateOrder(/g

# new Libs
s/new \(Affiliate\|Cache\|Captcha\|Cart\|Categorizr\|Config\|Currency\|Customer\|Document\|Encryption\|Image\|Language\|Length\|Log\|Mail\|Pagination\|Request\|Response\|Session\|Tax\|Template\|Url\|User\|Weight\)/new \\Libs\\Opencart\\\1/g


# sale\return special, return is php key word
s/Sale\\Return/Sale\\ReturnModel/g
