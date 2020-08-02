Implementation
========================
I have created custom framework for this solution.

Backend part of monolithic application consist with 2 native modules (Framework, App) and 2 external libraries (twig, phpspreadsheet).

* `Framework` is a framework core module.
* `App` is a module for http response.
* `Twig` is a template engine.
* `Phpspreadsheet` is a third party application for xls reader.

Storage folder implemented for temporary files.
Application read and show listing (I didn't use database).

Frontend part is implemented via jquery. Also, I've used the dropzone library for upload files.


Installation Guide
========================

When it comes to installing the application, you have the following options.

### Use Composer (*important*)

    composer install
    composer dump-autoload --optimize

### Create storage (*important*)

    mkdir storage
    chmod -R 0777 storage

### Example of Nginx configuration

    server {
        listen              80;
        server_name         local.match-score;

        root                /var/www/match-score/public;
        index               index.php;

        location / {
            try_files $uri $uri/ /index.php$is_args$args;
        }

        rewrite ^/(.*)/$ /$1 permanent;

        if (!-e $request_filename) {
            rewrite ^.*$ /index.php last;
        }

        location ~ \.php$ {
            fastcgi_pass   unix:/var/run/php/php7.2-fpm.sock;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME $document_root/index.php;
            fastcgi_param  APPLICATION_ENV development;
            fastcgi_param  APPLICATION_ENV production;
            include        fastcgi_params;
        }

        location ~* ^/.+\.(php)|/.+\.(php)/.+$ {
            return $scheme://$host/404;
        }

        location ~* \.(jpg|jpeg|gif|png|ico|css|bmp|swf|js|html|txt|eot|woff|woff2|ttf)$ {
            root /var/www/match-score/public;
            expires 30d;
            add_header Cache-Control "public";
        }

        access_log /var/log/nginx/local.match-score.log warn;
        error_log  /var/log/nginx/local.match-score.log warn;
    }


Browsing the Demo Application
========================

Open in local environment

    local.match-score
