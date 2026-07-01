<?php
/** Sticky table of contents for long-form legal pages. Auto-numbered via CSS counter.
 *
 *   get_template_part('template-parts/legal/toc', null, array(
 *       'items' => array(
 *           array('id' => 's1', 'label' => 'परिचय'),
 *           array('id' => 's2', 'label' => 'सङ्कलित जानकारी'),
 *           // ...
 *       ),
 *   ));
 *
 *  The first item gets `.active` initially; the scroll-spy script updates as the user scrolls.
 */
if (! defined('ABSPATH')) exit;

$items = isset($args['items']) ? (array) $args['items'] : array();
?>
<aside>
    <nav class="toc">
        <h4>विषयसूची <span class="en">TOC</span></h4>
        <ol>
            <?php foreach ($items as $i => $it) :
                $cls = ($i === 0) ? ' class="active"' : '';
            ?>
                <li><a href="#<?php echo esc_attr($it['id']); ?>"<?php echo $cls; ?>><?php echo esc_html($it['label']); ?></a></li>
            <?php endforeach; ?>
        </ol>
    </nav>
</aside>
