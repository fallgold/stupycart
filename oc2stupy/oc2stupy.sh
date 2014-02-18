#!/bin/bash

root=/home/weils/htdocs/phalcon/stupycart/oc2stupy
oc=/home/weils/htdocs/opencart1551/upload
opall='1'

if [ "$opall" = "1" ]; then
	cd $root
	rm -r controller model view
	rm -r library
	cp $oc/system/library/ . -R
	cp $oc/catalog/controller/ . -R
	cp $oc/catalog/model/ . -R
	cp $oc/catalog/view/theme/default/template/ ./view -R
fi


# libs
# rename dir
cd $root/library
ls -1d * |sed -n '/\(^[a-z].*\)/{s//mv & \u\1/e;}'

# model
# rename dir
cd $root/model
ls -1d * |sed -n '/\(^[a-z].*\)/{s//mv & \u\1/e;}'

# model
# rename file
cd $root/model
# 重复执行会mv error，但不影响执行结果
find . -type f |sed -n -e 's/\(\.\/[^\/]*\/\)\(.*\)/& \1\u\2/' -e 's/\(\.php .*\)_\(.\)/\1\u\2/' -e 's/\(\.php .*\)_\(.\)/\1\u\2/' -e 's/\(\.php .*\)_\(.\)/\1\u\2/' -e 's/.*/mv &/e'

# controller
# rename dir
cd $root/controller
ls -1d * |sed -n '/\(^[a-z].*\)/{s//mv & \u\1/e;}'

# controller
# rename file
cd $root/controller
# 重复执行会mv error，但不影响执行结果
find . -type f |sed -n -e 's/\(\.\/[^\/]*\/\)\(.*\)\.php/& \1\u\2Controller.php/' -e 's/\(\.php .*\)_\(.\)/\1\u\2/' -e 's/\(\.php .*\)_\(.\)/\1\u\2/' -e 's/\(\.php .*\)_\(.\)/\1\u\2/' -e 's/.*/mv &/e'


cd $root


# controller_dir
# TODO: redirect...
find controller -type f |xargs -I% sed -i -f oc2stupy.controller_dir.sed %

# request
# TODO: session[] = array
find . -type f -name "*.php" |xargs -I% sed -i -f oc2stupy.request.sed %

# model dir
# query, class define
find model -type f -name "*.php" |xargs -I% sed -i -f oc2stupy.model_dir.sed %

# model load
find . -type f -name "*.php" |xargs -I% sed -i -f oc2stupy.model.sed %
# model query
find . -type f -name "*.php" |xargs -I% sed -i -f oc2stupy.model.otherdir.sed %

# view
find view -type f -name "*.tpl" |xargs -I% sed -i -f oc2stupy.view.sed %

# libs
find library -type f -name "*.php" |xargs -I% sed -i -f oc2stupy.lib.sed %


if [ "$opall" = "1" ]; then
	cd $root
	cp library/* ../libs/Opencart/ -R
	cp controller/* ../apps/frontend/controllers/ -R
	cp model/* ../apps/common/models/ -R
	cp view/* ../apps/frontend/views/ -R
fi

