<?php $cleanTitle = str_replace('+', ' ', get_query_var('search')); ?>
<html>
<head>
<title><?php echo $cleanTitle ?></title>
</head>

<body>
<div>
<h1><?php echo $cleanTitle ?></h1>
<form id="form1">
  <input type="text" name="search" id="searchF" />
  <input type="submit" value="search" />
</form>

<img src="http://tse1.mm.bing.net/th?q=<?php echo get_query_var('search');  ?>" title="<?php echo $cleanTitle ?>" alt="<?php echo $cleanTitle ?>" style="border:5px solid white;" width="200">
</div>
<?php
// the query
$wpb_all_query = new WP_Query(array('post_type'=>'post', 'post_status'=>'publish', 'posts_per_page'=>-1)); ?>

<?php if ( $wpb_all_query->have_posts() ) : ?>

<ul>

    <!-- the loop -->
    <?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); ?>
      <?php if(strpos(strtolower(get_the_title()), strtolower($cleanTitle)) !== false) {?>
        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
      <?php } ?>
    <?php endwhile; ?>
    <!-- end of the loop -->

</ul>

    <?php wp_reset_postdata(); ?>

<?php else : ?>
    <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>

<script>
document.getElementById("form1").addEventListener("submit", function(event){
  event.preventDefault();
  window.location.href = "/search/"+document.getElementById("searchF").value;
});
</script>
</body>
</html>
