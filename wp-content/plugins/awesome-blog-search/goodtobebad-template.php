<?php $cleanTitle = str_replace('+', ' ', get_query_var('search')); ?>
<html>
<head>
<title><?php echo $cleanTitle ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="UTF-8">


<!-- Font -->

<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet">
<!-- Stylesheets -->

<link href="<?php echo home_url(); ?>/wp-content/plugins/awesome-blog-search/common-css/bootstrap.css" rel="stylesheet">

<link href="<?php echo home_url(); ?>/wp-content/plugins/awesome-blog-search/common-css/ionicons.css" rel="stylesheet">


<link href="<?php echo home_url(); ?>/wp-content/plugins/awesome-blog-search/layout-1/css/styles.css" rel="stylesheet">

<link href="<?php echo home_url(); ?>/wp-content/plugins/awesome-blog-search/layout-1/css/responsive.css" rel="stylesheet">
<style>
body, .blog-area.section {
  background-color: white;
}
.h-100, .blog-area .single-post{
  height: auto !important;
}
.slider {
    height: none;
    width: 100%;
    margin: 0 auto;
    background-image: none;
    background-size: full;
}
@media(min-width: 1000px){
  .slider img {
    height: 300px;
  }
}
</style>

</head>

<body>


<div class="slider">
  <img src="http://tse1.mm.bing.net/th?q=<?php echo get_query_var('search');  ?>" title="<?php echo $cleanTitle ?>" alt="<?php echo $cleanTitle ?>" align="right" style="border:5px solid white; width: 30vw; border: none; max-height: 300px;">
  <div style="width: 70vw; text-align: center; padding: 20px 10% 0;">
    <h1><?php echo $cleanTitle ?></h1>
    <p style="margin-top: 20px;">If your product has multiple favorable aspects, create sales messages for a variety of clients they could relate to. Especially if its new, you might have to generate interest by phoning your clients. Like its competitors, you can discover products of all sorts on the site and there are increasing numbers of imported products offered for purchase.</p>
    <form id="form1" style="margin-top: 30px;">
      <input type="text" name="search" id="searchF" style="border-color: #d3d3d3;" />
      <input type="submit" value="search" />
    </form>
  </div>
</div><!-- slider -->

<section class="blog-area section">
  <div class="container">

    <?php
    // the query
    $wpb_all_query = new WP_Query(array('post_type'=>'post', 'post_status'=>'publish', 'posts_per_page'=>-1)); ?>

    <?php if ( $wpb_all_query->have_posts() ) : ?>
      <?php $x = 0; ?>
      <div class="row">
        <!-- the loop -->
        <?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); ?>
          <?php if(strpos(strtolower(get_the_title()), strtolower($cleanTitle)) !== false) {?>

            <?php if($x % 3 == 0) { ?>
              </div><div class="row">
            <?php } ?>

            <div class="col-lg-4 col-md-6">
              <div class="card h-100">
                <div class="single-post post-style-1">
                  <?php if(get_the_post_thumbnail_url()) { ?>
                    <div class="blog-image"><img src="<?php the_post_thumbnail_url() ?>" alt="Blog Image"></div>
                  <?php } else { ?>
                    <div class="blog-image"><img src="<?php echo home_url(); ?>/wp-content/plugins/awesome-blog-search/images/category-3-400x250.jpg" alt="Blog Image"></div>
                  <?php } ?>
                  <div class="blog-info">

                    <h4 class="title"><a href="<?php the_permalink(); ?>"><b><?php the_title(); ?></b></a></h4>

                    <ul class="post-footer"></ul>

                  </div><!-- blog-info -->
                </div><!-- single-post -->
              </div><!-- card -->
            </div><!-- col-lg-4 col-md-6 -->
            <?php $x += 1; ?>
          <?php } ?>
        <?php endwhile; ?>
        <!-- end of the loop -->
      </div>
        <?php wp_reset_postdata(); ?>

    <?php else : ?>
        <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
    <?php endif; ?>
  </div><!-- container -->
</section><!-- section -->


<script>
document.getElementById("form1").addEventListener("submit", function(event){
  event.preventDefault();
  window.location.href = "/blog/search/"+document.getElementById("searchF").value;
});
</script>
</body>
</html>
