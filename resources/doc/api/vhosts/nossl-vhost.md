```
<VirtualHost *:80>

  ServerName api.1001pharmacies.com

  ## Vhost docroot
  DocumentRoot "{project source directory (ex: /var/www/html/web/)}"

  ## Directories, there should at least be a declaration for {project source directory}

  <Directory "{project source directory (ex: /var/www/html/web/}">
    Options FollowSymLinks MultiViews
    AllowOverride All
    Require all granted
  </Directory>

  ## Load additional static includes

  ## Logging
  ErrorLog "/var/log/apache2/api.1001pharmacies.com_error.log"
  ServerSignature Off
  CustomLog "/var/log/apache2/api.1001pharmacies.com_access.log" combined

  ## Server aliases
  ServerAlias www.api.1001pharmacies.com

  ## SetEnv/SetEnvIf for environment variables (`local` for dev)
  SetEnv TP_ENV local

</VirtualHost>
```