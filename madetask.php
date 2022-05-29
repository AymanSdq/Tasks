<?php
    $pageTitle = "Done Tasks - Page ";
    include "layouts/header.php";

    session_start();


    if(!isset($_SESSION["username_sess"])){
        header("Location: index.php");
        exit();
    }else if(!isset( $_SERVER['HTTP_REFERER'])){
        header('Location: dashboard.php');
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

        // Calling Data Tables
        $get_task_stat = $conn->prepare("SELECT * FROM tasks WHERE UserID = ? and taskstats=?");
        $get_task_stat->execute([$userID,1]);
        $fetch_task_data = $get_task_stat->fetchall();
        $count_task_row = $get_task_stat->rowCount();

        if(isset($_POST["resetit"])){
            // Calling the ID of the Task
            $id = $_POST["idtask"];
            // Changing Stats
            $change_statement = $conn->prepare("UPDATE tasks SET taskstats=? WHERE ID = ?");
            $change_statement->execute([0,$id]);
            if($change_statement){
                header("Location: madetask.php?msg=task_reset");
                exit();
            }
        }

        if(isset($_POST["deleteit"])){
            // Calling the ID of the Task
            $id = $_POST["idtask"];
            // Changing Stats
            $delete_statement = $conn->prepare("DELETE FROM tasks WHERE ID = ?");
            $delete_statement->execute([$id]);
            if($delete_statement){
                header("Location: madetask.php?task_deleted_success");
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
            
                    </div>
                    <!-- Table of the tasks that didn't do yet -->



                <div class="card m-3">
                    <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped text-center" id="dataTable" width="100%" cellspacing="0">
                                        <thead class="table-success">
                                            <tr>
                                                <!-- <th>ID</th> -->
                                                <th>Task Name</th>
                                                <th>Task Client Name</th>
                                                <th>Client Phone</th>
                                                <th>Task Problem</th>
                                                <th>Task Time End</th>
                                                <th>Fixed By</th>
                                                <th>Options</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php
                                                if($fetch_task_data > 0){
                                                    foreach($fetch_task_data as $fetch_task_dt){?>
                                                        <tr>
                                                            
                                                            
                                                            <th><?php echo $fetch_task_dt["taskname"] ?></th>
                                                            <th><?php echo $fetch_task_dt["taskown"] ?></th>
                                                            <th><?php echo 0 . $fetch_task_dt["NumClt"] ?></th>
                                                            <th><?php echo $fetch_task_dt["taskprb"] ?></th>       
                                                            <th><?php echo $fetch_task_dt["dateend"] ?></th>
                                                            <th><?php echo $Fullnm ?></th>
                                                            <th>
                                                                <form action="" method="POST">
                                                                    <input type="hidden" name="idtask" value="<?php echo $fetch_task_dt["ID"]?>">
                                                                    <button type="submit" name="resetit" class="btn btn-warning m-1">Reset</button>
                                                                    <!-- This is the print Page Edit -->
                                                                        <a class="btn btn-dark m-1" onclick="window.open('print.php?Id=<?php echo  $fetch_task_dt['ID'] ?>', '_blank', 'location=yes,scrollbars=yes,status=yes');"> Print</a>
                                                                        <button type="submit" name="deleteit" class="btn btn-danger m-1">Delete</button>
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
            </div>
            <!-- End of Main Content -->
                


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