<?php
namespace Music\Model;

class Extradata extends \ActiveRecord\Model
{
    static $table_name = 'extradata';
    static $primary_key = 'idextradata';
    static $belongs_to = array(array('track'));
}
?>