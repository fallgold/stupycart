#!/bin/bash

root=/home/weils/htdocs/phalcon/stupycart/oc2stupy/admin
oc=/home/weils/htdocs/opencart1551/upload/admin
opall='1'

if [ "$opall" = "1" ]; then
	cd $root
	rm -r controller model view
	cp $oc/controller/ . -R
	cp $oc/model/ . -R
	cp $oc/view/template/ ./view -R
fi


# model
# rename dir
cd $root/model
ls -1d * |sed -n '/\(^[a-z].*\)/{s//mv & \u\1/e;}'

# model
# rename file
cd $root/model
# 重复执行会mv error，但不影响执行结果
find . -type f |sed -n -e 's/\(\.\/[^\/]*\/\)\(.*\)/& \1\u\2/' -e 's/\(\.php .*\)_\(.\)/\1\u\2/' -e 's/\(\.php .*\)_\(.\)/\1\u\2/' -e 's/\(\.php .*\)_\(.\)/\1\u\2/' -e 's/.*/mv &/e'
mv Sale/Return.php Sale/ReturnModel.php

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


if [ "$opall" = "1" ]; then
	cd $root
	cp controller/* ../../apps/admin/controllers/ -R
	cp model/* ../../apps/common/models/Admin/ -R
	cp view/* ../../apps/admin/views/ -R
fi

