<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);


   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

      <!-----payment script----->
      <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

function processInput() {
    var input = document.getElementById("input").value;
    if (input === "SLC-01") {
        paynow01();
    } else if (input === "SLC-02") {
        paynow02();
    } else {
        paynow03();
    }
}
   
// function paynow01() is used for the payment of shop "SLC-01", key:rzp_test_35KB49UYo7xAQa this mentions the razorpay account 

    function paynow01(){
        var name=jQuery('#name').val();
        var amt=jQuery('#amt').val();
        
         jQuery.ajax({
               type:'post',
               url:'payment_process.php',
               data:"amt="+amt+"&name="+name,
               success:function(result){
                   var options = {
                        "key": "rzp_test_35KB49UYo7xAQa", 
                        "amount": amt*100, 
                        "currency": "INR",
                        "name": "BIT-SLC-01",
                        "description": "Test Transaction",
                        "image": "https://image.freepik.com/free-vector/logo-sample-text_355-558.jpg",
                        "handler": function (response){
                           jQuery.ajax({
                               type:'post',
                               url:'payment_process.php',
                               data:"payment_id="+response.razorpay_payment_id,
                               success:function(result){
                                   window.location.href="finalout.php";
                               }
                           });
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
               }
           }); 
    }

// function paynow02() is used for the payment of shop "SLC-02", key:rzp_test_QnATJeNadwfPtT this mentions the razorpay account
    function paynow02(){
        var name=jQuery('#name').val();
        var amt=jQuery('#amt').val();
        
         jQuery.ajax({
               type:'post',
               url:'payment_process.php',
               data:"amt="+amt+"&name="+name,
               success:function(result){
                   var options = {
                        "key": "rzp_test_QnATJeNadwfPtT", 
                        "amount": amt*100, 
                        "currency": "INR",
                        "name": "BIT-SLC-02",
                        "description": "Test Transaction",
                        "image": "https://image.freepik.com/free-vector/logo-sample-text_355-558.jpg",
                        "handler": function (response){
                           jQuery.ajax({
                               type:'post',
                               url:'payment_process.php',
                               data:"payment_id="+response.razorpay_payment_id,
                               success:function(result){
                                   window.location.href="finalout.php";
                               }
                           });
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
               }
           }); 
    }

// function paynow03() is used for the payment of shop "SLC-03", key:rzp_test_JC5zcAEJ9EYE43 this mentions the razorpay account
    function paynow03(){
        var name=jQuery('#name').val();
        var amt=jQuery('#amt').val();
        
         jQuery.ajax({
               type:'post',
               url:'payment_process.php',
               data:"amt="+amt+"&name="+name,
               success:function(result){
                   var options = {
                        "key": "rzp_test_JC5zcAEJ9EYE43", 
                        "amount": amt*100, 
                        "currency": "INR",
                        "name": "BIT-SLC-03",
                        "description": "Test Transaction",
                        "image": "https://image.freepik.com/free-vector/logo-sample-text_355-558.jpg",
                        "handler": function (response){
                           jQuery.ajax({
                               type:'post',
                               url:'payment_process.php',
                               data:"payment_id="+response.razorpay_payment_id,
                               success:function(result){
                                   window.location.href="finalout.php";
                               }
                           });
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
               }
           }); 
    }
     
</script>


   <!-----payment script----->

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>checkout</h3>
   <p><a href="index.php">home</a> <span> / checkout</span></p>
</div>

<section class="checkout">

   <h1 class="title">order summary</h1>

<form action="" method="post">

   <div class="cart-items">
      <h3>cart items</h3>
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
      <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price">₹<?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?></span></p>
      <?php
            }
         }else{
            echo '<p class="empty">your cart is empty!</p>';
         }
         
      ?>
      <p class="grand-total"><span class="name">grand total :</span><span class="price">₹<?= $grand_total; ?></span></p>
      <a href="cart.php" class="btn">veiw cart</a>
   </div>

   <input type="hidden" name="total_products" value="<?= $total_products; ?>">

<!--this line helps to pull the input "amount" for the payment ex:-name="amt" id="amt" these are the main variable to get input-->
<input type="hidden" name="total_price" name="amt" id="amt" value="<?= $grand_total; ?>" value="">
<!--the above line helps to pull the input "amount" for the payment-->

<input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
<input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
<input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
<!--this line helps to pull the input "shop id" for the payment ex:- name="input" id="input" these are the main variable to get input-->
<input type="hidden" name="input" id="input" value="<?php 
   $select_category = $conn->prepare("SELECT category FROM `cart` WHERE user_id = ? LIMIT 1");
   $select_category->execute([$user_id]);
   $fetch_category = $select_category->fetch(PDO::FETCH_ASSOC);
   echo $fetch_category['category'];
?>">
<!--the above line helps to pull the input "shop id" for the payment-->
<input type="hidden" value="<?= $fetch_profile['address'] ?>">
   <div class="user-info">
      <h3>your info</h3>
      <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?></span></p>
      <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number'] ?></span></p>
      <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
      <a href="update_profile.php" class="btn">update info</a>
      <h3>Roll number</h3>
      <p><i class="fas fa-user"></i><span><?php if($fetch_profile['address'] == ''){echo 'please enter your address';}else{echo $fetch_profile['address'];} ?></span></p>
      <a href="update_address.php" class="btn">update Id</a>
      <h3>Payment</h3>
      <div name="method" class="more-btn">
      <a value="google pay" id="btn" value="Pay Now" onclick="processInput()" class="btn">Pay Now</a>
      <br>
      <br>
      <br>
      <h1 style= "color:red;">verify the entered detials once before going to the payment</h1>
</form>
   
</section>









<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->






<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>