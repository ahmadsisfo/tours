CREATE TABLE IF NOT EXISTS `ay_tracker` (
`id` int(11) NOT NULL auto_increment,
`ip` text NOT NULL,
`country` text NOT NULL,
`city` text NOT NULL,
`query_string` text NOT NULL,
`http_referer` text NOT NULL,
`http_user_agent` text NOT NULL,
`isbot` int(11) NOT NULL,
`status` int(1) NOT NULL,
PRIMARY KEY  (`id`)
);

CREATE TABLE IF NOT EXISTS `ay_tracker_detail` (
`id` int(11) NOT NULL auto_increment,
`tracker_id` int(11) NOT NULL,
`date` date NOT NULL,
`time` time NOT NULL,
`url` text NOT NULL,
`info` text NOT NULL,
PRIMARY KEY  (`id`)
);