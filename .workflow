cd a_package && ./configure-skeleton.sh

composer install
cd .. && composer dump-autoload && cd a_package

if you need
composer require orchestra/testbench

move files

fix namespaces if necessary

set up package service provider

run tests from laravel application, not from package

delete phpunit.xml

clean up composer.json

composer-link ../packages/{package}

composer require pkboom/{package}

test

from package
git remote add origin git@github.com:pkboom/{{your-package}}
wip
git push -u origin master -f

create README.md

delete a_package

when deleting a_package, it will only delete link
when deleting files, it will actually files in the package folder

