CREATE TABLE IF NOT EXISTS `user_sessions` (
  `session_key` varchar(255) NOT NULL,
  `session_secret` varchar(255) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `expires` int(11) NOT NULL,
  `ts` int(11) NOT NULL,
  PRIMARY KEY (`session_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(250) NOT NULL,
  `pwrdlol` varchar(250) NOT NULL,
  `tsc` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `login_flood_control` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipaddr` varchar(64) NOT NULL,
  `attempts` int(11) NOT NULL,
  `tsc` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ipaddr` (`ipaddr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;