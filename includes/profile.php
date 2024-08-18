<?php
$uerID=get_current_user_id();
$user=get_userdata($uerID);
$userMeta=get_usermeta($uerID);
if($user!=false){

}

//User data Update.
if(isset($_POST['profileUpdate'])){
        $user_id=esc_sql($_POST['userId']);
        $user_fname=esc_sql($_POST['firstName']);
        $user_lname=esc_sql($_POST['lasttName']);
        $user_email=esc_sql($_POST['email']);

//Profile Picture upload
        if($_FILES['profilePic']['error']==0){
        $proPic=$_FILES['profilePic'];
       $ext=explode('/',$proPic['type'])[1];
       $fileName="$user_id.$ext";

       
        if(!metadata_exists('user', $user_id, 'user_profile_img_url')){
            $profileImg= wp_upload_bits($fileName, null, file_get_contents($proPic['tmp_name']));
            add_user_meta($user_id, 'user_profile_img_url', $profileImg['url']);
            add_user_meta($user_id, 'user_profile_img_path', esc_sql($profileImg['file']));
        }else{
            $profilePath= get_usermeta($user_id, 'user_profile_img_path');
            wp_delete_file($profilePath);
            $profileImg= wp_upload_bits($fileName, null, file_get_contents($proPic['tmp_name']));
            update_user_meta($user_id, 'user_profile_img_url', $profileImg['url']);
            update_user_meta($user_id, 'user_profile_img_path', esc_sql($profileImg['file']));
        }

        }
       
        
        $userData=array(
            'ID'=>$user_id,
            'first_name'=>$user_fname,
            'last_name'=>$user_lname,
            'user_email'=>$user_email,
        );
   
        $updateUser = wp_update_user($userData);
        if(is_wp_error($updateUser)){
            echo $updateUser->get_error_message();
        }

}

$proImage= get_usermeta($uerID,'user_profile_img_url');
?>
<div class="profile">
    <div class="container">
        <h2>Hi <?php echo get_usermeta($uerID,'first_name'); ?></h2>
        <p><a href="<?php echo wp_logout_url(site_url('/register-2')); ?>">Logout</a></p>
        <?php if($proImage!=""){ ?>
            <img src="<?php echo $proImage;?>"/>
        <?php } ?>
        
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="userId" value=<?php echo $uerID ?>>
            <label>Profile Picture</label><br>
            <input type="file" name="profilePic">
            <br>
            <br>
            <label>First Name</label><br>
            <input type="text" name="firstName" value="<?php echo get_usermeta($uerID,'first_name'); ?>"><br> <br>
            <lable>Last Name</lable><br>
            <input type="text" name="lasttName" value="<?php echo get_usermeta($uerID,'last_name'); ?>"><br> <br>
            <lable>Email</lable><br>
            <input type="email" name="email" value="<?php echo $user->user_email; ?>">
            <br> <br>
            <input type="Submit" value="Update" name="profileUpdate">
        </form>
    </div>
</div>