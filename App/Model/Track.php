<?php
namespace Music\Model;

class Track extends \ActiveRecord\Model
{
    static $table_name = 'track';
    static $primary_key = 'idtrack';
    static $belongs_to = array(array('album'), array('playlist'));
    //static $has_many = array(array('extradata'));
}
?>