<?php
    global $wpdb, $table_prefix;
    $wp_emp= $table_prefix.'emp';
    if(isset($_GET['empsearch'])){
        $listEmp="SELECT * FROM `$wp_emp` WHERE `name` LIKE '%".$_GET['empsearch']."%'
        OR `mail` LIKE '%".$_GET['empsearch']."%'
        OR `status` LIKE '%".$_GET['empsearch']."%'
        ;"; 
    } else {
        $listEmp="SELECT * FROM `$wp_emp`";
    }
    
    $getList=$wpdb->get_results($listEmp);
    //print_r($getList);


    
ob_start();
?>

<div class="wrap">
<h2 class="pageTitle">Employee Table</h2>
<div class="search-form">
    <?php if (is_admin()){ ?>
        <form action="<?php echo admin_url('admin.php')?>" id="empForm">
    <?php } else { 
        global $wp;
        ?>
    <form action="<?php echo home_url( $wp->request ); ?>" id="empForm">
    <?php } ?>
        <input type="hidden" name="page" value="mfp-first-acp-page">
        <input type="search" name="empsearch" id="EmpSearch" placeholder="Search Employee">
        <input type="submit" value="Search" name="search">
</form>
</div>
<table class="empList" border="0" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
        <tbody id="myTableBody">
        <?php foreach($getList as $list): ?>
        <tr>
            <td><?php echo $list->name; ?></td>
            <td><?php echo $list->mail;  ?></td>
            <td><?php echo $list->status; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </thead>
</table>
</div>
<?php 
echo ob_get_clean();
?>