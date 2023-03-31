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

   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);

   $update_address = $conn->prepare("UPDATE `users` set address = ? WHERE id = ?");
   $update_address->execute([$address, $user_id]);

   $message[] = 'details saved!';

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php' ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Roll number</h3>
      <h1 style= "color:red;">College roll number</h1>
      <input type="text" class="box" placeholder="college roll number" required maxlength="50" name="address" ?>">
      <input type="submit" value="save Details" name="submit" class="btn">
      <div class="more-btn">
      <a href="checkout.php" class="btn">Back</a>
   </div>
   </form>

</section>

<?php include 'components/footer.php' ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
