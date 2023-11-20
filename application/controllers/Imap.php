<?php defined('BASEPATH') or exit('No direct script access allowed');


class Imap extends Controller {


        // IMAP/POP3 (mail server) LOGIN
        var $imap_server    = 'localhost';
        var $imap_user        = 'envios@revisionalpha.com';
        var $imap_pass        = 'enpass3900.';


        // Constuctor

        function __construct() {

            parent::Controller();

            $this->load->library('Imap');

        }

        // index

        function index() {

            $inbox = $this->imap->cimap_open($this->imap_server, 'INBOX', $this->imap_user, $this->imap_pass) or die(imap_last_error());

            $data_array['totalmsg']    = $this->imap->cimap_num_msg($inbox);
            $data_array['quota']    = $this->imap->cimap_get_quota($inbox);

            //$this->load->view('mail_view', $data_array);   
            echo '<pre>' . print_r($data_array, true) . '</pre>'; 
        }
    }
