<?php
/*
 * Add my new menu to the Admin Control Panel
 */

add_shortcode( 'footag', 'wpdocs_footag_func' );
function wpdocs_footag_func( $atts, $content = null) {
    $outPut = '';
    extract(shortcode_atts(array(
        'cat' => '',
        'num' => '5',
        'order' => 'DESC',
        'orderby'=>'post_date'
    ),$atts));
    $arg= array(
        'cat'=> $cat,
        'post_type'=>"post",
        'posts_per_page'=>$num,
        'order' => $order,
        'orderby' => $orderby
     );
    $query= new WP_Query($arg);
    while($query->have_posts()){
        $query->the_post();
        $outPut.= '<h2><a href='.get_the_permalink().'>'. get_the_title() .'</a></h2>';
    }
    return $outPut;
}

/*
add_shortcode('helloPost', 'passParams');
function passParams($atts, $content=null){
    $myName='';
    extract(shortcode_atts(array(
        'year'=>'5',
        'name'=>'jhon'
    ),$atts
));
    $myName= "My name is ".$name.". I am ".$year." years old";
    return $myName;
}
*/
