-- 
-- Table structure for table `prochatrooms_share`
-- 

CREATE TABLE `prochatrooms_share` (
  `id` int(11) NOT NULL auto_increment,
  `ref` varchar(32) NOT NULL,
  `username` varchar(255) NOT NULL,
  `file` varchar(100) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;