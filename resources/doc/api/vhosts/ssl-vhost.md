```
<VirtualHost *:443>

  ServerName kali.1001pharmacies.com
  RequestHeader set X-Forwarded-Proto "https"

  SSLEngine On
  SSLCertificateFile /etc/apache2/ssl/server.crt
  SSLCertificateKeyFile /etc/apache2/ssl/server.key

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
  ErrorLog "/var/log/apache2/kali.1001pharmacies.com_error.log"
  ServerSignature Off
  CustomLog "/var/log/apache2/kali.1001pharmacies.com_access.log" combined

  ## Server aliases
  ServerAlias www.kali.1001pharmacies.com

  ## SetEnv/SetEnvIf for environment variables (`local` for dev)
  SetEnv TP_ENV local

</VirtualHost>
```