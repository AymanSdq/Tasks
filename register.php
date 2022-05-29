<?php
    $pageTitle = "Register - Page";
    include "layouts/header.php";

    session_start();

    if(isset($_SESSION["username_sess"])){
        header("Location: dashboard.php");
        exit();
    }else{
        if(isset($_POST["register"])){
            // Caling Input fields
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $fullname = $_POST['fullname'];
            $password = trim($_POST['pass']);
            $repeatpass = trim($_POST['repass']);
            // 
            // Checking if it's not empty 
            if(empty($username) || empty($email) || empty($fullname) || empty($password) || empty($repeatpass)){
                header("Location: register.php?msg=empty_string");
                exit();
            } // Cheking if the password match the repeat password
            else if( $password != $repeatpass){
                header("Location: register.php?msg=password_not_match");
                exit();
            } //Cheking if the username or email already exist
            $check_user_exist = $conn->prepare("SELECT * FROM users WHERE Username=? or Email=?");
            $check_user_exist->execute([$username,$email]);
            $user_exist_counter = $check_user_exist->rowCount();
            if($user_exist_counter >1 ){
                header("Location: register.php?msg=User_already_exist");
                exit();
            }// IF user don't existe Insert
            else{
                $hashedpass = password_hash($password, PASSWORD_DEFAULT);

                $create_user_stmt = $conn->prepare("INSERT INTO users (Username,Email,FullName,Password) VALUES(?,?,?,?)");
                $create_user_stmt->execute([$username,$email,$fullname,$hashedpass]);
                if($create_user_stmt){
                    header("Location: index.php?");
                    exit();
                } // If the account not created
                else{
                    header("Location: register.php?msg=Error_creating_account_please_try_again");
                    exit();
                }

            }
        }

        ?>
        <body class="bg-gradient-primary">

            <div class="container">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                                    </div>
                                    <form class="user" method="POST" action="">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="exampleinputusername"
                                                name="username" placeholder="Username" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                                                name="email" placeholder="Email Address" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="exampleInputEmail"
                                                name="fullname" placeholder="Full Name" required>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="password" class="form-control form-control-user"
                                                    name="pass" id="exampleInputPassword" placeholder="Password" required>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="password" class="form-control form-control-user"
                                                    name="repass" id="exampleRepeatPassword" placeholder="Repeat Password" required>
                                            </div>
                                        </div>
                                        <button type="submit" name="register" class="btn btn-primary btn-user btn-block">
                                            Register Account
                                        </button>
                                        <hr>
                                        <div class="text-center">
                                            <a class="small" href="forgot-password.html">Forgot Password?</a>
                                        </div>
                                        <div class="text-center">
                                            <a class="small" href="index.php">Already have an account? Login!</a>
                                        </div>    
                                    </form>
                                    <hr>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php
            include "layouts/footer.php";
        }

