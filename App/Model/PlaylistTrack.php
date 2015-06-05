<?php
namespace Music\Model;

class PlaylistTrack extends \ActiveRecord\Model
{
    static $table_name = 'playlist_track';
    static $belongs_to = array(array('track'), array('playlist'));
}
?>