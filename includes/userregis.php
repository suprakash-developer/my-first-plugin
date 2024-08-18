<?php
if(isset($_POST['register'])){
    global $wpdb, $table_prefix;
    $firstName=$wpdb->escape($_POST['firstName']);
    $lastName=$wpdb->escape($_POST['lastName']);
    $email=$wpdb->escape($_POST['email']);
    $userName=$wpdb->escape($_POST['userName']);
    $password = $wpdb->escape($_POST['password']);
    $confirmPass = $wpdb->escape($_POST['confirmPassword']);

    if($password==$confirmPass){
        $userdata=array(
            'first_name'=>$firstName,
            'last_name'=> $lastName,
            'user_email'=> $email,
            'user_login'=>$userName,
            'user_pass'=>$password
        );

        $result=wp_insert_user($userdata);

        if(!is_wp_error($result)){
            echo "User Added $result";
            // Switch role
            $u = new WP_User( $result );
            $u->set_role( 'author' );
            add_user_meta($result, 'type', 'Faculty');
        } else{
            echo $result->get_error_message();
        }
          
        } else {
            echo 'Password should match';
        }
}

?>
<div class="warp">
    <div class="loginform">
        <form action="<?php get_permalink() ?>" method="post">
        <input type="text" name="user_name" placeholder="User Name"><br>
        <input type="password" name="user_password" placeholder="Password"><br>
        <input type="submit" value="Login" name="user_login">
</form>
    </div>
    <div class="registrationform">
    <form action="<?php echo get_the_permalink();?>" method="post">
    <input type="text" name="firstName" placeholder="First Name"><br>
    <input type="text" name="lastName" placeholder="Last Name"><br>
    <input type="email" name="email" placeholder="Email"><br>
    <input type="text" name="userName" placeholder="User Name"><br>
    <input type="password" name="password" placeholder="Password"><br>
    <input type="password" name="confirmPassword" placeholder="Confirm Password"><br>
    <input type="submit" value="Register" name="register">
</form>
    </div>
</div>

