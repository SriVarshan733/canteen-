<?php

if(isset($_POST['add_to_cart'])){
   
   if($user_id == ''){
      header('location:login.php');
   } else {
      
      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_STRING);
      $category = $_POST['category'];
      $category = filter_var($category, FILTER_SANITIZE_STRING);
      
      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $check_cart_numbers->execute([$user_id]);
      
      // Check if the cart is empty
      if($check_cart_numbers->rowCount() == 0){
         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image, category) VALUES(?,?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image, $category]);
         $message[] = 'Added to cart!';
      } else {
         $cart_items = $check_cart_numbers->fetchAll(PDO::FETCH_ASSOC);
         $category_exists = false;
         $same_item_exists = false;
         
         // Check if the category of the existing cart item is the same as the new item
         foreach($cart_items as $cart_item){
            if($cart_item['pid'] == $pid && $cart_item['category'] == $category){
               $same_item_exists = true;
               $new_qty = $cart_item['quantity'] + $qty;
               $update_cart = $conn->prepare("UPDATE `cart` SET quantity=? WHERE id=?");
               $update_cart->execute([$new_qty, $cart_item['id']]);
               $message[] = 'Quantity updated in cart!';
               break;
            } else if($cart_item['category'] == $category){
               $category_exists = true;
            }
         }
         
         if(!$same_item_exists){
            if($category_exists){
               // Add new item to the cart
               $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image, category) VALUES(?,?,?,?,?,?,?)");
               $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image, $category]);
               $message[] = 'Added to cart!';
            }else {
               $message[] = 'Cannot add new products from different shop till the cart gets empty.';
            }
         }
      }
   }
}


?>
