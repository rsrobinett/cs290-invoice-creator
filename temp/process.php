<?<?php require('includes/db.php'); 
//if form has been submitted process it
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//very basic validation
	if(strlen($_POST['username']) < 3){
		//$error[] = 'Username is too short.';
		echo '1';
	} else {
		$stmt = $db->prepare('SELECT username FROM user WHERE username = :username');
		$stmt->execute(array(':username' => $_POST['username']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!empty($row['username'])) {
			//$error[] = 'Username provided is already in use.';
			echo '2';
		}
	}
	if(strlen($_POST['password']) < 3){
		// $error[] = 'Password is too short.';
		echo '3';
	}
	if(strlen($_POST['passwordConfirm']) < 3){
		echo '4';
		// $error[] = 'Confirm password is too short.';
	}
	if($_POST['password'] != $_POST['passwordConfirm']){
		echo '5';
		// $error[] = 'Passwords do not match.';
	}
	//if no errors have been created carry on
	if(!isset($error)){
		//hash the password
		echo $_POST['password'];
		$hashedpassword = $user->password_hash($_POST['password']);
		try {
			//insert into database with a prepared statement
			$stmt = $db->prepare('INSERT INTO user (username,hashpass) VALUES (:username, :password)');
			$stmt->execute(array(
				':username' => $_POST['username'],
				':password' => $hashedpassword
			));
			echo "ok";
// 			header('Location: main.php');
		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    // $error[] = $e->getMessage();
		    echo $e->getMessage();
		}
	}
}
?>