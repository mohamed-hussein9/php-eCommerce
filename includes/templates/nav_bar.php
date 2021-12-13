 <nav>
     <div class="logo">
         <img src="admin/layout/images/logo.png" alt="">
     </div>
     <div class="slide_nav">
         <p class="line "></p>
         <p class="line line2"></p>
         <p class="line "></p>
     </div>
     <!-- part1 -->
     <div class="left ">
         <?php echo $nav = '
        <div class="n_home">
		    <div>
            <a href="index.php">
                <i class="fas fa-home fa-fw"></i>
                ' . lang('HOME') . '</a>
            </div>
        </div>
      
        ';
            echo '<div>';
            $getcat = GetAllFrom('*', 'categories', ' ', 'Ordering', 'ASC');
            foreach ($getcat as $row_r) {

                echo $n1 = '<a href=
            "index.php?page=' . str_replace(' ', '-', $row_r['Name']) . '&cat_id=' . $row_r['ID'] . '"
            >' . $row_r['Name'] . '</a>';
            }
            echo '</div>';

            ?>


     </div>
     <div class="cart_icon">
         <a href="cart.php"><i class=" fas fa-cart-arrow-down"></i></a>
         <?php
            $uid = '';

            $ip = getIPAddress();
            $stmt = $con->prepare('SELECT id FROM cart WHERE ip_address=? ');
            $stmt->execute(array($ip));
            $cart_count = $stmt->rowCount();
            if ($cart_count > 0) {
                echo '<span><a href="cart.php">' . $cart_count . '</a></span>';
            } ?>

     </div>

     <!-- notification -->
     <?php if (isset($_SESSION['User'])) { ?>

         <div class="notification">
             <i class="fas fa-globe"></i>
             <?php $c_c = getCount('n_id', 'users_notification', 'WHERE read_state=0 And user_id=' . $_SESSION['UserID']);
                if ($c_c > 0) {
                    echo '<span>' . $c_c . '</span>';
                } ?>
             <div class="notification-box">
                 <div class="notification-box-content">
                     <?php
                        $get_notfs = GetAllFrom('*', 'users_notification', 'WHERE user_id=' . $_SESSION['UserID'], 'date');
                        foreach ($get_notfs as $notf) {
                            echo '<a href="#">';
                            echo $notf['notification'];
                            echo '<p class="show_date">' . $notf['date'] . '</p>';

                            echo '</a>';
                        }

                        ?>
                 </div>

             </div>

         </div>

     <?php } ?>
     <div class="search_items">
         <form action="index.php" method="get">
             <input autocomplete="off" type="text" name="search" placeholder="search for product">
             <input type="submit" value="search">

         </form>
     </div>

     <!-- part2 -->
     <?php if (isset($_SESSION['User'])) { ?>
         <div class="center">


             <?php
                $sessionUser = $_SESSION['UserID'];
                $get_avatar = GetAllFrom("avatar,UserId", "users", "WHERE UserID= $sessionUser", "UserID", "", "fetch()");
                $avatar_src = !empty($get_avatar['avatar']) ? $get_avatar['avatar'] : 'default.png'; ?>

             <img src="admin/upload/avatars/<?php echo $avatar_src; ?>" alt="' . $result['Username'] . '">
             <p><?php echo $_SESSION['User']; //echo $_SESSION['Username'];  
                ?></p>
             <div class="profile_options">
                 <div class="close_p_options"><i class="fas fa-times-circle fa-fw"></i>Close</div>
                 <div onclick="location.href = 'profile.php?user_id=<?php echo $get_avatar['UserId'] ?>';"><i class="fas fa-user-edit fa-fw"></i> <?php echo lang('PROFILE'); ?></div>
                 <div><i class="fas fa-user-cog fa-fw"></i> <?php echo lang('SETTING'); ?></div>
                 <div onclick="location.href = 'additem.php';"><i class="fas fa-user-cog fa-fw"></i> <?php echo lang('ADD-ITEM'); ?></div>

                 <div onclick="location.href = 'logout.php';"><i class="fas fa-sign-out-alt fa-fw"></i><?php echo lang('LOGOUT'); ?></div>


             </div>

         </div>
     <?php } else { ?>


         <div class="login_side">
             <p class=""><a href="login.php">LogIn</a></p>

         </div>
     <?php } ?>

     <!-- part3 -->







 </nav>

 <div class="slide-down">
     <?php echo $nav;

        echo '<div>';
        foreach ($getcat as $row_r) {
            echo '<a href="index.php?page=' . str_replace(' ', '-', $row_r['Name']) . '&cat_id=' . $row_r['ID'] . '">' . $row_r['Name'] . '</a>';
        }
        echo '</div>';
        ?>



 </div>
 <div style="height:100px">

 </div>

 </div>