# rpg-archive
Darkshifty deleted his project so I made an archive of it

# Original Readme

# pokemon-rpg

Import the .sql file followed by the .sql-updates file into your database and edit the values of the table 'settings' to your needs

Define database values in scheduler/cronConfig.php
Define database values in includes/config.php

Replace ```#serverip#``` with the ip of your server in the .htaccess file in scheduler/

Define the following crons and replace yourdomain with your full domain name i.e. https://pokeworld.nl 
Minute/Hour/Day/Month/Weekday

```
0 1 * * *       "/usr/bin/wget -O - yourdomain/scheduler/cron_day.php >/dev/null"
0 4 * * *       "/usr/bin/wget -O - yourdomain/scheduler/cron_backup.php >/dev/null"
0 0 * *	*       "/usr/bin/wget -O - yourdomain/scheduler/cron_daycare.php >/dev/null"
0 3 * *	*       "/usr/bin/wget -O - yourdomain/scheduler/cron_log_chat.php >/dev/null"
0 0,12 * * *    "/usr/bin/wget -O - yourdomain/scheduler/cron_market.php >/dev/null"	
```

There are three styles you can choose from, just change the style inclusion within index.php on line 327 to one of the following:

```
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/style-christmas.css" />
<link rel="stylesheet" type="text/css" href="css/style-spring.css" />
```

chmod the following files and folders to 755
```
/cache
/uploads
/includes/backups
.htaccess (ip bans will be written to the .htaccess automaticly.)
```

create a google recaptcha to prevent spam and add the secret key and site key in the following locations:
```
secret key in register.php:39
site key in register.php:381

```

_Special thanks to:_
```
Mugaru - Thanks for changing some bits into PHP7

```
