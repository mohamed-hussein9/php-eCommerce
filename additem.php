<?php
ob_start();
session_start();
$Title = 'ADD-NEW-ITEM';
include('init.php');

if (isset($_SESSION['User'])) {
    $session_id = $_SESSION['UserID'];
    $test_regstatus = GetAllFrom("RegStatus", "users", "WHERE UserID=$session_id", "RegStatus", "", "fetch()");
    if ($test_regstatus['RegStatus'] == 1) {







?>
        <div>
            <p class="profile"> ADD item</p>
            <div class="add_container">

                <div>

                    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="edit_profile add_status" enctype="multipart/form-data">
                        <h2>Add New Item</h2>
                        <div>
                            <label for="name">Name</label>
                            <input class="live_name" id="name" type="text" name="name" autocomplete="off" spellcheck="false" placeholder="Item  Name " required="required">
                        </div>
                        <div>
                            <label for="desc">Description</label>
                            <input class="live_description" id="desc" type="text" name="description" placeholder="description of this item" required="required">
                        </div>
                        <div>
                            <label for="price">Price</label>
                            <input class="live_price" id="price" type="number" name="price" placeholder="item price" required="required">
                        </div>
                        <div>
                            <label for="c_made">Contry made</label>
                            <input id="c_made" type="text" name="c_made" placeholder="item contry made" required="required">
                        </div>
                        <div>
                            <label for="status" class="status_label">Status</label>
                            <select name="status" class="status_select" id="status">
                                <option value="0">...</option>
                                <option value="1">new</option>
                                <option value="2">like new</option>
                                <option value="3">used</option>
                                <option value="4">old</option>
                            </select>
                        </div>
                        <div>
                            <label for="status" class="status_label">Category</label>
                            <select name="cat_id" class="status_select" id="status">
                                <option value="0">...</option>
                                <?php
                                $getcat = GetAllFrom('*', 'categories', ' ', ' Ordering', 'ASC');
                                foreach ($getcat as $row) {
                                    echo '<option value="' . $row['ID'] . '">' . $row['Name'] . '</option>';
                                }
                                ?>
                            </select>

                        </div>
                        <div>
                            <label for="tags">Tags </label>
                            <input id="tags" type="text" name="tags" placeholder="Type Tags Separated By a Comma">
                        </div>
                        <div>
                            <label for="item_image">Image </label>
                            <input id="item_image" type="file" name="item_image">
                        </div>
                        <input type="submit" value="Add Item">

                    </form>
                </div>



                <div class="container">
                    <div class="items">
                        <div>
                            <img id="imgInp" src="online-marketing-1246457_640.jpg" alt="">
                            <h3> Item name </h3>
                            <h5>Description</h5>
                            <span class="price">$0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $stmt_user = $con->prepare("SELECT UserID FROM users WHERE Username = ?");
            $stmt_user->execute(array($_SESSION['User']));
            $member_id = $stmt_user->fetch();
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $price = $_POST['price'];
            $c_made = $_POST['c_made'];
            $status = $_POST['status'];
            $cat_id = $_POST['cat_id'];
            $tags = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
            $tags = strtolower(str_replace(" ", "", $tags));
            $item_image = $_FILES['item_image'];
            $item_image_name = $item_image['name'];
            $item_image_tmp = $item_image['tmp_name'];
            $item_image_type = $item_image['type'];
            $item_image_size = $item_image['size'];
            $allowed = array("png", "jpg", "gif");
            $extention = explode(".", $item_image_name);
            $extention = strtolower(end($extention));

            //validate the form
            echo '<div class="errors_list">';
            $errors = array();
            if (strlen($name) < 4 || strlen($name) > 20) {
                $errors[] = 'Item name must be between <strong>(4-20)</strong> characters ';
            }
            if (empty($_POST['description'])) {
                $errors[] = 'you can\'t leave<strong> description</strong> empty ';
            }
            if (empty($_POST['price'])) {
                $errors[] = 'you can\'t leave<strong> Price</strong> empty ';
            }
            if (empty($_POST['c_made'])) {
                $errors[] = 'you can\'t leave the <strong>contry made</strong> empty ';
            }
            if (empty($_POST['status'])) {
                $errors[] = 'you can\'t leave the <strong>status</strong> empty ';
            }
            if (empty($_POST['cat_id'])) {
                $errors[] = 'you can\'t leave the <strong>category</strong> empty ';
            }
            if (!in_array($extention, $allowed) && !empty($item_image_name) && $item_image_size < 1000000) {
                $errors[] = 'upload only image with max-size 1';
            }
            foreach ($errors as $err_result) {
                echo '<div class="err_daner">' . $err_result . '</div>';
            }

            if (!empty($err_result)) {
                add_error_p();
            }
            echo '</div>';
            if (empty($err_result)) {

                echo '<script>$(".errors_list").hide();</script>';
                $item_image_name = rand(10, 10000000) . "_" . $_SESSION['UserID'] . "." . $extention;
                move_uploaded_file($item_image_tmp, "admin/upload/item_images/" . $item_image_name . "");
                $stmt = $con->prepare("INSERT INTO items ( Name,Description,Price, status,
            	    Contry_made,Image,Cat_ID,Add_Date,Member_ID,tags ) values(?,?,?,?,?,?,?,now(),?,?) 
                    ");

                $stmt->execute(array($name, $desc, '$' . $price, $status, $c_made, $item_image_name, $cat_id, $member_id['UserID'], $tags));

                $count = $stmt->rowCount();



                if ($count > 0) {
                    redirect_success('DATA INSERTED', 'back', 3);
                } else {
                    redirect_error('error 142', 'back', 5);
                }
            }
        }
    } else {
        ?>
        <div>
            <p class="profile"> ADD item</p>
            <div class="add_container">

                <div>
                    <h1>You can not add items until accepted your mumbership</h1>
                </div>



                <div class="container">
                    <div class="items">
                        <div>
                            <img src="online-marketing-1246457_640.jpg" alt="">
                            <h3> Item name </h3>
                            <h5>Description</h5>
                            <span class="price">$0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php


    }
} else {
    header("location: index.php");
    exit();
}
include($templates . "footer.php");
ob_end_flush();  ?>