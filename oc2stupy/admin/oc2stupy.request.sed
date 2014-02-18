
# _GET
s/isset(\$this\->request\->get\[\([^]]*\)\])/$this->request->hasQuery(\1)/g
s/empty(\$this\->request\->get\[\([^]]*\)\])/(!$this->request->getQueryE(\1))/g
s/\$this\->request\->get\[\([^]]*\)\]/$this->request->getQueryE(\1)/g
s/\$this\->request\->get)/$this->request->getQueryE())/g

# _POST
s/isset(\$this\->request\->post\[\([^]]*\)\])/$this->request->hasPost(\1)/g
s/empty(\$this\->request\->post\[\([^]]*\)\])/(!$this->request->getPostE(\1))/g
s/\$this\->request\->post\[\([^]]*\)\]/$this->request->getPostE(\1)/g
s/\$this\->request\->post)/$this->request->getPostE())/g

# _REQUEST
s/isset(\$this\->request\->request\[\([^]]*\)\])/$this->request->has(\1)/g
s/empty(\$this\->request\->request\[\([^]]*\)\])/(!$this->request->getE(\1))/g
s/\$this\->request\->request\[\([^]]*\)\]/$this->request->getE(\1)/g

# isset _SESSION[]
s/isset(\$this\->session\->data\[\([^]]*\)\])/$this->session->has(\1)/g
s/empty(\$this\->session\->data\[\([^]]*\)\])/(!$this->session->get(\1))/g
# isset _SESSION[][]...
s/isset(\$this\->session\->data\[\([^]]*\)\]\(.[^)]*\))/(($_tmp = $this->session->get(\1)) \&\& isset($_tmp\2))/g
s/empty(\$this\->session\->data\[\([^]]*\)\]\(.[^)]*\))/(!($_tmp = $this->session->get(\1)) || empty($_tmp\2))/g
# unset $_SESSION[][]
/unset(\$this\->session\->data\[\([^]]*\)\]\(\[[^]]*\]\)*\[\([^]]*\)\]);/{s//{ $_tmp = $this->session->get(\1); unset($_tmp[\3]); $this->session->set(\1, $_tmp); }/g;}
# unset $_SESSION[]
/unset(\$this\->session\->data\[\([^]]*\)\]);/{s//$this->session->remove(\1);/g;}
# set $_SESSION[][]
/\$this\->session\->data\[\([^]]*\)\]\(\[[^]]*\]\)*\[\([^]]*\)\]\s*\([+\-\*\/]=\)\s*\(.*\);/{s//{ $_tmp = $this->session->get(\1); $_tmp[\3] \4 \5; $this->session->set(\1, $_tmp); }/g;}
/\$this\->session\->data\[\([^]]*\)\]\(\[[^]]*\]\)*\[\([^]]*\)\]\s*=\s*\(.*\);/{s//{ $_tmp = $this->session->get(\1); $_tmp[\3] = \4; $this->session->set(\1, $_tmp); }/g;}
# set $_SESSION[]
/\$this\->session\->data\[\([^]]*\)\]\s*\([+\-\*\/]=\)\s*\(.*\);/{s//{ $_tmp = $this->session->get(\1); $_tmp \2 \3; $this->session->set(\1, $_tmp); }/g;}
/\$this\->session\->data\[\([^]]*\)\]\s*=\s*\(.*\);/{s//$this->session->set(\1, \2);/g;}
# special for checkout/cart.php:77
s/, \$this\->session\->data\[\([^]]*\)\]\(.*\));/, (($_tmp = $this->session->get(\1)) ? $_tmp\2 : null));/g
# all other _SESSION[][]...
s/\$this\->session\->data\[\([^]]*\)\]\(\(\[[^]]*\]\)\{1,\}\)/(($_tmp = $this->session->get(\1)) ? $_tmp\2 : null)/g
# all other _SESSION[]
s/\$this\->session\->data\[\([^]]*\)\]/$this->session->get(\1)/g

# _COOKIE[]
s/isset(\$this\->request\->cookie\[\([^]]*\)\])/$this->cookies->has(\1)/g
s/empty(\$this\->request\->cookie\[\([^]]*\)\])/(!$this->cookies->has(\1) || !$this->cookies->get(\1)->getValue())/g
s/\$this\->request\->cookie\[\([^]]*\)\]/$this->cookies->get(\1)->getValue()/g
# set
s/setcookie(/$this->cookies->set(/g

# _SERVER
s/isset(\$this\->request\->server\[\([^]]*\)\])/$this->request->hasServer(\1)/g
s/empty(\$this\->request\->server\[\([^]]*\)\])/(!$this->request->getServer(\1))/g
s/\$this\->request\->server\[\([^]]*\)\]/$this->request->getServer(\1)/g

# _FILE
# FIXME
s/\$this\->request\->files\[\([^]]*\)\]\[\([^]]*\)\]/$_FILES[\1][\2]/g

