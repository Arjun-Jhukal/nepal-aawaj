<?php
/**
 * Block: Taxonomy Tabs.
 *
 * ACF fields (group_block_taxonomy_tabs):
 *   - title
 *   - tabs (repeater): { label, category (term), count }
 *
 * Front-end behaviour: a single tab is open at a time; shared.min.js
 * toggles `data-na-tab="<slug>"` panels when their button is clicked.
 */
if (! defined('ABSPATH')) exit;

$title = na_field('title', null, 'श्रेणी अनुसार');
$tabs  = na_field('tabs',  null, array());

if (! $tabs) {
    foreach (array('politics', 'economy', 'sports', 'world') as $slug) {
        $term = get_term_by('slug', $slug, 'category');
        if (! $term) continue;
        $tabs[] = array('label' => $term->name, 'category' => $term, 'count' => 5);
    }
}
?>
<section class="section taxonomy-tabs">
    <div class="container-wide">
        <div class="section-head"><h2><?php echo esc_html($title); ?></h2></div>
        <div class="taxonomy-tabs__nav" role="tablist">
            <?php foreach ((array) $tabs as $i => $tab):
                $term = is_object($tab['category']) ? $tab['category'] : ($tab['category'] ? get_term((int) $tab['category'], 'category') : null);
                if (! $term) continue;
            ?>
                <button type="button" class="taxonomy-tabs__btn<?php echo $i === 0 ? ' is-active' : ''; ?>" data-na-tab-btn="<?php echo esc_attr($term->slug); ?>" role="tab" aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>">
                    <?php echo esc_html($tab['label'] ?: $term->name); ?>
                </button>
            <?php endforeach; ?>
        </div>
        <div class="taxonomy-tabs__panels">
            <?php foreach ((array) $tabs as $i => $tab):
                $term  = is_object($tab['category']) ? $tab['category'] : ($tab['category'] ? get_term((int) $tab['category'], 'category') : null);
                $count = (int) ($tab['count'] ?? 5);
                if (! $term) continue;
                $posts = get_posts(array('numberposts' => $count ?: 5, 'cat' => $term->term_id));
            ?>
                <div class="taxonomy-tabs__panel<?php echo $i === 0 ? ' is-active' : ''; ?>" data-na-tab="<?php echo esc_attr($term->slug); ?>" role="tabpanel" <?php echo $i === 0 ? '' : 'hidden'; ?>>
                    <?php foreach ($posts as $p): ?>
                        <a href="<?php echo esc_url(get_permalink($p)); ?>" class="card-row card">
                            <?php echo na_thumb($p, '4/3', 'medium', '160px'); ?>
                            <div class="body">
                                <h3 class="title"><?php echo esc_html(get_the_title($p)); ?></h3>
                                <div class="meta"><?php echo esc_html(na_time_ago($p->ID)); ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
