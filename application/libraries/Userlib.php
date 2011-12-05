<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		Rick Ellis
 * @copyright	Copyright (c) 2006, EllisLab, Inc.
 * @license		http://www.codeignitor.com/user_guide/license.html
 * @link		http://www.codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter User Class v1
 *
 * This class contains functions that will give you the option to run a Member/User/Login system
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	User System
 * @author		Mike Owens [Xikeon]
 * @link		http://www.xikeon.com/
 */
class Userlib {
	
	/**
	 * Constructor
	 *
	 * Get instance for Database Lib
	 *
	 * @access	public
	 */
	function Userlib()
	{
		$this->CI =& get_instance();
		
		log_message('debug', "User Class Initialized");
	}
	
	// --------------------------------------------------------------------

	/**
	 * Register User
	 *
	 * @access	public
	 * @param	string	the username
	 * @param	string	the password
	 * @param	string	the email
	 * @param	string	the ip
	 * @param	integer	the status
	 * @param	boolean	check for email
	 * @param	boolean	check for IP
	 * @return	string	if the user was created, if not returns errors in Unordered List
	 */
	function register( $username, $password, $email, $status = 1, $emailcheck = TRUE, $ipcheck = TRUE )
	{
		if( $ipcheck )
		{
			$qip = $this->CI->db->query( "SELECT * FROM `users` WHERE ip='" . mysql_real_escape_string( $_SERVER[ 'REMOTE_ADDR' ] ) . "'" );
			if( $qip->num_rows( ) > 0 )
			{
				return 'This IP is already assigned to an account.';
			}
		}
		
		if( $emailcheck )
		{
			$qemail = $this->CI->db->query( "SELECT * FROM `users` WHERE email='" . mysql_real_escape_string( $email ) . "'" );
			if( $qemail->num_rows( ) > 0 )
			{
				return 'This IP is already assigned to an account.';
			}
		}
		
		$qusername = $this->CI->db->query( "SELECT * FROM `users` WHERE username='" . mysql_real_escape_string( $username ) . "'" );
		if( $qusername->num_rows( ) > 0 )
		{
			return 'This username is already taken.';
		}
		
		$this->CI->db->query( "INSERT INTO `users`
							( username, password, email, ip, status )
							VALUES
							( '" . mysql_real_escape_string( $username ) . "', '" . sha1( md5( $password ) ) . "', '" . mysql_real_escape_string( $email ) . "',
							'" . mysql_real_escape_string( $_SERVER[ 'REMOTE_ADDR' ] ) . "', '" . mysql_real_escape_string( $status ) . "' )" );
	}
	
	// --------------------------------------------------------------------

	/**
	 * Login User
	 *
	 * @access	public
	 * @param	string	the username
	 * @param	string	the password
	 * @return	boolean	if the user was logged in or not
	 */
	function login( $username, $password )
	{
		$qcheck = $this->CI->db->query( "SELECT * FROM `users` WHERE username='" . mysql_real_escape_string( $username ) . "' AND password='" . sha1( md5( $password ) ) . "'" );
		if( $qcheck->num_rows( ) == 0 )
		{
			return FALSE;
		}
		
		$newdata = array(
						'username'	=>	$username,
						'password'	=>	sha1( md5( $password ) )
		);

		$this->CI->session->set_userdata( $newdata );
		
		return TRUE;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Logged in
	 *
	 * @access	public
	 * @return	boolean	if the user is logged in or not
	 */
	function logged_in( )
	{
		$lcheck = $this->CI->db->query( "SELECT * FROM `users` WHERE
										username='" . mysql_real_escape_string( $this->CI->session->userdata( 'username' ) ) . "' AND
										password='" . mysql_real_escape_string( $this->CI->session->userdata( 'password' ) ) . "'" );
		if( $lcheck->num_rows( ) == 1 )
		{
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Forgot Username or Password
	 *
	 * @access	public
	 * @param	string	the email
	 * @param	integer	length of new password
	 * @return	boolean	if the email was found and email was sent
	 */
	function forgot( $email, $length )
	{
		$qemail = $this->CI->db->query( "SELECT email FROM `users` WHERE email='" . mysql_real_escape_string( $email ) . "'" );
		if( $qemail->num_rows( ) > 0 )
		{
			/**
			 * For strict servers [Home Servers?]
			 */
			$new = '';
			
			for( $i = 1; $i <= $length; $i++ )
			{
				$new .= rand( 1, 9 );
			}
			
			$this->CI->db->query( "UPDATE `users` SET password='" . sha1( md5( $new ) ) . "' WHERE email='" . mysql_real_escape_string( $email ) . "'" );
			
			mail( $email, "New Password", "Your new password is: " . $new );
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Get Data
	 *
	 * @access	public
	 * @param	string	the username
	 * @param	string	what row to grab
	 * @return	string	the data
	 */
	function getData( $username, $what )
	{
		$lcheck = $this->CI->db->query( "SELECT " . mysql_real_escape_string( $what ) . " FROM `users` WHERE
										username='" . mysql_real_escape_string( $username ) . "'" );
		if( $lcheck->num_rows( ) == 1 )
		{
			$data = $lcheck->row( );
			return $data->$what;
		} else {
			return 'Username or row does not exist.';
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Get Age
	 *
	 * @access	public
	 * @param	string	birth-day
	 * @param	string	birth-month
	 * @param	string	birth-year
	 * @return	integer	how many years
	 */
	function getAge( $day, $month, $year )
	{
		$now_year = date( "Y" );
		$now_month = date( "m" );
		$now_day = date( "d" );
		
		$years = $now_year - $year;
		$months = $now_month - $month;
		if( $months < 0 )
		{
			$years--;
		}
		else if( $months == 0 )
		{
			$days = $now_day - $day;
			if( $days < 0 )
			{
				$years--;
			}
		}
		
		return $years;
	}
}
// END User Class
?>