<?php
/**
 * Block: Archive Sidebar
 *
 * ACF fields (group_block_archive_sidebar):
 *   Editor's Pick
 *     - editor_pick_heading_np / _en (text)
 *     - editor_pick_items (repeater): label, title, meta, href
 *   Province Jump
 *     - province_heading_np / _en (text)
 *     - province_items (repeater): label, href, full_width
 *   Most Read
 *     - most_read_heading_np / _en (text)
 *     - most_read_items (repeater): title, meta, href
 *   Ad
 *     - ad_label (text), ad_url (url)
 *
 * Every field has a sensible default so the block renders the same content
 * the original template-part used to ship — the editor just gets to swap
 * each line out for their own copy. ACF is required for editing; without it
 * the defaults render and the block stays visible.
 */
if (! defined('ABSPATH')) exit;

/* ---------- Editor's Pick ---------- */
$ep_heading_np = na_field('editor_pick_heading_np', null, 'सम्पादक छनोट');
$ep_heading_en = na_field('editor_pick_heading_en', null, "Editor's Pick");
$ep_items      = na_field('editor_pick_items', null, array(
    array('label' => 'Election', 'title' => 'नयाँ निर्वाचन प्रणाली: किन र कसरी?', 'meta' => '४ दिन अघि', 'href' => '#'),
    array('label' => 'Court',    'title' => 'संविधानको पुनरावलोकन: अब के?',       'meta' => '६ दिन अघि', 'href' => '#'),
    array('label' => 'Cabinet',  'title' => 'मन्त्रिमण्डलमा युवा अनुहार कति?',     'meta' => '१ हप्ता अघि', 'href' => '#'),
));

/* ---------- Province Jump ---------- */
$pv_heading_np = na_field('province_heading_np', null, 'प्रदेशको खबर');
$pv_heading_en = na_field('province_heading_en', null, 'By Province');
$pv_items      = na_field('province_items', null, array(
    array('label' => 'कोशी',        'href' => '#', 'full_width' => false),
    array('label' => 'मधेस',        'href' => '#', 'full_width' => false),
    array('label' => 'बागमती',      'href' => '#', 'full_width' => false),
    array('label' => 'गण्डकी',      'href' => '#', 'full_width' => false),
    array('label' => 'लुम्बिनी',     'href' => '#', 'full_width' => false),
    array('label' => 'कर्णाली',     'href' => '#', 'full_width' => false),
    array('label' => 'सुदूरपश्चिम', 'href' => '#', 'full_width' => true),
));

/* ---------- Most Read ---------- */
$mr_heading_np = na_field('most_read_heading_np', null, 'सर्वाधिक पढिएको');
$mr_heading_en = na_field('most_read_heading_en', null, 'Most Read');
$mr_items      = na_field('most_read_items', null, array(
    array('title' => 'मन्त्रीको राजीनामा माग: सडकमा विद्यार्थी', 'meta' => '३२,४१२ हेरिएको', 'href' => '#'),
    array('title' => 'बजेट विवादमा फेरि तनाव',                  'meta' => '२८,१०० हेरिएको', 'href' => '#'),
    array('title' => 'सांसदहरूको सम्पत्ति विवरण: टप १०',          'meta' => '२४,८८० हेरिएको', 'href' => '#'),
    array('title' => 'निर्वाचन सम्बन्धी नयाँ विवाद',              'meta' => '२१,३५० हेरिएको', 'href' => '#'),
    array('title' => 'सर्वोच्चको आदेशले हल्लियो राजनीति',          'meta' => '१८,९४० हेरिएको', 'href' => '#'),
));

/* ---------- Ad ---------- */
$ad_label = na_field('ad_label', null, 'Ad · 300 × 250');
$ad_url   = na_field('ad_url', null, '');
?>
<aside>
    <?php if ($ep_items): ?>
    <div class="panel">
        <div class="panel-head">
            <span><?php echo esc_html($ep_heading_np); ?></span>
            <span class="en-sub"><?php echo esc_html($ep_heading_en); ?></span>
        </div>
        <div class="panel-body">
            <?php $last_ep = count($ep_items) - 1; foreach ($ep_items as $i => $row):
                $border = $i === $last_ep ? '' : 'border-bottom: 1px solid var(--gray-2);';
                $href   = ! empty($row['href']) ? $row['href'] : '#';
                $label  = isset($row['label']) ? $row['label'] : '';
                $title  = isset($row['title']) ? $row['title'] : '';
                $meta   = isset($row['meta'])  ? $row['meta']  : '';
            ?>
            <a href="<?php echo esc_url($href); ?>" class="card-row card" style="<?php echo esc_attr($border); ?> padding: 14px 0;">
                <div class="thumb ph" data-label="<?php echo esc_attr($label); ?>"></div>
                <div>
                    <h4 class="title" style="font-family: var(--f-display-dn); font-size: 14px;"><?php echo esc_html($title); ?></h4>
                    <?php if ($meta !== ''): ?>
                    <div class="meta" style="font-family: var(--f-ui); font-size: 11px; color: var(--gray-6); margin-top: 4px;"><?php echo esc_html($meta); ?></div>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($pv_items): ?>
    <div class="panel">
        <div class="panel-head">
            <span><?php echo esc_html($pv_heading_np); ?></span>
            <span class="en-sub"><?php echo esc_html($pv_heading_en); ?></span>
        </div>
        <div class="panel-body" style="padding: 12px 16px 16px;">
            <ul class="province-jump">
                <?php foreach ($pv_items as $row):
                    $href  = ! empty($row['href']) ? $row['href'] : '#';
                    $label = isset($row['label']) ? $row['label'] : '';
                    $cls   = ! empty($row['full_width']) ? ' class="full"' : '';
                ?>
                <li<?php echo $cls; ?>><a href="<?php echo esc_url($href); ?>"><?php echo esc_html($label); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($mr_items): ?>
    <div class="panel">
        <div class="panel-head">
            <span><?php echo esc_html($mr_heading_np); ?></span>
            <span class="en-sub"><?php echo esc_html($mr_heading_en); ?></span>
        </div>
        <div class="panel-body">
            <div class="numlist">
                <?php foreach ($mr_items as $row):
                    $href  = ! empty($row['href']) ? $row['href'] : '#';
                    $title = isset($row['title']) ? $row['title'] : '';
                    $meta  = isset($row['meta'])  ? $row['meta']  : '';
                ?>
                <a href="<?php echo esc_url($href); ?>" class="item"><div><div class="title"><?php echo esc_html($title); ?></div><div class="meta"><?php echo esc_html($meta); ?></div></div></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($ad_label !== '' || $ad_url !== ''): ?>
    <div class="panel" style="background: transparent; border-style: dashed;">
        <?php if ($ad_url): ?>
        <a href="<?php echo esc_url($ad_url); ?>" class="ph" style="aspect-ratio: 1/1.2; display:block;" data-label="<?php echo esc_attr($ad_label); ?>"></a>
        <?php else: ?>
        <div class="ph" style="aspect-ratio: 1/1.2;" data-label="<?php echo esc_attr($ad_label); ?>"></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</aside>
