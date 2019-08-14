# php-secure-auth
This is security module for PHP sites to implement login, logout and session management features without worrying about security as it is already taken careof.

This module is built on PHP OOP concept to initiate sessions and to check if user is logged in or not and perform some task if user is logged in or otherwise show login page.


# Getting Started
Working with this framework is very easy and can be implemented in many ways. We provide a skeleton php app here which has an Index page as the entry of the app which cannot be viewed without logging in user. If browser agent calls for this app withut logging into the app, it will redirect the user to login page and will force the user to login to view any other pages in the app. Once the user is logged in, it will redirect the user back to index page which will show the content and has a logout option to kill the login session.

Please note the directory structure for this framework :
        secure-auth/
          --index.php
          --login.php
          --logout.php
          --processLogin.php
          --secureAuth.php

Here the only file we require to implement this feature is "secureAuth.php" while the rest of the files are skeleton files to demonstrate this feature in action. You can have your own version of the below files - 
  
          --index.php    : Entry page to the app while you can name it anything your web server understands.
          --login.php    : Login form displayed to the user. You can create your owne version of the form. This is very basic without css.
          --logout.php   : Simple call to process logout function in secureAuth.php "logoutUser()"
          --processLogin.php : This is just another php skeleton file to handle the form data and to forward the values to SecureAuth class                                in secureAuth.php

# Project Information
secureAuth.php file contains a class called SecureAuth which carries out entire login, logout and session management processes. It has several methods define to process these functions, which we will look into detail now.

      public function __construct($connection)
     
This function is the constructor function for class "SecureAuth" which instantiates the class object. It accepts only one argument i.e. connection variable name to your database. If you are using MYSQL or any other RDBMS you will need to create a connection string and store it to any variable and pass the variable to this constructor function while creating an instance of this class. For example,
     
     $connection = mysqli_connect("localhost","my_user","my_password","my_db");
     $secureAuthCheck = new SecureAuth($connection);
 
 Once the class is initialised, the constructor function will now check if there are any active session or not and it has few logical conditions such as  - 
      
        session exists and user is logged in
        session exists but user is not logged in
        session does not exists at all

If session exists and user is logged in, it will fetch the current user id, session token and auth status from the session object and assign it to the "userId", "token" and "isAuthenticated=1(bool true)" variables declared in SecureAuth class. If second logical condition returns true i.e., session exists but user is not logged in then it will set "isAuthenticated" variable declared in SecureAuth class to 0 which is boolean false and fetch session token from session variable and assign it to "token" variable declared in SecureAuth class. However, if both of the first condition fails and there does not exists any session which may be the case that user pressed logout button or user is visiting this site or app as a fresh user then it will start the session and create a session variable and assign session token.

The next function which this class has to login an user given the user has already submitted username and password in login form and hit the login button which in turns just submits the form. This class will deal with verifying if a match is found, if yes then will check for password hashes(important), if username match is found and password hash stored in db matches with the hash created from the password supplied by the user then it redirects the user to index page or home page which was restricted earlier or else returns the user back to login form.
    
            public function loginUser($username,$password)

This function has two required arguments username and password which can be supplied by invoking this method against our class object which we instantiated before :
        
          $secureAuthCheck = new SecureAuth($connection);
          $secureAuthCheck->loginUser($username, $password);
 
 Okay now we have to discuss few security features it includes as a package - 
  1. It deals with SQL injection attack to make sure there are no SQL statements entered by the user. If SQL injection is attempted then      it will just do a regular expression escapes to mark as them normal words and not a meaningful characters.
  2. It also updates the session variables to store the current user context and auth status and finally a redirection mechanism to          redirect the user to index page, if validation was successful or else login page.
  3. While you may be thinking this one function is itself doing all these actions then you're probably incorrect here. It just works for escaping sql characters and stores them to class variables and then passes those escaped variables to other functions for meaningful actions.
  
The other function which is the opposite to the function above is definitely required to complete this framework otherwise it is not useful enough. Yes, it is called Logout and the function declartion in our class is as below :
    
            public function logoutUser()

Yes, you see it correctly, it does not need any argument as this function simply unset the session variables responsible to remember user and auth status and destroys the session completely. We could have just destroyed the session without unsetting the session variables, but that could cause replay attacks, so as our name goes secure-auth, we tried to make it secure by flushing out the values of session variables. Checkmate!

Another useful function which tells the framework that login is mandatory for any page is 
    
            public function loginRequired()

This function checks if user is already logged in or not. If yes then just update the class variable which is responsible for this called "loginRequiredVar" to 1 otherwise if user is not logged in it will redirect the user to login page. Okay now you may be assuming how about I want to show a page to users who aren't logged in and also to logged in users but with one or more changed UI or information then instead of using this function we can go with workaround : 

            if($secureAuthCheck->loginRequiredVar){
            //do something else for user who are logged in
            }else{
            //do something else for user who aren't logged in
            }

Enough of reading through this. Below is the basic snippet required for few of the functions we discussed above :

              <?php
                include('secureAuth.php');
                require_once('../disallow_Access/config.php');
                $secureAuthCheck = new SecureAuth($con_user);
                $secureAuthCheck->loginRequired();

                if($secureAuthCheck->loginRequiredVar){
                    echo "Continue here with your app as per logged in user values";
                }else{
                    echo "Redirecting you to login now";
                }

# Final But Very Important Information
For default this framework thinks you have a database where a table for users is already created. You will need to configure your database and connection info in a file called config.php which does not come with this skeleton app. You must create one with your database values and save it to the root folder of this directory. For its security, you will need to google how to protect files with .htaccess . Values for your user table need to be updated in checkCredentials() method defined within this class on line 38 of this skeleton app in secureAuth.php. Whatever logic you have to implement regarding querying your user table has to be implemented here. For example, with this skeleton app we are querying user_id and user_password from xyz_users table and we are trying to match the user email address in the database with one entered in the login form. We accept username in the form of email address and hence we are filtering users by their email address. You may change this to anything as per your business logic but user_id and user_password is must to have this framework work. 

Additionaly you may modify these methods according to your need and it will function exactly.
Happy learning @ https://hackinginnovated.com

I am open to feedback and comments to make neccessary changes in terms of functionality or security.
