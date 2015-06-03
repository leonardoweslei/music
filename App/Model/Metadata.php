<?php
namespace Music\Model;

class Metadata extends \ActiveRecord\Model
{
    static $table_name = 'metadata';
    static $primary_key = 'idmetadata';
    static $belongs_to = array(array('track'));
}
?>