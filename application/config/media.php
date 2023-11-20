<?php
/**
 * Media Manager for Codeigniter
 *
 * @package    CodeIgniter
 * @author     Prashant Pareek
 * @link       http://codecanyon.net/item/media-manager-for-codeigniter/9517058
 * @version    2.3.1
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$config['allowed_types'] = 'csv,doc,gif,jpg,jpeg,pdf,png,ppt,swf,txt,xls,mp3,mp4,mov,avi,aif,aiff,wav,tif,tiff,mpg,mpeg,m4a,aac,acc3,flac,zip';
$config['max_size'] = 250000;
$config['max_width'] = 19200;
$config['max_height'] = 19200;
$config['media_path'] = 'multimedia';
$config['max_filename'] = 0;
$config['max_files'] = 10;
$config['overwrite'] = 0;
$config['remove_spaces'] = 1;
$config['encrypt_name'] = 0;