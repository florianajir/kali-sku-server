# Install
   
## Install SSL on server 

+ Be root :

```ShellSession
sudo su
```

+ Let apache listening to 443 port, in /etc/apache2/ports.conf add if not exists :

```ShellSession
sudo nano /etc/apache2/ports.conf
```
and add line : 

```
Listen 443
```

+ Generate cert key in /etc/apache2/ssl/

You may have to create `ssl` dir if not exists.

```ShellSession
cd /etc/apache2/ssl/
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout server.key -out server.crt
```

+ activate ssl and headers modules :

```ShellSession
a2enmod ssl
a2enmod headers
```

with theses commands you may see a warning message about the LOCALE. Don't worry it's not a problem.

## Add Virtual Host

Update or create your virtualhost file /etc/apache2/sites-enabled/{yourVhost}.conf (see examples files)

- [SSL](vhosts/ssl-vhost.md)
- [No SSL](vhosts/nossl-vhost.md)

And activate your Virtual Host

```ShellSession
a2ensite {yourVhost}
```

+ Restart apache

```ShellSession
service apache2 restart
```

## Clone project

+ Clone repository

```ShellSession
cd {project source directory (ex: /var/www/html/)}
git clone git@github.com:1001Pharmacies/kali-server.git
```

+ Install vendors 

```ShellSession
composer.phar install
```

At this point you will be asked to configure yours parameters (database etc.) 

+ Create database 

```ShellSession
php app/console doctrine:schema:update --force
```

**you're done ! enjoy. You can access to API with the following URI : https://kali.1001pharmacies.com or other if you have changed the URI into the VHOST file**
