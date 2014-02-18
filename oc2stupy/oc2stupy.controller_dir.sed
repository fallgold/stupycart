
s/class\s*Controller\(.[a-z0-9_]*\)\([A-Z][^ ]*\)\s*extends\s*Controller/\nnamespace Stupycart\\Frontend\\Controllers\\\1;\n\nclass \2Controller extends \\Stupycart\\Frontend\\Controllers\\ControllerBase/g

s/public function \([a-z0-9_]*\)\s*(/public function \1Action(/g

s/protected\s*function\s*index\s*(/public function indexAction(/g

# pick view
/if (file_exists(DIR_TEMPLATE/,+2d
/\s\$this\->template = 'default\/template\/\(.*\)\.tpl';/{s//$this->view->pick('\1');/g;n;d;}

# response
s/$this->response->setOutput($this->render());/$this->view->setVars($this->data);/g
s/\$this\->response\->setOutput\(.*\);/$this->response->setContent\1;\n\t\treturn $this->response;/g
s/\$this\->render();/$this->view->setVars($this->data);\n\t\t$this->view->render('defined_by_pick', 'defined_by_pick');\n\t\treturn $this->view->getContent();/g

# common action
/\$this\->children = array/i\\t\t$this->_commonAction();
/\$this\->children = array/,/^\s*);\s*$/d

# view
s/catalog\/view\/javascript/js/g
s/catalog\/view\/theme/theme/g

# redirect
s/\$this\->redirect(\(.*\));/$this->response->redirect(\1, true);\n\t\treturn;/g

# load
s/\$this\->load\->helper('vat');/require_once(_ROOT_. '\/libs\/helper\/vat.php');/g
/\$this\->load\->library.*;/d

