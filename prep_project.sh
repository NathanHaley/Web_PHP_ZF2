php php-cs-fixer.phar fix -v --level=psr2 module/

php php-cs-fixer.phar fix -v --level=psr2 config/

sudo find . -type f -print0 | xargs -0 chmod 0644

zip -r demo1.zip * -x \*_bak*