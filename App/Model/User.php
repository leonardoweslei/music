<?php
namespace Music\Model;

class User extends \ActiveRecord\Model
{
    static $table_name = 'user';
    static $primary_key = 'iduser';
    static $has_many = array(array('playlist'));
}
?>