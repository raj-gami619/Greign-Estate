<?php
include "Crud.php";
include "authenticator.php";
include_once 'DBConnector.php';

class User implements Crud, Authenticator{
  private $email;
  private $password;
  private $user_id;
  private $con;
  private $city;
  private $title;
  private $beds;
  private $baths;
  private $price;
  private $filetoUpload;

//Using class constructor to initialize values member variables cant be instantiated from elsewhere; They are private
function __construct($email="",$password="",$city="",$title="",$beds="",$baths="",$price="",$filetoUpload=""){
$this->email = $email;
$this->password = $password;
$this->city = $city;
$this->title = $title;
$this->beds = $beds;
$this->baths = $baths;
$this->price = $price;
$this->filetoUpload=$filetoUpload;

}

public static function create(){
	$instance= new self ();
	return $instance;
}
public function setUserId($user_id){
$this->user_id= $user_id;
}

//user id getter
public function getUserId(){
return $this->user_id;
}
public function setEmail ($email) {
	$this->email = $email;
}

//username getter
public function getEmail(){
	return $this->email;
}

//password setter
public function setPassword($password){
	$this->password= $password;
}

//password getter
public function getPassword(){
	return $this->password;
}

//username setter
public function setCity ($city) {
	$this->username = $city;
}

//username getter
public function getCity(){
	return $this->city;
}

//password setter
public function setTitle($title){
	$this->password= $title;
}

//password getter
public function getTitle(){
	return $this->title;
}

//user id setter
public function setBeds($beds){
$this->beds= $beds;
}

//user id getter
public function getBeds(){
return $this->beds;
}
public function setBaths($baths){
$this->baths= $baths;
}

//user id getter
public function getBaths(){
return $this->baths;
}

public function setPrice($price){
$this->price= $price;
}

//user id getter
public function getPrice(){
return $this->price;
}

public function save($con){
$email=$this->email;
$password=$this->password;
$city= $this->city;
$title= $this->title;
$beds= $this->beds;
$baths= $this->baths;
$price= $this->price;

$file=$this->filetoUpload;
$fileln= new FileUploader();
$fileln->uploadFile();



$res= mysqli_query($con, "INSERT INTO properties (email,password,city,title,beds,baths,price,image) VALUES ('$email','$password','$city','$title','$beds','$baths','$price','$file')") or die ("Error " . mysqli_error($con));
return $res;
}

public function readAll(){
  $res=mysqli_query($con,"SELECT * FROM properties");
  return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

public function readUnique(){
return null;
}

public function search(){
return null;
}

public function update(){
return null;
}

public function removeOne(){
return null;
}

public function removeAll(){
return null;
}

public function validateForm(){
	//Return true if the values are not empty
	$email=$this->email;
	$password=$this->password;
	$city = $this->city;
	$title = $this->title;
	$beds = $this->beds;
	$baths=$this->baths;
	$price=$this->price;
	
	if ($email=""  ||$password=""){
		return false;
	}
	return true;
}

public function createFormErrorSessions(){
	session_start();
	$_SESSION['form_errors'] = "All fields are required";
}

public function hashPassword(){
	//inbuilt function hashes out password
	$this->password= password_hash($this->password, PASSWORD_DEFAULT);
}



public function createUserSession(){
	session_start();
	$_SESSION['email'] = $this->getEmail();
	$_SESSION['id']=$this->getUserId();
}

public function logout(){
	session_start();
	unset($_SESSION['email']);
	session_destroy();
	header("Location:login.php");
}

public function isUserExist(){
	$con = new DBConnector;
	$found = false;
	$res = mysqli_query($con->conn,"SELECT * FROM users") or die("Error". mysqli_error($con->conn));

	while ($row = mysqli_fetch_array($res) ) {
		if ($this->getEmail() == $row['id']) {
			$found = true;
		}
	}
	$con->closeDatabase();
	return $found;
}
public function DBConnect() {
	$this->con = new DBConnector;
}

public function DBClose() {
	$this->con->closeDatabase();
}
public function isPasswordCorrect(){
	$con= new DBConnector;
	$email = $this->getEmail();
	$found= false;
	$res= mysqli_query($con->conn, "SELECT * FROM users where email='$email'") or die ("Error" . mysqli_error($con));
	
	while ($row=mysqli_fetch_array($res)){
		if (password_verify($this->getPassword(), $row ['password']) && $this->getEmail() == $row['email']){
			$this->setUserId($row['id']);
			$found= true;
		}
	}
	
	//close the database connection 
	$con->closeDatabase();
	return $found;
	//return true
}
public function login(){
	if ($this->isPasswordCorrect()){
		//password is correct, so we load the home page
		header("Location:index.php");
	}
}

}

?>