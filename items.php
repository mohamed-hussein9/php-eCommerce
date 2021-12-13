<?php
ob_start();
session_start();
$Title = 'ITEMS';
include 'init.php';
if (isset($_GET['itemid']) && is_numeric($_GET['itemid']) && intval($_GET['itemid'])) {
    //check promission to open item witch not approved
    $test = $con->prepare("SELECT Member_ID FROM items WHERE ItemID=?");
    $test->execute(array($_GET['itemid']));
    $test_user = $test->fetch();
    $session_id = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : 0;
    if ($test_user['Member_ID'] == $session_id) {
        $sql_item = '';
    } else {
        $sql_item = 'AND Approve=1';
    }

    $stmt = $con->prepare("SELECT 
                                items.* ,
                                categories.Name AS category,
                                categories.ID,
                                users.Username,
                                users.UserID
                                FROM items
                                    INNER JOIN categories
                                    on categories.ID = items.Cat_ID
                                    INNER JOIN users
                                    on users.UserID = items.Member_ID
                                WHERE 
                                    ItemID=?
                                $sql_item
                                    

                            ");
    $stmt->execute(array($_GET['itemid']));
    $count = $stmt->rowCount();
    if ($count > 0) {

        $result = $stmt->fetch();
        $stmt2 = $con->prepare("SELECT   comments.*,
                                    users.Username,
                                    users.UserID,
                                    users.avatar
                                    FROM comments
                                    INNER JOIN users
                                    on comments.user_id = users.UserID
                                    WHERE  item_id= ?
                                    AND status=1
                                    ");
        $stmt2->execute(array($_GET['itemid']));
        $result2 = $stmt2->fetchAll();

?>
        <div class="">
            <div class="item">
                <?php $img_name =  $result['Image'] !== '' ? $result['Image'] : "default-store.jpg"; ?>
                <div class="image"><img src="admin/upload/item_images/<?php echo $img_name; ?>" alt=""></div>
                <div class="info">
                    <h2><?php echo $result['Name'] ?>
                        <?php
                        $ip = getIPAddress();
                        $stmt = $con->prepare('SELECT id FROM cart WHERE ip_address=? AND item_id=?');
                        $stmt->execute(array($ip, $result['ItemID']));
                        $count = $stmt->rowCount();
                        if ($count > 0) {
                            echo '<a  class="add_to_cart">YOU Added This Item To Cart</a>';
                        } else {
                            echo '<a  class="add_to_cart" href="cart.php?do=add&id=' . $result['ItemID'] . '">ADD To Cart</a>';
                        }
                        ?>

                    </h2>
                    <p> <?php echo $result['Description'] ?></p>
                    <p> Contry Made : <?php echo $result['Contry_made'] ?></p>
                    <span><small> Posted in : <?php echo $result['Add_Date'] ?></small></span>
                    <p>Category :<a href="index.php?page=<?php echo str_replace(' ', '-', $result['category']) ?>&cat_id=<?php echo  $result['ID']; ?>"> <?php echo $result['category'] ?> </a></p>
                    <p>Price : <?php echo $result['Price'] ?></p>
                    <p>Posted By : <a href="profile.php?user_id=<?php echo $result['UserID'] ?>"><?php echo $result['Username'] ?></a></p>
                    <p>Tags : <?php $tag_name = $result['tags'];
                                $tag_array = explode(",", str_replace(" ", "", $tag_name));
                                if (!empty($tag_name)) {
                                    foreach ($tag_array as $tag) {
                                        echo '<span><a href="tags.php?tagname=' . $tag . '"> ' . $tag . '</a></span>|';
                                    }
                                }
                                ?></p>
                    <?php
                    if (isset($_SESSION['User'])) {
                        if ($result['Username'] == $_SESSION['User']) { ?>
                            <p>
                                <span><a class="edit" href="edit_item.php?itemid=<?php echo $result['ItemID'] ?>">Edit</a></span>
                                <span><a class="delete" href="">Delete</a></span>
                            </p>

                    <?php }
                    } ?>
                </div>
                <div class="comment">
                    <div>
                        <h1>Comments : </h1>

                    </div>
                    <?php
                    if (!empty($result2)) {
                        foreach ($result2 as $row2) {
                            echo '<div>';
                            $u_image_name = empty($row2['avatar']) ? "default.png" : $row2['avatar'];
                            echo '<p class="user-image"><img src="admin/upload/avatars/' . $u_image_name . '" alt=""> </p> <a href="#"> ' . $row2['Username'] . '</a> :<span class="comment-content"> <p> ' . $row2['comment'] . '</p> .
                    <small class="date">' . $row2['comment_date'] . '</small> </span>';

                            echo '</div>';
                        }
                    } else {
                        echo '<div> No Comments </div>';
                    }

                    ?>
                    <div class="post_comment">
                        <form action="<?php echo  $_SERVER['PHP_SELF'] . '?itemid=' . $_GET['itemid']; ?>" method="post">
                            <textarea required name="comment" <?php if (!isset($_SESSION['User'])) {
                                                                    echo 'disabled';
                                                                }  ?> name="" id="" cols="40" rows="5"><?php if (!isset($_SESSION['User'])) {
                                                                                                            echo 'Login To Post Comment';
                                                                                                        } ?></textarea>

                            <input <?php if (!isset($_SESSION['User'])) {
                                        echo 'disabled';
                                    }  ?> type="submit" value="comment">
                            <input type="hidden" value="<?php echo $_GET['itemid']; ?>" name="item">
                            <input type="hidden" value="<?php echo $_SESSION['UserID']; ?>" name="user">
                        </form>
                    </div>

                </div>



            </div>
        </div>

        <?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['HTTP_REFERER']) {
            if (isset($_SESSION['User'])) {
                if (!empty($_POST['comment'])) {
                    $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                    $stmt_comment = $con->prepare("INSERT INTO comments(comment,comment_date,item_id,user_id)
                                                    values(?,now(),?,?)");
                    $stmt_comment->execute(array($comment, $_POST['item'], $_POST['user']));
                    if ($stmt_comment) {


                        header("location: items.php?itemid=" . $_GET['itemid'] . "");

                        exit();
                    }
                } else {
                    echo 'Dont leave the feild empty';
                }
            } else {
                echo 'you have to <a href="login.php">Login</a> To Post Comment';
            }
        }
        ?>


<?php
    } else {

        header("location: index.php");
        exit();
    }
}

include $templates . 'footer.php';
ob_end_flush(); ?>