CREATE TABLE IF NOT EXISTS `mail_send` (
`mail_send_id` int(11) NOT NULL auto_increment,
`ip` text NOT NULL,
`date_added` DATETIME NOT NULL,
`emailfrom` varchar(300) NOT NULL,
`namefrom` varchar(300) NOT NULL,
`phone` varchar(20) NOT NULL,
`emailto` varchar(300) NOT NULL,
`subject` text NOT NULL,
`message` text NOT NULL,
`status` int(1) NOT NULL,
PRIMARY KEY  (`mail_send_id`)
);

CREATE TABLE IF NOT EXISTS `mail_receive` (
`mail_receive_id` int(11) NOT NULL auto_increment,
`ip` text NOT NULL,
`date_added` DATETIME NOT NULL,
`emailfrom` varchar(300) NOT NULL,
`namefrom` varchar(300) NOT NULL,
`phone` varchar(20) NOT NULL,
`emailto` varchar(300) NOT NULL,
`subject` text NOT NULL,
`message` text NOT NULL,
`status` int(1) NOT NULL,
PRIMARY KEY  (`mail_receive_id`)
);
