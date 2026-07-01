<?php
/**
 * Comments — WordPress comments_template() output with the theme's styling.
 * The form + list use WP defaults; the surrounding chrome (heading, count)
 * stays themed.
 */
if (! defined('ABSPATH')) exit;

$count = (int) get_comments_number();
?>
<div class="comments" id="comments">
    <h3>प्रतिक्रिया <?php if ($count): ?><span class="ct"><?php echo esc_html(na_to_devanagari($count)); ?> कमेन्ट</span><?php endif; ?></h3>
    <?php comments_template('', true); ?>
</div>
