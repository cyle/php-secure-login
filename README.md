# CyleSoft PHP Secure Login System

It's pretty simple. Uses BLOWFISH ($2y$, specifically) crypt() hashing in PHP 5.3.7+. The purpose behind this was to make a pretty damn good way to store passwords and give the user a unique session token.

## Requirements

- PHP 5.3.7+
- MySQL

## Installation

1. Set up a new MySQL database, build its tables using user_tables.sql
1. Rename dbconn_mysql.example.php to dbconn_mysql.php and put the database info in there.
1. Upload the files to your web server and visit index.php

## How it works

1. User registers using an email address and password (though you could easily swap out email for username).
1. A unique salt is generated using /dev/urandom via a get_key() function originally found [here](http://stackoverflow.com/questions/637278/what-is-the-best-way-to-generate-a-random-key-within-php).
1. Password is hashed using crypt() $2y$ BLOWFISH hashing, at 12 cost.
1. New user data is stored in MySQL, yay.
1. User is presented to log in.
1. When a user logs in, it looks up their email, and compares passwords based on crypt() functionality.
1. If the user logs in successfully, they are given a 256-bit key and a 256-bit secret (using get_key(256)) that identifies them.
1. The session key/secret token is saved via a cookie, which is set to expire in 30 days by default.
1. When the user accesses a page, PHP looks up their session based on this key.

That's it! Obviously you'd probably want to build in a way for a user to invalidate all of their current sessions and change their password.

## Notes

Originally this used /dev/random instead of /dev/urandom, but it got way too slow as /dev/random would run out of entropy. Not sure exactly how to fix that yet, as my server does not have a TRNG to run [rng-tools](http://www.gnu.org/software/hurd/user/tlecarrour/rng-tools.html) with.

Obviously you'd still want to use SSL to prevent any over-the-wire (or over-the-air) session hijacking and password stealing.

It also features a login flood control mechanism to prevent bots from trying to brute-force their way in; after 20 failed attempts from a given IP, you need to wait half an hour to try logging in again.

This is just a proof of concept, I don't think you should keep the file structure like this, but you could.

Also, you could easily use memcached or riak or something more efficient to store session data.

Check this out: http://cylesoft.com/