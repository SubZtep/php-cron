# php-cron

This script is able to execute scripts, like crontab. But more often.

**Please note that this script is under development.**

## Installation

Create `log` folder for log files.

## Usage

Create `tasks.json` file to describe tasks. For example:

```json
[
    {
        "exec": "php /var/www/cron-test/task1.php",
        "secs": 1,
        "log": "one"
    },
    {
        "exec": "php /var/www/cron-test/task2.php",
        "secs": 3
    }
]
```

`exec`: command to execute  
`secs`: Number of seconds between runs  
`log` (optional): log file name in logs folder

## How to use

It's up to you, run simply in a un*x screen is an option.

Upstream is an other option. This is just an example for more details please do some research.

For example In CentOS 6 create `/etc/init/php-cron.conf` with content below:

```sh
start on filesystem or runlevel [2345]
stop on shutdown
exec php /var/www/php-cron/index.php
respawn
```

Update `exec php` with your current path.

## Next Steps

Find and add missing features, create proper documentation, etc :)