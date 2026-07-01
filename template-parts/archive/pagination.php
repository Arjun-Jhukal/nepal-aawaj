<?php
/** Numbered pagination via paginate_links(). Style matches the static reference. */
if (! defined('ABSPATH')) exit;

global $wp_query;
$total = (int) ($wp_query->max_num_pages ?? 0);
if ($total < 2) return;

$current = max(1, get_query_var('paged') ?: 1);
$links   = paginate_links(array(
    'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
    'format'    => '?paged=%#%',
    'current'   => $current,
    'total'     => $total,
    'mid_size'  => 1,
    'end_size'  => 1,
    'prev_text' => '← अघिल्लो',
    'next_text' => 'पछिल्लो →',
    'type'      => 'array',
));
if (! $links) return;
?>
<div class="pagination">
    <?php foreach ($links as $link):
        // paginate_links emits anchors/spans with class="page-numbers"; normalise to the theme's styling.
        $link = str_replace('page-numbers current', 'current', $link);
        $link = str_replace('page-numbers dots',    'ellipsis', $link);
        $link = str_replace('page-numbers prev',    'nav', $link);
        $link = str_replace('page-numbers next',    'nav', $link);
        $link = str_replace('class="page-numbers"', '', $link);
        echo $link;
    endforeach; ?>
</div>
