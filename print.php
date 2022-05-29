<?php
    $pageTitle = "Print - Page";
    include "layouts/header.php";

    session_start();


    if(!isset($_SESSION["username_sess"])){
        header("Location: index.php");
        exit();
    // }else if(!isset( $_SERVER['HTTP_REFERER'])){
    //     header('Location: dashboard.php');
    //     exit();
    // 
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
		$taskstats = 1;
        

        // Fetch Ifo 
        $fetch_info = $conn->prepare("SELECT * FROM tasks WHERE UserID=? & taskstats=?");
        $fetch_info->execute([$userID,$taskstats]);
        $get_info = $fetch_info->fetchAll();


        
   ?>


    <body id="page-top d-flex" onload="window.print()">
        <div class="header">
            <img src="img/123GO.png" alt="" width="400"  />
        </div>
        <div class="information">
        <table class="table table-striped m-4">
            <thead>
                <tr>
                <th scope="col">Client Name</th>
                <th scope="col">Client Phone</th>
                <th scope="col">Problem</th>
                <th scope="col">Fixed By </th>
                </tr>
				
            </thead>
            <tbody>
			
				<?php
					foreach($get_info as $get_inf){ ?>
						<tr>
							<th scope="row"><?php echo $get_inf["taskown"] ?></th>
							<td>ss</td>
							<td><?php echo $get_inf["taskprb"] ?></td>
							<td> <?php echo $Fullnm ?></td>
						</tr><?php
					} 
				?>
            </tbody>
            </table>
            
        </div>
        


    <?php
        include "layouts/footer.php";

    }