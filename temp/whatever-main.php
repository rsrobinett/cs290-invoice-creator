// $userid = $_SESSION['userid'];


// include_once "$_SERVER[DOCUMENT_ROOT]/src/dbCredentials.php";

// include_once "$_SERVER[DOCUMENT_ROOT]/src/dbConnection.php";

// include_once "$_SERVER[DOCUMENT_ROOT]/src/validation.php";

// global $mysqli;

// if (!$mysqli) {
//     $mysqli = createDBConnection($dbhost, $dbuser, $dbpass, $dbname);
// }



// // if (isset($_POST['register'])) {   
// //     echo "registering user";
// //     registerUser($mysqli, $db);
// //     echo $_SESSION['username']."is logged in";
// // } else if(isset($_POST['login'])) {   
// //     echo "loging in user";
// //     login($mysqli, $db);
// //     echo $_SESSION['username']."is logged in";
// // }
    
// function setSessionUsername() {
//     if(isset($_POST['username']) || ($_POST['username'] !== $_SESSION['username'])){
//       $_SESSION['username'] = $_POST['username'];
//     }       
// }

// function login($mysqli, $db) {
//     setSessionUsername();
// }

// function usernameAndPasswordInDB($mysqli, $db){
//     if (!$mysqli || $mysqli->connect_error) {
//             echo "<div class='error'>Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "</div>";
//     }
    
//     $username = trim($_POST['username']);
//     $hashpass = trim(base64_encode(hash('sha256',$_POST['password'])));

//     if(!($stmt = $mysqli->prepare("SELECT username FROM $db.user WHERE username = ? AND hashpass = ?"))){
//         echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
//     }

//     if (!$stmt->bind_param("ss", $username, $hashpass )) {
//         echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
//     }

//     if (!$stmt->execute()) {
//         echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
//     }   
    
//     $stmt->store_result();
    
//     if($stmt->num_rows === 1){
//         unset($stmt);
//         return true;
//     }

//     unset($stmt);
//     return false;
// }


    
// if(($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['username']) && isset($_POST['password'])){
//     if (usernameAndPasswordInDB($mysqli, $db)) {
//       login($mysqli, $db);
//       echo 'ok';
//     } else {
//         echo 'incorrect username or password';
//     }
//     return;
// }








// function registerUser($mysqli, $db){
//     $userid = addToUser($mysqli, $db);
//     addToCompany($mysqli, $db, $userid);
//     setSessionUsername();
// }

// /*
// function setSessionUsername(){
//     if(isset($_POST['username']) || ($_POST['username'] !== $_SESSION['username'])){
//       $_SESSION['username'] = $_POST['username'];
//     }       
// }


// function login($mysqli, $db){

//     if(!usernameAndPasswordInDB($mysqli, $db)){
//       header("Location: ".getPath()."/login.php?action=logout", true);    
//     }

//     setSessionUsername();
// }
// */
// /*
// function usernameAndPasswordInDB($mysqli, $db){
//     if (!$mysqli || $mysqli->connect_error) {
//             echo "<div class='error'>Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "</div>";
//     }
    
//     $username = trim($_POST['username']);
//     $hashpass = trim(base64_encode(hash('sha256',$_POST['password'])));
    
//     echo "usernameAndPasswordInDB";
//     echo "username = $username";
//     echo "hashpass = $hashpass";
    
//     if(!($stmt = $mysqli->prepare("SELECT username FROM $db.user WHERE username = ? AND hashpass = ?"))){
//         echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
//     }

//     if (!$stmt->bind_param("ss", $username, $hashpass )) {
//         echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
//     }

//     if (!$stmt->execute()) {
//         echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
//     }   
    
//     $stmt->store_result();
    
//     if($stmt->num_rows === 1){
//         unset($stmt);
//         return true;
//     }

//     unset($stmt);
//     return false;
// }
// */  

// function addToUser($mysqli, $db){
//     if (!$mysqli || $mysqli->connect_error) {
//             echo "<div class='error'>Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "</div>";
//     } 
    
//     if(!($stmt = $mysqli->prepare("INSERT INTO $db.user (username, hashpass) VALUES (?, ?)"))){
//         echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
//     }
    
//     $username = trim($_POST['username']);
//     $hashpass = trim(base64_encode(hash('sha256',$_POST['password'])));


//     echo "username = $username <p>";
//     echo "hashpass = $hashpass <p>";
    

//     if (!$stmt->bind_param("ss", $username, $hashpass )) {
//         echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
//     }   
    
//     if (!$stmt->execute()) {
//         echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
//     }
    
//     $newuserid = $stmt->insert_id;
    
//     unset($stmt);
    
//     return $newuserid;
// }

// function addToCompany($mysqli, $db, $userid){
//     if (!$mysqli || $mysqli->connect_error) {
//             echo "<div class='error'>Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "</div>";
//     } 
    
//     if(!($stmt = $mysqli->prepare("INSERT INTO $db.company (userid, companyname) VALUES (?, ?)"))){
//         echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
//     }

//     if(isset($_POST['companyname']))
//     $companyname = $_POST['companyname'];
//     //$firstname = $_POST['firstname'];
//     //$lastname = $_POST['lastname'];
//     //$address = $_POST['address'];

//     echo "companyname = $companyname <p>";
    

//     if (!$stmt->bind_param("issss", $userid, $nam)) {
//         echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
//     }   
    
//     if (!$stmt->execute()) {
//         echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
//     }
//     unset($stmt);
// }

// function getUserIDFromUserName($mysqli, $db){

//     if(!($stmt  = $mysqli->prepare("SELECT userid FROM $db.user where username = ?"))){
//         echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
//     }
        
//     $username = $_SESSION['username'];

//     if (!$stmt->bind_param("s", $username )) {
//         echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
//     } 
    
//     if (!$stmt->execute()) {
//         echo "<div class='error'>Execute failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
//     } 
    
//     $userid = NULL;
    
//     if (!$stmt->bind_result($userid )) {
//         echo "<div class='error'>Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
//     }      
    
//     $sessionuserid = NULL;
    
//     while ($stmt->fetch()) {
//         $sessionuserid = $userid;
//     }
    
//     unset($stmt);
    
//     return $sessionuserid;

// }

// function printCompanyInfo($mysqli, $db){

//     if(!($stmt  = $mysqli->prepare("SELECT name FROM $db.company where userid = ?"))){
//         echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
//     }
    
//     $userid = getUserIDFromUserName($mysqli, $db);
    
//     if (!$stmt->bind_param("s", $userid )) {
//         echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
//     } 
    
//      if (!$stmt->execute()) {
//         echo "<div class='error'>Execute failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
//     } 
    
    
//     $name = NULL;

    
//     if (!$stmt->bind_result($name)) {
//         echo "<div class='error'>Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
//     }
    
    
//     while ($stmt->fetch()) {
//         echo "<p> name = $name";
//     }

//     unset($stmt);

// }


/*    
else{
    echo "How did you get here?";
}

if(!isset($_SESSION['username'])){
  echo "session username is not set";
  //setSessionUsername();
} 
*/
