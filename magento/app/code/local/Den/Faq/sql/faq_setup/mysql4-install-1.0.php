<?php

$installer = $this;

$installer->startSetup();
try {
    $installer->run("

DROP TABLE IF EXISTS {$this->getTable('faq/faq')};
CREATE TABLE {$this->getTable('faq/faq')} (
`post_id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
`cat_id` smallint( 11 ) NOT NULL default '0',
`title` varchar( 255 ) NOT NULL default '',
`post_content` text NOT NULL ,
`status` smallint( 6 ) NOT NULL default '0',
`created_time` datetime default NULL ,
`update_time` datetime default NULL ,
`identifier` varchar( 255 ) NOT NULL default '',
`user` varchar( 255 ) NOT NULL default '',
`update_user` varchar( 255 ) NOT NULL default '',
`meta_keywords` text NOT NULL ,
`meta_description` text NOT NULL ,
`comments` TINYINT( 11 ) NOT NULL,
PRIMARY KEY ( `post_id` ) ,
UNIQUE KEY `identifier` ( `identifier` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO {$this->getTable('faq/faq')} (`post_id` ,`cat_id`, `title` ,`post_content` ,`status` ,`created_time` ,`update_time` ,`identifier` ,`user` ,`update_user` ,`meta_keywords` ,`meta_description`)
VALUES (NULL ,'0', 'Hello World', 'Welcome to Magento Blog by aheadWorks Co. This is your first post. Edit or delete it, then start faqging!', '1', '2010-09-06 07:28:34' , NOW( ) , 'Hello', 'Joe Blogs', 'Joe Blogs', 'Keywords', 'Description');

DROP TABLE IF EXISTS {$this->getTable('faq/comment')};
CREATE TABLE {$this->getTable('faq/comment')} (
`comment_id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
`post_id` smallint( 11 ) NOT NULL default '0',
`comment` text NOT NULL ,
`status` smallint( 6 ) NOT NULL default '0',
`created_time` datetime default NULL ,
`user` varchar( 255 ) NOT NULL default '',
`email` varchar( 255 ) NOT NULL default '',
PRIMARY KEY ( `comment_id` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO {$this->getTable('faq/comment')} (`comment_id` ,`post_id` ,`comment` ,`status` ,`created_time` ,`user` ,`email`)
VALUES (NULL , '1', 'This is the first comment. It can be edited, deleted or set to unapproved so it is not displayed. This can be done in the admin panel.', '2', NOW( ) , 'Joe Blogs', 'joe@faqs.com');

DROP TABLE IF EXISTS {$this->getTable('faq/cat')};
CREATE TABLE {$this->getTable('faq/cat')} (
`cat_id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
`title` varchar( 255 ) NOT NULL default '',
`identifier` varchar( 255 ) NOT NULL default '',
`sort_order` tinyint ( 6 ) NOT NULL ,
`meta_keywords` text NOT NULL ,
`meta_description` text NOT NULL ,
PRIMARY KEY ( `cat_id` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO {$this->getTable('faq/cat')} (
`cat_id` ,
`title`,
`identifier`
)
VALUES (
NULL , 'News', 'news'
);


DROP TABLE IF EXISTS {$this->getTable('faq/store')};
CREATE TABLE {$this->getTable('faq/store')} (
`post_id` smallint(6) unsigned,
`store_id` smallint(6) unsigned
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS {$this->getTable('faq/cat_store')};
CREATE TABLE {$this->getTable('faq/cat_store')} (
`cat_id` smallint(6) unsigned ,
`store_id` smallint(6) unsigned
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS {$this->getTable('faq/post_cat')};
CREATE TABLE {$this->getTable('faq/post_cat')} (
`cat_id` smallint(6) unsigned ,
`post_id` smallint(6) unsigned
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

ALTER TABLE {$this->getTable('faq/faq')} ADD `tags` TEXT NOT NULL;
ALTER TABLE {$this->getTable('faq/faq')} ADD `short_content` TEXT NOT NULL;
");
} catch (Exception $e) {

}

$installer->run("
CREATE TABLE {$this->getTable('faq/tag')} (
 `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `tag` VARCHAR( 255 ) NOT NULL ,
 `tag_count` INT( 11 ) NOT NULL DEFAULT '0',
 `store_id` TINYINT( 4 ) NOT NULL ,
  INDEX ( `tag` , `tag_count` , `store_id` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
");

$installer->endSetup();

