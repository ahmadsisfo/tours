CREATE TABLE IF NOT EXISTS `layout_content` (
  `layout_content_id` int(16) NOT NULL AUTO_INCREMENT,
  `layout_type_id` int(16) NOT NULL,
  `title` varchar(256) NOT NULL,
  `image` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `key` varchar(256) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`layout_content_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=131 ;


CREATE TABLE IF NOT EXISTS `layout_type` (
  `layout_type_id` int(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`layout_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;
