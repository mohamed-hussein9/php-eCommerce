<?php
ob_start();
session_start();
$Title = 'EDIT_ITEM';
include('init.php');
if (isset($_SESSION['success_message'])) {
    echo '<div class="success_message">';
    echo $_SESSION['success_message'];
    echo '</div>';
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo '<div class="error_message">';
    echo $_SESSION['error_message'];
    echo '</div>';
    unset($_SESSION['error_message']);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

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
    $item_id = $_POST['item_id'];
    $old_img = $_POST['old_item_img'];

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
    if (!in_array($extention, $allowed) && !empty($item_image_name)) {
        echo 'upload only image';
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
        if ($item_image_name != '') {
            $item_image_tmp = $item_image['tmp_name'];
            $item_image_type = $item_image['type'];
            $item_image_size = $item_image['size'];
            $allowed = array("png", "jpg", "gif");
            $extention = explode(".", $item_image_name);
            $extention = strtolower(end($extention));
            $item_id = $_POST['item_id'];
            if (in_array($extention, $allowed)) {
                if (!empty($item_image_name)) {
                    $item_image_name = rand(10, 10000000) . "_" . $_SESSION['UserID'] . "." . $extention;
                    move_uploaded_file($item_image_tmp, "admin/upload/item_images/" . $item_image_name . "");
                    if (file_exists('admin/upload/item_images/' . $old_img)) {
                        unlink('admin/upload/item_images/' . $old_img);
                    }
                } else {
                    $item_image_name = $old_img;
                }
            } else {
                $item_image_name = $old_img;
            }
        } else {
            $item_image_name = $old_img;
        }

        $stmt = $con->prepare("UPDATE items SET  
        Name=?,
        Description=?,
        Price=?,
        status=?,
        Contry_made=?,
        Image=?, 
        Cat_ID=?,
        Member_ID=?,
        tags=? 
        WHERE ItemID=?
                    ");

        $stmt->execute(array($name, $desc, $price, $status, $c_made, $item_image_name, $cat_id, $member_id['UserID'], $tags, $item_id));





        if ($stmt) {
            $_SESSION['success_message'] = 'DATA UPdated';
            header('location:edit_item.php?itemid=' . $item_id);
            //redirect_success('DATA UPdated', 'back', 3);
        } else {
            $_SESSION['error_message'] = 'ERROR please check data and try again';
            // header('location:edit_item.php?itemid=' . $item_id);
        }
    }
} else {

    if (isset($_SESSION['User']) && isset($_GET['itemid'])) {
        $item_id = $_GET['itemid'];
        $item_data = GetAllFrom("*", "items", "WHERE ItemID=$item_id", 'ItemID', 'DESC', 'fetch()');

        if ($_SESSION['UserID'] === $item_data['Member_ID']) {

?>
            <!-- edit form -->
            <!-- i used some classes from add item page -->
            <div>
                <p class="profile"> Edit item</p>
                <div class="add_container">

                    <div>

                        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="edit_profile add_status" enctype="multipart/form-data">
                            <h2>Edit Item</h2>

                            <div>
                                <input type="hidden" name="item_id" value="<?php echo $item_id ?>">
                                <label for="name">Name</label>
                                <input value="<?php echo $item_data['Name']   ?>" class="live_name" id="name" type="text" name="name" autocomplete="off" spellcheck="false" placeholder="Item  Name " required="required">
                            </div>
                            <div>
                                <label for="desc">Description</label>
                                <input value="<?php echo $item_data['Description']   ?>" class="live_description" id="desc" type="text" name="description" placeholder="description of this item" required="required">
                            </div>
                            <div>
                                <label for="price">Price</label>
                                <input value="<?php echo $item_data['Price']   ?>" class="live_price" id="price" type="text" name="price" placeholder="item price" required="required">
                            </div>
                            <div>
                                <label for="c_made">Contry made</label>
                                <input value="<?php echo $item_data['Contry_made']   ?>" id="c_made" type="text" name="c_made" placeholder="item contry made" required="required">
                            </div>
                            <div>
                                <label for="status" class="status_label">Status</label>
                                <select name="status" class="status_select" id="status">
                                    <option <?php if ($item_data['Status'] == 0) {
                                                echo 'selected';
                                            }   ?> value="0">...</option>
                                    <option <?php if ($item_data['Status'] == 1) {
                                                echo 'selected';
                                            }   ?> value="1">new</option>
                                    <option <?php if ($item_data['Status'] == 2) {
                                                echo 'selected';
                                            }   ?> value="2">like new</option>
                                    <option <?php if ($item_data['Status'] == 3) {
                                                echo 'selected';
                                            }   ?> value="3">used</option>
                                    <option <?php if ($item_data['Status'] == 4) {
                                                echo 'selected';
                                            }   ?> value="4">old</option>
                                </select>
                            </div>
                            <div>
                                <label for="cat" class="status_label">Category</label>
                                <select name="cat_id" class="status_select" id="cat">
                                    <option value="0">...</option>
                                    <?php
                                    $getcat = GetAllFrom('*', 'categories', ' ', 'ID', 'ASC');
                                    foreach ($getcat as $row) {
                                        if ($row['ID'] === $item_data['Cat_ID']) {
                                            $s = 'selected';
                                        } else {
                                            $s = ' ';
                                        }

                                        echo '<option value="' . $row['ID'] . '" ' . $s . '>' . $row['Name'] . '</option>';
                                    }
                                    ?>
                                </select>

                            </div>
                            <div>
                                <label for="tags">Tags </label>
                                <input value="<?php echo $item_data['tags']  ?>" id="tags" type="text" name="tags" placeholder="Type Tags Separated By a Comma">
                            </div>
                            <div>
                                <input type="hidden" name="old_item_img" value="<?php echo $item_data['Image'] ?>">
                                <label for="item_image">Image </label>
                                <input id="item_image" type="file" name="item_image">
                            </div>
                            <input type="submit" value="Save">

                        </form>
                    </div>



                    <div class="container">
                        <div class="items">
                            <div>
                                <img id="imgInp" src="admin/upload/item_images/<?php echo $item_data['Image']  ?>" alt="">
                                <h3> <?php echo $item_data['Name']  ?> </h3>
                                <h5><?php echo $item_data['Description']  ?></h5>
                                <span class="price"><?php echo $item_data['Price']  ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<?php
        }
    } else {
        echo 'the value of if is false';
        // header("location: index.php");
        // exit();
    }
}
include($templates . "footer.php");
ob_end_flush();
