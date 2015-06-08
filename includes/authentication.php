<?php
class User {
    private $_db;
    function __construct($db){
    	$this->_db = $db;
    }
    
	private function get_user_hash($username){	
		try {
			$stmt = $this->_db->prepare('SELECT hashpass FROM user WHERE username = :username');
			$stmt->execute(array('username' => $username));
			
			$row = $stmt->fetch();
			return $row['hashpass'];
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	 public function password_hash($password) {
	 	return trim(base64_encode(hash('sha256', $password)));
    }
	
	 public function password_verify($password, $hash) {
	 	// might not need to do all this
        $ret = trim(base64_encode(hash('sha256', $password)));
        if (!is_string($ret) || strlen($ret) != strlen($hash) || strlen($ret) <= 13) {
            return false;
        }
        $status = 0;
        for ($i = 0; $i < strlen($ret); $i++) {
            $status |= (ord($ret[$i]) ^ ord($hash[$i]));
        }
        return $status === 0;
    }

	public function login($username, $password){
		$hashed = $this->get_user_hash($username);
		
		if($this->password_verify($password, $hashed) == 1){
		    
		    $_SESSION['loggedin'] = true;
		    return true;
		} 	
	}
		
	public function logout(){
		session_destroy();
	}
	
	public function is_logged_in(){
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
			return true;
		}		
	}
}
?>