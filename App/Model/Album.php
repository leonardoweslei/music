<?php
namespace Music\Model;

class Album extends \ActiveRecord\Model
{
    static $table_name = 'album';
    static $primary_key = 'idalbum';
    static $belongs_to = array(array('artist'));
    static $has_many = array(array('track'));
}
?>