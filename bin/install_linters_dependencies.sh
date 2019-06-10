#!/bin/bash

rm -rf phpcs rulesets

git clone https://github.com/squizlabs/PHP_CodeSniffer.git phpcs
git clone -b master https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards.git rulesets/wpcs
git clone -b master https://github.com/PHPCompatibility/PHPCompatibility.git rulesets/PHPCompatibility

npm install

cp bin/pre-commit .git/hooks/pre-commit
