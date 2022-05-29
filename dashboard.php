<?php
    $pageTitle = "Dashboard - Page ";
    include "layouts/header.php";

    session_start();


    if(!isset($_SESSION["username_sess"])){
        header("Location: index.php");
        exit();
    }else{
        date_default_timezone_set("Africa/Casablanca");
        //fetching the User information
        $user = $_SESSION["username_sess"];
        
        // Fetching the User ID 
        $fetch_userid_stmt = $conn->prepare("SELECT * from users WHERE Username=?");
        $fetch_userid_stmt->execute([$user]);
        $fetch_dt_user = $fetch_userid_stmt->fetch();
        // Calling the UserID
        $userID = $fetch_dt_user["UserID"];

        $Fullnm = $fetch_dt_user["FullName"];

        //Ading task into dbs 
        if(isset($_POST["addtask"])){   
            //Calling input field
            $task_name = $_POST["taskname"];
            $task_owner = $_POST["taskowner"];
            $task_problem = $_POST["taskprblm"];
            $task_number = $_POST["tasknumber"];
            
            
            // Inserting Information Into dbs
            $date_start = date('Y-m-d H:i:s');
            

            $insert_task_stmt = $conn->prepare("INSERT INTO tasks (UserID,taskname,taskown,NumClt,taskprb,taskmade,datestart)
                                                VALUES (?,?,?,?,?,?,?)");
            $insert_task_stmt->execute([$userID,$task_name,$task_owner,$task_number,$task_problem,$user,$date_start]);
            if($insert_task_stmt){
                header("Location: dashboard.php?msg=task_inserted_success_$task_owner");
                exit();
            }else{
                header("Location: dashboard.php?msg=couldnt_insert_try_again");
                exit();
            }
        }
        // Fetching User ID

        // Fetching Data Tables Information
        $get_task_stat = $conn->prepare("SELECT * FROM tasks WHERE UserID = ? and taskstats=?");
        $get_task_stat->execute([$userID,0]);
        $fetch_task_data = $get_task_stat->fetchall();
        $count_task_row = $get_task_stat->rowCount();
        



        if(isset($_POST["gotoshow"])){
            header("Location: madetask.php?checktask");
            exit();

        }


        // Done The Task Code // 
        if(isset($_POST["doneit"])){
            $time_end = date('Y-m-d H:i:s');
            // Calling the ID of the Task
            $id = $_POST["idtask"];
            $taskst = 1;
            // Changing Stats
            $change_statement = $conn->prepare("UPDATE tasks SET taskstats=? , dateend=? WHERE ID = ?");
            $change_statement->execute([$taskst,$time_end,$id]);
            if($change_statement){
                header("Location: dashboard.php?task_ended_success");
                exit();
            }
        }
        // Edit it 
        if(isset($_POST["editit"])){
            $id = $_POST["idtask"];
            header("Location: edittask.php?Id=$id");
            exit();
        }

        // Delete The Task Code // 
        if(isset($_POST["deleteit"])){
            // Calling the ID of the Task
            $id = $_POST["idtask"];
            // Changing Stats
            $delete_statement = $conn->prepare("DELETE FROM tasks WHERE ID = ?");
            $delete_statement->execute([$id]);
            if($delete_statement){
                header("Location: dashboard.php?task_deleted_success");
                exit();
            }
        }

        // Logout from website code 
        if(isset($_POST['logout'])){
            session_unset();
            session_destroy();
            header("Location: index.php");
            exit();
        }
        
    ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <h3><a href="dashboard.php">123GO</a></h3>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $Fullnm ?></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
                <div class="card text-center m-3">
                    <div class="card-header">
                        Add a Task
                    </div>
                    <div class="card-body ">
                        <h5 class="card-title">Add Tasks </h5>
                        <p class="card-text">Here You can Add Tasks for your job that you have to complete</p>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Add Tasks</button>
                        
                        
                            <!-- Start Modal  -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="POST">
                                    <!-- Task name -->
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Task Name:</label>
                                        <input type="text" name="taskname" class="form-control" id="recipient-name" required>
                                    </div>
                                    <!-- Task own -->
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Task Owner:</label>
                                        <input type="text" name="taskowner" class="form-control" id="recipient-name" required>
                                    </div>
                                    <!-- Task own -->
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Phone Number:</label>
                                        <input type="phone" name="tasknumber" class="form-control" id="recipient-name" required>
                                    </div>
                                    <!-- Task Probleme -->
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Task Problem:</label>
                                        <input type="text" name="taskprblm" class="form-control" id="recipient-name" >
                                    </div>
                                    
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="addtask" class="btn btn-primary">Add It</button>
                                    </div>
                                </form>
                                
                            </div>
                            
                            </div>
                            
                        </div>
                        </div>
                        <!-- end model  -->
                    </div>
                        <div class="card-footer">
                            <form action="" method="POST">
                                <button type="submit" class="btn btn-success" name="gotoshow" >Show Task</button>
                            </form>
                            
                        </div>
                </div>
                <!-- end of the add taks card  -->

            </div>
                </div>
                
            </div>
            <!-- End of Main Content -->
                <!-- Table of the tasks that didn't do yet -->
                <div class="card m-3">
                    <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped text-center" id="dataTable" width="100%" cellspacing="0">
                                        <thead class="table-danger">
                                            <tr>
                                                <th>ID</th>
                                                <th>Task Name</th>
                                                <th>Task Client Name</th>
                                                <th>Client Phone</th>
                                                <th>Task Problem</th>
                                                <th>Task Time Start</th>
                                                <th>Options</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php
                                                if($fetch_task_data > 0){
                                                    foreach($fetch_task_data as $fetch_task_dt){?>
                                                        <tr>
                                                            <th><?php echo $fetch_task_dt["ID"] ?></th>
                                                            <th><?php echo $fetch_task_dt["taskname"] ?></th>
                                                            <th><?php echo $fetch_task_dt["taskown"] ?></th>
                                                            <th><?php echo 0 . $fetch_task_dt["NumClt"] ?></th>
                                                            <th><?php echo $fetch_task_dt["taskprb"] ?></th>
                                                            <th><?php echo $fetch_task_dt["datestart"] ?></th>
                                                            <th>
                                                                <form action="" method="POST">
                                                                    <input type="hidden" name="idtask" value="<?php echo $fetch_task_dt["ID"]?>">
                                                                    <button type="submit" name="doneit" class="btn btn-success">Done</button>
                                                                    <button type="submit" name="deleteit" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger">Delete</button>
                                                                </form>
                                                            </th>
                                                        </tr>
                                                    <?php
                                                    }
                                                    
                                                }else{
                                                    echo "No Data Yet";
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                    </div>
                </div>


        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <form action="" method="post">
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <form action="" method="post">
                            <button type="submit" class="btn btn-primary" name="logout">Logout</button>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php
        include "layouts/footer.php";

    }