<?php

namespace Ctimt\SqlControl\Adapter\SqlSrv\Filter;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-09-19 at 18:02:54.
 */
class EscapeKeyWordsTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var EscapeKeyWords
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new EscapeKeyWords(['user']);
    }

    public function testKeyWords() {
        $keyWords = ['user'];
        $this->assertEquals($keyWords, $this->object->setKeyWords($keyWords)->getKeyWords());
    }

    public function testFilter() {
        $sql = "select User.firstName from User where user.lastName = 'Anderson'";
        $result = "select [User].firstName from [User] where [user].lastName = 'Anderson'";
        $this->assertEquals($result, $this->object->filter($sql));
    }
    
    public function testFilterEndOfString() {
        $sql = "select User.firstName from User";
        $result = "select [User].firstName from [User]";
        $this->assertEquals($result, $this->object->filter($sql));
    }
    
    public function testFilterCreateWithMultipleKeyWords(){
        $this->object->setKeyWords(['User','File']);
        $sql = "CREATE TABLE File (
  file_id int  IDENTITY,
  file_purpose varchar(45) DEFAULT NULL,
  file_path varchar(250) DEFAULT NULL,
  file_dateTime datetime NOT NULL,
  file_deleted tinyint  DEFAULT '0',
  status_userId int  DEFAULT NULL,
  content_id int  NOT NULL,
  user_id int  NOT NULL,
  fileType_id int  NOT NULL,
  language_id int NOT NULL,
  PRIMARY KEY (file_id),";
        $result = "CREATE TABLE [File] (
  file_id int  IDENTITY,
  file_purpose varchar(45) DEFAULT NULL,
  file_path varchar(250) DEFAULT NULL,
  file_dateTime datetime NOT NULL,
  file_deleted tinyint  DEFAULT '0',
  status_userId int  DEFAULT NULL,
  content_id int  NOT NULL,
  user_id int  NOT NULL,
  fileType_id int  NOT NULL,
  language_id int NOT NULL,
  PRIMARY KEY (file_id),";
        $this->assertEquals($result, $this->object->filter($sql));
    }


    public function testFilterParenthesis() {
        $sql = "select 
            u.user_email AS username,
            u.user_password AS password,
            u.user_firstName AS first_name,
            u.user_lastName AS last_name 
            from (User u join UserStatus us on((us.userStatus_id = u.userStatus_id))) 
            where (us.userStatus_active = 1)";
        $result = "select 
            u.user_email AS username,
            u.user_password AS password,
            u.user_firstName AS first_name,
            u.user_lastName AS last_name 
            from ([User] u join UserStatus us on((us.userStatus_id = u.userStatus_id))) 
            where (us.userStatus_active = 1)";
        $this->assertEquals($result, $this->object->filter($sql));
    }

}