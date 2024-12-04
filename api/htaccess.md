
## For testing

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} !=on
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    RewriteRule ^(.*)$ public/ [L]
</IfModule>




RewriteEngine On
RewriteBase /workspace/

RewriteCond %{THE_REQUEST} /public/([^\s?]*) [NC]
RewriteRule ^ %1 [L,NE,R=302]

RewriteRule ^((?!public/).*)$ public/$1 [L,NC]





RewriteEngine On
RewriteCond %{REQUEST_URI} !^public
RewriteRule ^(.*)$ public/$1 [L]





<IfModule mod_rewrite.c>
    # That was ONLY to protect you from 500 errors
    # if your server did not have mod_rewrite enabled
    RewriteEngine On
    # RewriteBase /
    # NOT needed unless you're using mod_alias to redirect
    RewriteCond %{REQUEST_URI} !/public
    RewriteRule ^(.*)$ public/$1 [L]
    # Direct all requests to /public folder
</IfModule>


