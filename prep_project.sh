#!/bin/bash

hr="*******************"

php php-cs-fixer.phar fix -v --level=psr2 module/

echo "$hr"
echo "php-cs-fixer ran for module/"
echo "$hr"

php php-cs-fixer.phar fix -v --level=psr2 config/

echo "$hr"
echo "php-cs-fixer ran for config/"
echo "$hr"

sudo find . -type f -print0 | xargs -0 chmod 0644

echo "$hr"
echo "chmod 0644 implemented recursively"
echo "$hr"

if [ "$1" = "zip" ]; then
	#php vendor/bin/zfdeploy.php build demo_zf2_$(date +%Y%m%d_%H%M%S).zip --modules=Application,Debug,User,Exam,ContactUs -v
	
	echo "$hr"
	FILENAME=demo_zf2_.$(date +%Y%m%d_%H%M%S).zip
	
	echo "Creating file named: ".$FILENAME
	echo "$hr"

	zip -r $FILENAME * -x \*_bak* *database.local.php config/autoload/local.php module/*/tests/**\* data/**\* tests/**\* module/Album/**\* module/Stub/**\* composer.* *.git* Vagrantfile php-cs-fixer.phar php_errors.log phpunitVerify.sh prep_project.sh vendor\* vendor_bak\* my-httpd.* logs/**\*

fi

echo "$hr"
echo "...DONE"
