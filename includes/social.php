<?php
if(isset($_POST['upsocial'])){
    $sociLink=array(
        'facebook'=>esc_sql($_POST['facebook']),
        'twitter'=>esc_sql($_POST['twitter']),
    )
    
    
    if(get_option('social-Link',-1)==-1){
        add_option('social-Link', $sociLink);
    } else{
       update_option('social-Link', $sociLink);
    }
    
}

?>


<div class="warp">
<form action="" method="post">
    <input type="text" name="facebook" value="">
    <input type="text" name="twitter" value="">
    <input type="submit" name="upsocial" value="Update">
</form>

</div>