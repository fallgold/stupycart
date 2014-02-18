
s/^\(.*\)class\s*\(Length\|Tax\|Cart\|User\|Weight\|Currency\|Customer\|Affiliate\)/\1class \2 extends \\Phalcon\\DI\\Injectable/g
s/^\(.*\)class \([a-zA-Z0-9_-]*\)\(\s*[^{]*\s*\){/\nnamespace Libs\\Opencart;\n\n\1class \2\3{/g

