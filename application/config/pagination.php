<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['per_page'] = 25;
$config['page_query_string'] = true;
$config['query_string_segment'] = 'page';
$config['use_page_numbers'] = true;
$config['reuse_query_string'] = true;
$config['suffix'] = '&paginado=true';

$config['full_tag_open'] = '<ul class="pagination pull-right">';
$config['full_tag_close'] = '</ul>';

$config['first_link'] = '« Primera';
$config['first_tag_open'] = '<li class="footable-page-arrow">';
$config['first_tag_close'] = '</li>';

$config['last_link'] = 'Ultima »';
$config['last_tag_open'] = '<li class="footable-page-arrow">';
$config['last_tag_close'] = '</li>';

$config['next_link'] = 'Próxima →';
$config['next_tag_open'] = '<li class="footable-page-arrow">';
$config['next_tag_close'] = '</li>';

$config['prev_link'] = '← Anterior';
$config['prev_tag_open'] = '<li class="footable-page-arrow">';
$config['prev_tag_close'] = '</li>';

$config['cur_tag_open'] = '<li class="footable-page active"><a>';
$config['cur_tag_close'] = '</a></li>';

$config['num_tag_open'] = '<li class="footable-page">';
$config['num_tag_close'] = '</li>';