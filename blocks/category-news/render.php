<?php
/**
 * Block: Category Split.
 *
 * ACF fields (group_block_category_news):
 *   - columns (repeater):
 *       - category    (taxonomy term)
 *       - title_np    (string, optional override)
 *       - title_en    (string, optional)
 *       - feature_post (post_object, optional pin)
 *       - list_posts   (relationship, max 3, optional)
 *
 * When list/feature are empty, posts are pulled live from the chosen
 * category. `variant` (block attribute) is forwarded to the wrapper class
 * so theme CSS can tweak per-section spacing without extra fields.
 */
if (! defined('ABSPATH')) exit;

if (! function_exists('na_category_news_defaults')):
function na_category_news_defaults($variant) {
    $map = array(
        'politics-economy' => array(array('politics', 'राजनीति', 'Politics'), array('economy', 'अर्थ', 'Economy')),
        'world'            => array(array('world',    'विश्व',   'World'),    array('sports',  'खेलकुद', 'Sports')),
        'culture-tech'     => array(array('culture',  'संस्कृति', 'Culture'),  array('tech',    'प्रविधि', 'Tech')),
    );
    $pairs = $map[$variant] ?? $map['politics-economy'];
    $out = array();
    foreach ($pairs as $row) {
        list($slug, $np, $en) = $row;
        $out[] = array(
            'category' => get_term_by('slug', $slug, 'category') ?: null,
            'title_np' => $np,
            'title_en' => $en,
        );
    }
    return $out;
}
endif;

/** @var array $block - injected by ACF block render context */
$variant = isset($block['data']['variant']) ? $block['data']['variant']
    : (isset($block['attrs']['variant']) ? $block['attrs']['variant']
    : (isset($attributes['variant']) ? $attributes['variant'] : 'politics-economy'));

$columns = na_field('columns', null, array());
if (! $columns) {
    $columns = na_category_news_defaults($variant);
}
?>
<section class="section category-news category-news--<?php echo esc_attr($variant); ?>">
    <div class="container-wide">
        <div class="split-2">
            <?php foreach ((array) $columns as $col):
                $term     = isset($col['category']) ? $col['category'] : null;
                $term_obj = is_object($term) ? $term : ($term ? get_term((int) $term, 'category') : null);

                $title_np = $col['title_np'] ?? ($term_obj ? $term_obj->name : '');
                $title_en = $col['title_en'] ?? ($term_obj ? $term_obj->description : '');

                $feature  = $col['feature_post'] ?? null;
                if ($feature && ! $feature instanceof WP_Post) $feature = get_post($feature);

                $list = $col['list_posts'] ?? array();
                if (! $list && $term_obj) {
                    $list = get_posts(array(
                        'numberposts' => 3,
                        'cat'         => $term_obj->term_id,
                        'exclude'     => $feature ? array($feature->ID) : array(),
                    ));
                }
                if (! $feature && $term_obj) {
                    $f = get_posts(array('numberposts' => 1, 'cat' => $term_obj->term_id));
                    $feature = $f ? $f[0] : null;
                }
            ?>
            <div>
                <div class="section-head">
                    <h2><?php echo esc_html($title_np); ?> <?php if ($title_en): ?><span class="en-sub"><?php echo esc_html($title_en); ?></span><?php endif; ?></h2>
                    <?php if ($term_obj): ?><a href="<?php echo esc_url(get_term_link($term_obj)); ?>" class="more">सबै →</a><?php endif; ?>
                </div>
                <?php if ($feature): $fcat = na_primary_category($feature->ID); ?>
                    <a href="<?php echo esc_url(get_permalink($feature)); ?>" class="card col-feature">
                        <?php echo na_thumb($feature, '16/9', 'medium_large', '(max-width:768px) 100vw, 40vw', 'lazy', array('cat_tag' => $fcat['np'])); ?>
                        <div class="body">
                            <h3 class="title"><?php echo esc_html(get_the_title($feature)); ?></h3>
                            <p class="excerpt"><?php echo esc_html(na_excerpt($feature->ID, 30)); ?></p>
                            <div class="meta"><span class="author"><?php echo esc_html(get_the_author_meta('display_name', $feature->post_author)); ?></span><span class="dot"></span><span><?php echo esc_html(na_time_ago($feature->ID)); ?></span></div>
                        </div>
                    </a>
                <?php endif; ?>
                <div style="margin-top: 18px;" class="col-list">
                    <?php foreach ((array) $list as $p): if (! $p instanceof WP_Post) $p = get_post($p); if (! $p) continue; $pcat = na_primary_category($p->ID); ?>
                        <a href="<?php echo esc_url(get_permalink($p)); ?>" class="card-text">
                            <div class="kicker"><?php echo esc_html($pcat['np']); ?></div>
                            <h4 class="title"><?php echo esc_html(get_the_title($p)); ?></h4>
                            <div class="meta"><?php echo esc_html(get_the_author_meta('display_name', $p->post_author)); ?> · <?php echo esc_html(na_time_ago($p->ID)); ?></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php
