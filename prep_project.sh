php php-cs-fixer.phar fix -v --level=psr2 module/

php php-cs-fixer.phar fix -v --level=psr2 config/

sudo find . -type f -print0 | xargs -0 chmod 0644

#zip -r demo_zf2_$(date +%Y%m%d_%H%M%S).zip * -x \*_bak* -x database.local.php

php vendor/bin/zfdeploy.php build demo_zf2_$(date +%Y%m%d_%H%M%S).zip --modules=Application,Debug,User,Exam,ContactUs -v

