<?php
class SecureAuth {
    private $username;
    private $password;
    private $token;
    private $isAuthenticated;
    private $userId;
    private $connection;
    public $loginRequiredVar;
    
    public function __construct($connection){
        
        $this->connection = $connection;

        if(!empty($_SESSION["token"]) && $_SESSION["authenticated"]==TRUE){
            $this->userId = $_SESSION["user_id"];
            $this->token = $_SESSION["token"];
            $this->isAuthenticated = 1;
        }else{
            if(!empty($_SESSION["token"] && $_SESSION["authenticated"]==FALSE)){
                $this->isAuthenticated = 0;
                $this->token = $_SESSION["token"];
            }else{
                if(empty($_SESSION["token"])){
                    session_start();
                    $_SESSION["token"] = session_id();
                }
            }
        }
    }

    public function loginUser($username,$password){
        $this->username = mysqli_real_escape_string($this->connection, $username);
        $this->password = mysqli_real_escape_string($this->connection, $password);
        self::checkCredentials();
    }

    private function checkCredentials(){
        $stmt = "SELECT user_id, user_password FROM xyz_users WHERE user_email='".$this->username."'";
        $stmt = mysqli_query($this->connection, $stmt);
        
        if(mysqli_num_rows($stmt)>0){
            $user = mysqli_fetch_assoc($stmt);
            if(hash_equals($user["user_password"] , crypt($this->password,$user["user_password"]))){
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["authenticated"] = TRUE;
                self::loggedInRedirect();
            }else{
                $_SESSION["authenticated"] = FALSE;
                self::loggedInRedirect();
            }
        }else{
            $this->isAuthenticated = 0;
            $this->token = $_SESSION["token"];
            self::loggedInRedirect();
        }
    }

    public function logoutUser(){
        unset($_SESSION["authenticated"]);
        unset($_SESSION["user_id"]);
        session_destroy();
        self::loggedInRedirect();
    }

    public function loginRequired(){
        if(!empty($_SESSION["user_id"]) && $_SESSION["authenticated"]==TRUE){
            $this->loginRequiredVar = 1;
        }else{
            header('Location: login.php');
        }
    }

    public function loggedInRedirect(){
        if(!empty($_SESSION["user_id"]) && $_SESSION["authenticated"]==TRUE){
            $this->loginRequiredVar = 1;
            header('Location: index.php');
        }else{
            header('Location: login.php');
        }
    }
}
?>