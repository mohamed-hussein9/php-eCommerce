 <nav>
     <div class="logo">
         <img src="layout/images/logo.png" alt="">
     </div>
     <div class="slide_nav">
        <p class="line "></p>
        <p class="line line2"></p>
        <p class="line "></p>
    </div>
    <!-- part1 -->
    <div class="left ">
         <?php echo $nav = '
        <div>
		    <div>
            <a href="dashboard.php">
                <i class="fas fa-home fa-fw"></i>
                ' . lang('HOME') . '</a>
            </div>
			<div>
            <a href="categories.php">
                <i class="fas fa-align-justify fa-fw"></i>
                ' . lang("CATEGORY") . '

            </a>
			</div><div>
            <a href="items.php">
                <i class="fas fa-shopping-bag fa-fw"></i>
                ' . lang("ITEMS") . '

            </a>
			</div><div>
            <a href="members.php">
                <i class="fas fa-users fa-fw"></i>
                ' . lang("MEMBERS") . '

            </a>
            </div>
            <div>
			<a href="comments.php">
                <i class="fas fa-comment-alt fa-fw"></i>
                ' . lang("COMMENTS") . '

            </a>
			</div>
            
            <div>
            <a href="#">
                <i class="fas fa-chart-line fa-fw"></i>
                ' . lang("STATISTICS") . '

            </a>
			</div>
            

        </div>'; ?>
         <!-- notification -->
     </div>
     <div class="notifications">
         <div class="notification comments-notif">
             <i class="fas fa-comment-alt"></i>
             <?php $c_c = getCount('comment_id', 'comments', 'WHERE status=0');
                if ($c_c > 0) {
                    echo '<span>' . $c_c . '</span>';
                } ?>
             <div class="notification-box">
                 <a href="comments.php?pending_comments=pending">
                     You have <strong><?php echo $c_c ?></strong> Comments needed your accept
                 </a>
                 <?php if ($c_c > 0) {
                        echo '<a href="comments.php?do=approveall&approveall=all">Approve All</a>';
                    } ?>
             </div>
         </div>
         <div class="notification users-notif">
             <i class="fas fa-users" aria-hidden="true"></i>
             <?php $u_c = getCount('UserID', 'users', 'WHERE RegStatus=0');
                if ($u_c > 0) {
                    echo '<span>' . $u_c . '</span>';
                } ?>
             <div class="notification-box">
                 <a href="members.php?mum=pending">You have
                     <strong><?php echo $u_c ?></strong>
                     Members needed your accept
                 </a>
                 <?php if ($u_c > 0) {
                        echo '<a href="members.php?do=approveall&approveall=all">Approve All</a>';
                    } ?>
             </div>
         </div>
         <div class="notification items-notif">
             <i class="fas fa-shopping-bag fa-fw"></i>
             <?php $i_c = getCount('ItemID', 'items', 'WHERE Approve=0');
                if ($i_c > 0) {
                    echo '<span>' . $i_c . '</span>';
                } ?>
             <div class="notification-box">
                 <a href="items.php?pending_items=pending">
                     You have <strong><?php echo $i_c ?></strong> Items needed your accept
                 </a>
                 <?php if ($i_c > 0) {
                        echo '<a href="items.php?do=approveall&approveall=all">Approve All</a>';
                    } ?>
             </div>
         </div>
     </div>

     <!-- part2 -->
     <div class="center">
         <?php
            $sessionUser = $_SESSION['ID'];
            $get_avatar = GetAllFrom("avatar", "users", "WHERE UserID= $sessionUser", "UserID", "", "fetch()");

            $avatar_src = !empty($get_avatar['avatar']) ? $get_avatar['avatar'] : 'default.png'; ?>

         <img src="upload/avatars/<?php echo $avatar_src; ?>" alt="">
         <p><?php echo $_SESSION['Username'];  ?></p>
         <div class="profile_options">
             <div class="close_p_options"><i class="fas fa-times-circle fa-fw"></i>Close</div>
             <div onclick="location.href = 'members.php?do=edit&id=<?php echo $_SESSION['ID'];  ?>';"><i class="fas fa-user-edit fa-fw"></i> <?php echo lang('EDIT'); ?></div>
             <div><i class="fas fa-user-cog fa-fw"></i> <?php echo lang('SETTING'); ?></div>


             <div onclick="location.href = 'logout.php';"><i class="fas fa-sign-out-alt fa-fw"></i><?php echo lang('LOGOUT'); ?></div>

         </div>
     </div>

     <!-- part3 -->







 </nav>
 <div class="slide-down">
     <?php echo $nav; ?>

 </div>