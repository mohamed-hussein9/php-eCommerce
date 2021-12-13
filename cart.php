<?php
ob_start();
session_start();
include('init.php');
?>

<div class="cart_container">
    <div>
        <div class="action">
            action
        </div>
        <div class="product_name">
            products
        </div>
        <div class="product_amount">
            amount

        </div>
        <div class="product_price">
            price
        </div>
    </div>


    <?php
    //delete from cart
    if (isset($_GET['do']) && isset($_GET['id'])) {
        if ($_GET['do'] === 'delete' && intval($_GET['id'])) {
            $id = $_GET['id'];

            $stmt = $con->prepare('DELETE FROM cart WHERE id=?');
            $stmt->execute(array($id));
            if ($stmt) {
                header('location: cart.php');
            }
        }
        //add to cart
        elseif ($_GET['do'] === 'add' && intval($_GET['id'])) {
            $item_id = $_GET['id'];

            $ip = getIPAddress();
            $stmt = $con->prepare('SELECT id FROM cart WHERE ip_address=? AND item_id=?');
            $stmt->execute(array($ip, $item_id));
            $count = $stmt->rowCount();
            if ($count > 0) {
                echo '<script> alert("You Added This Item Befor");</script>';
            } else {
                $stmt = $con->prepare('INSERT INTO  cart (ip_address ,date,amount,item_id)
                                                values(?,now(),1,?)');
                $stmt->execute(array($ip, $item_id));
            }
        }
    }

    if (isset($_POST['amount']) && intval($_POST['amount'])) {
        $stmt = $con->prepare('UPDATE cart SET amount= ? WHERE item_id=? AND ip_address=?');
        $stmt->execute(array($_POST['amount'], $_POST['item_id'], $_POST['ip']));
        if ($stmt) {
            $stmt = $con->prepare('SELECT amount FROM cart WHERE item_id=? AND ip_address=? ');
            $stmt->execute(array($_POST['item_id'], $_POST['ip']));
            $res = $stmt->fetch();
            echo $res['amount'];
        }
        echo 'the if statment is true';
    } else {

        $stmt = $con->prepare('SELECT cart.*,
                                    items.Name,
                                    items.Price,
                                    items.Image
                                    FROM cart 
                                    
                                    INNER JOIN items
                                    on items.ItemID=cart.item_id
                                    WHERE cart.ip_address=?');
        $stmt->execute(array(getIPAddress()));
        $cart_content = $stmt->fetchAll();
        $total_price = 0;
        if (!empty($cart_content)) {
            $total_price = 0;
            foreach ($cart_content as $cart) {
                $total_price = $total_price + $cart['Price'];


    ?>

                <div>
                    <div class="action" style="    padding: 14px;">
                        <a href="cart.php?do=delete&id=<?php echo $cart['id'] ?>">delete</a>
                    </div>
                    <div class="product_name">
                        <img src="admin/upload/item_images/<?php echo $cart['Image']  ?>" alt="">
                        <p><?php echo $cart['Name']  ?></p>
                    </div>
                    <div class="product_amount">
                        <input class="amount" type="number" value="<?php echo $cart['amount']  ?>" disabled>
                        <input type="hidden" name="item_id" class="c_item_id" value="<?php echo $cart['item_id'] ?>">
                        <input type="hidden" name="ip" class="c_ip" value="<?php echo getIPAddress(); ?>">

                    </div>
                    <div class="product_price">
                        <?php echo $cart['Price'] . ' $'; ?>
                    </div>
                </div>


    <?php
            }
        }
    }
    ?>


    <div>
        <p>total Price :<?php echo $total_price . ' $';; ?></p>
        <button class="checkout_btn">chechout</button>
    </div>
</div>

<?php include($templates . "footer.php");
ob_end_flush(); ?>