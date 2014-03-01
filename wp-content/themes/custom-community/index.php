<?php
global $cap;
global $wp_query;
get_header()
?>

<div id="content" class="span8">
    <div class="padder">
        <div class="clear"></div>

        <?php do_action('bp_before_blog_home'); ?>

        <?php
        if ($cap->posts_lists_style_home == 'magazine') {
            $args = array();
            $args['amount']       = get_option('posts_per_page', 9);
            $args['img_position'] = cc_get_magazine_style($cap->magazine_style_home);

            echo cc_list_posts($args); ?>

            <div class="navigation">
                <div class="alignleft"><?php next_posts_link(__('&larr; Previous Entries', 'cc')) ?></div>
                <div class="alignright"><?php previous_posts_link(__('Next Entries &rarr;', 'cc')) ?></div>
            </div>
        <?php
        } else { // blog
        ?>
            <div class="clear"></div>
            <div class="page" id="blog-latest">

                <?php if (have_posts()) : ?>

                    <?php while (have_posts()) : the_post(); ?>

                        <?php do_action('bp_before_blog_post') ?>

                        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                            <div class="author-box visible-desktop">
                                <?php echo get_avatar(get_the_author_meta('user_email'), '50'); ?>

                                <?php cc_author_link(); ?>
                            </div>

                            <div class="post-content span11">

                                <span class="marker visible-desktop"></span>

                                <h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to', 'cc') ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

                                <p class="date"><?php the_time('F j, Y') ?> <em><?php _e('in', 'cc') ?> <?php the_category(', ') ?> <?php cc_author_link(); ?></em></p>

                                <div class="entry">
                                    <?php do_action('blog_post_entry') ?>
                                </div>

                                <?php $tags = get_the_tags();
                                if ($tags) { ?>
                                    <p class="postmetadata"><span class="tags"><?php the_tags(__('Tags: ', 'cc'), ', ', '<br />'); ?></span> <span class="comments"><?php comments_popup_link(__('No Comments &#187;', 'cc'), __('1 Comment &#187;', 'cc'), __('% Comments &#187;', 'cc')); ?></span></p>
                                <?php } else { ?>
                                    <p class="postmetadata"><span class="comments"><?php comments_popup_link(__('No Comments &#187;', 'cc'), __('1 Comment &#187;', 'cc'), __('% Comments &#187;', 'cc')); ?></span></p>
                                <?php } ?>
                            </div>

                        </div>

                            <?php do_action('bp_after_blog_post') ?>

                        <?php endwhile; ?>

                    <?php if(function_exists('wp_pagenavi')): ?>
                        <?php wp_pagenavi(); ?>
                    <?php else: ?>
                        <div class="pagination">
                            <?php
                            $big = 999999999; // need an unlikely integer

                            echo paginate_links(array(
                                'base'    => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                                'format'  => '?paged=%#%',
                                'current' => max(1, get_query_var('paged')),
                                'total'   => $wp_query->max_num_pages
                            ));
                            ?>
                        </div>
                    <?php endif;?>

                <?php else : ?>

                    <h2 class="center"><?php _e('Not Found', 'cc') ?></h2>
                    <p class="center"><?php _e('Sorry, but you are looking for something that isn\'t here.', 'cc') ?></p>

                    <?php locate_template(array('searchform.php'), true) ?>

                <?php endif; ?>
                    </div>
                <?php } ?>

        <?php do_action('bp_after_blog_home') ?>

    </div><!-- .padder -->
</div><!-- #content -->

<?php get_footer() ?>