s/catalog\/view\/javascript/{$this->url->getBaseUrl()}js/g
s/catalog\/view\/theme/{$this->url->getBaseUrl()}theme/g

#s/<?php\s*echo\s*\$\(header\|footer\);\s*?>//g
s/<?php\s*echo\s*\$\(header\|footer\|column_left\|column_right\|content_top\|content_bottom\);\s*?>/{include '..\/common\/\1.tpl'}/g

# StupyTpl error parse {dateFormat: xxx}
s/({\([^]:]*: \)/({ \1/g 

# route=xxx
s/index.php?route=\([^\/]*\/[^\/]*\/[^\/]*\)\&/{$this->url->getBaseUrl()}\1?/g
s/index.php?route=\([^\/]*\/[^\/]*\)\&/{$this->url->getBaseUrl()}\1?/g
s/index.php?route=\([^\/]*\)\&/{$this->url->getBaseUrl()}\1?/g
s/index.php?route=/{$this->url->getBaseUrl()}/g
