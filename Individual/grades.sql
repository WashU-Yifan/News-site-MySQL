MariaDB [wustl]> show create table grades;
+--------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Table  | Create Table                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |
+--------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| grades | CREATE TABLE `grades` (
  `pk_grade_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id` mediumint(8) unsigned NOT NULL,
  `grade` decimal(5,2) NOT NULL,
  `school_code` enum('L','B','A','F','E','T','I','W','S','U','M') NOT NULL,
  `dept_id` tinyint(3) unsigned NOT NULL,
  `course_code` varchar(5) NOT NULL,
  PRIMARY KEY (`pk_grade_ID`),
  KEY `school_code` (`school_code`,`dept_id`,`course_code`),
  KEY `id` (`id`),
  CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`school_code`, `dept_id`, `course_code`) REFERENCES `courses` (`school_code`, `dept_id`, `course_code`),
  CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`id`) REFERENCES `students` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 |
