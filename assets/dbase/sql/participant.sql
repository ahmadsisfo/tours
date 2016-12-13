CREATE TABLE IF NOT EXISTS `participant` (
`participant_id` int(11) NOT NULL auto_increment,
`participant_group_id` int(11) NOT NULL,
`name` varchar(225) NOT NULL,
`username` varchar(225) NOT NULL,
`email` varchar(225) NOT NULL,
`phone` varchar(225) NOT NULL,
`password` varchar(225) NOT NULL,
`instansi` varchar(225) NOT NULL,
`paper` text NOT NULL,
`fullpaper` text NOT NULL,
`status` int(1) NOT NULL,
`ip` varchar(200) NOT NULL,
`date_added` DATETIME NOT NULL,
PRIMARY KEY  (`participant_id`)
);

CREATE TABLE IF NOT EXISTS `participant_group` (
  `participant_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `permission` text NOT NULL,
  PRIMARY KEY (`participant_group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;
