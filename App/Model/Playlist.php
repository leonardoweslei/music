<?php
namespace Music\Model;

class Songs extends \ActiveRecord\Model
{
    static $table_name = 'playlist';
    static $primary_key = 'idplaylist';
    static $belongs_to = array(array('user'));
    static $has_many = array(array('track'));
}
?>