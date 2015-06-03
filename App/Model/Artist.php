<?php
namespace Music\Model;

class Artist extends \ActiveRecord\Model
{
    static $table_name = 'artist';
    static $primary_key = 'idartist';
    static $has_many = array(array('album'));
}
?>