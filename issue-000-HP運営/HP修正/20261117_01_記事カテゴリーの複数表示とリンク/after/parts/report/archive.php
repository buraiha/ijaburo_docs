<?php get_header(); ?>
<div class="gw-wrapper">
    <main>
        <div class="gw-sub-hero">
            <div class="gw-sub-hero__inner">
                <h1 class="gw-general-h1">
                    <span class="gw-general-h1__ja"><img src="<?php gw_asset_baseurl('img'); ?>/common/ttl-report.png" alt="進捗状況報告"></span>
                    <!--<span class="gw-general-h1__en gw-txt-grad">Report</span>-->
                </h1>
            </div>
            <!--<span class="gw-sub-hero__bg-text is-report">Report</span>-->
            <?php get_template_part('parts/bread'); ?>
        </div>

        <div class="gw-entry">
            <div class="gw-report__wrapper">
                <div class="gw-report__main">
                    <ul class="gw-report__archive">
                        <?php if (have_posts()): while(have_posts()): the_post(); // WPメインループ開始 ?>
                        <li>
                            <dl class="gw-report__archive-item">
                                <dt class="gw-report__archive-item-img"><a href="<?php the_permalink(); ?>"><span><?php the_post_thumbnail(); ?></span></a></dt>
                                <dd class="gw-report__archive-item-date"><time><?php the_time('Y.m.d'); ?></time></dt>
                                <dd class="gw-report__archive-item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></dd>
                                <?php $report_category_links = get_the_term_list(get_the_ID(), GW_TAXONOMY_NAME_REPORT, '<span>', '</span><span>', '</span>'); ?>
                                <?php if ($report_category_links && !is_wp_error($report_category_links)): ?>
                                <dd class="gw-report__archive-item-category"><?php echo wp_kses_post($report_category_links); ?></dd>
                                <?php endif; ?>
                            </dl>
                        </li>
                        <?php endwhile; // ループ終了?>
                        <?php else: // 該当の投稿が無かった場合?>
                        <li>
                            <span class="gw-report__archive-item-title">報告はありません。</span>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <?php get_template_part('parts/pagination'); ?>
                </div>
                <!-- /.gw-report__main -->
            </div>
            <!-- /.gw-report__wrapper -->
        </div>
        <!-- /.gw-report -->
        <?php get_template_part('parts/linknav'); ?>
    </main>
    <!-- /.gw-report -->
<?php get_footer(); ?>
