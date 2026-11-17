<div class="gw-wrapper">
    <div class="gw-sub-hero is-post">
       <?php if (have_posts()): while (have_posts()): the_post(); ?>
       <div class="gw-report__head">
                    <div class="gw-report__title">
                        <img src="<?php gw_asset_baseurl('img'); ?>/common/ttl-report_notice.png" alt="進捗状況報告">
                    </div>
                    <div class="gw-report__head-meta">
                        <div class="gw-report__head-meta-date"><time><?php the_time('Y.m.d'); ?></time></div>
                    </div>
                    <h1 class="gw-report__head-title"><?php the_title(); ?></h1>
                    <div class="gw-report__head-meta-category">
                        <span><?php gw_category_name(get_the_ID(), GW_TAXONOMY_NAME_REPORT); ?></span>
                    </div>
                    
                    <!--<ul class="gw-news__head-meta-sns">
                        <li>
                            <a href="<?php gw_share_url('facebook') ?>" target="_blank" class="gw-news__head-meta-sns-facebook"><img src="<?php gw_asset_baseurl('img'); ?>/icon_facebook.svg" alt=""></a>
                        </li>
                        <li>
                            <a href="<?php gw_share_url('twitter'); ?>" target="_blank" class="gw-news__head-meta-sns-twitter"><img src="<?php gw_asset_baseurl('img'); ?>/icon_twitter.svg" alt=""></a>
                        </li>
                    </ul>-->
                    <?php get_template_part('parts/bread'); ?>
                </div>
    </div>
    <div class="gw-entry">
        <div class="gw-report__wrapper gw-report__wrapper--single">
            <article class="gw-report__main">
                
                

                <div class="gw-report__contents">
                    <?php the_post_thumbnail(); ?>
                    <?php the_content(); ?>
                </div>
                <!-- /.gw-entry -->

                <!--<div class="gw-news__article-navi">
                    <?php if (gw_get_prev_post_link()){ ?>
                    <a href="<?php echo gw_get_prev_post_link(); ?>" class="gw-news__article-navi-prev" rel="prev">
                        <span>前の記事</span>
                    </a>
                    <?php } ?>

                    <a href="<?php bloginfo('url'); ?>/<?php echo GW_CUSTOM_POST_TYPE_NAME_NEWS; ?>/" class="gw-news__article-navi-top">ニュース一覧</a>

                    <?php if (gw_get_next_post_link()){ ?>
                    <a href="<?php echo gw_get_next_post_link(); ?>" class="gw-news__article-navi-next" rel="next">
                        <span>次の記事</span>
                    </a>
                    <?php } ?>
                </div>-->

                <div class="gw-btn">
                    <a href="/report/" class="gw-btn__link">一覧に戻る</a>
                </div>

                <?php endwhile; ?>
                <?php else: ?>
                <div class="gw-report__contents gw-entry">
                    <p>報告が見つかりません。</p>
                </div>
                <?php endif; ?>
            </article>
            <!-- /.gw-report__main -->
        </div>
        <!-- /.gw-report__wrapper -->
    </div>
    <!-- /.gw-report -->
    <?php get_template_part('parts/linknav'); ?>
