<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Rest Controller
 * Email Reader
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Garrett St. John
 * @license         ?
 * @link            https://garrettstjohn.com/article/reading-emails-with-php/
 * @version         ?
 */

class Email_reader {

	// imap server connection
	public $conn;

	// inbox storage and inbox message count
	public $inbox;
	public $msg_cnt;

	// email login credentials
	private $server = 'mail.revisionalpha.com';
	private $user   = 'envios@revisionalpha.com';
	private $pass   = 'enpass3900.';
	private $port   = 110; // adjust according to server settings

	// connect to the server and get the inbox emails
	function __construct() {
		$this->connect();
		$this->inbox();
	}

	// close the server connection
	function close() {
		$this->inbox = array();
		$this->msg_cnt = 0;

		imap_close($this->conn);
	}

	// open the server connection
	// the imap_open function parameters will need to be changed for the particular server
	// these are laid out to connect to a Dreamhost IMAP server
	function connect() {
		$this->conn = imap_open('{'.$this->server.'/notls}', $this->user, $this->pass);
	}

	// move the message to a new folder
	function move($msg_index, $folder='INBOX.Processed') {
		// move on server
		imap_mail_move($this->conn, $msg_index, $folder);
		imap_expunge($this->conn);

		// re-read the inbox
		$this->inbox();
	}

	// get a specific message (1 = first email, 2 = second email, etc.)
	function get($msg_index) {
		if (isset($this->inbox[$msg_index])) {
		return $this->inbox[$msg_index];
	}
		return [];
	}

	// read the inbox
	function inbox() {
		$this->msg_cnt = imap_num_msg($this->conn);

		$in = array();
		for($i = 1; $i <= $this->msg_cnt; $i++) {
			$in[$i] = array(
				'index'     => $i,
				'header'    => imap_headerinfo($this->conn, $i),
				'body'      => imap_body($this->conn, $i),
				'structure' => imap_fetchstructure($this->conn, $i)
			);
		}

		$this->inbox = $in;
	}

}