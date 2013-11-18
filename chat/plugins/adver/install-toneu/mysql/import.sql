-- 
-- Table structure for table `prochatrooms_adverts`
-- 

CREATE TABLE `prochatrooms_adverts` (
  `id` int(11) NOT NULL auto_increment,
  `text` text NOT NULL,
  `displays` varchar(500) NOT NULL default '0',
  `clicks` varchar(500) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;