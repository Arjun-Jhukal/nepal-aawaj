<?php
/**
 * Theme comments template, loaded by comments_template().
 * Renders the comment list (themed via wp_list_comments + na_comment_callback)
 * and the WP comment form.
 */
if (! defined('ABSPATH')) exit;

if (post_password_required()) return;
?>
<?php if (have_comments()): ?>
    <div id="commentList">
        <?php
        wp_list_comments(array(
            'style'        => 'div',
            'short_ping'   => true,
            'avatar_size'  => 44,
            'reply_text'   => 'रिप्लाई',
        ));
        ?>
    </div>

    <?php if (get_option('page_comments') && get_comment_pages_count() > 1):
        $links = paginate_comments_links(array('echo' => false, 'type' => 'array')) ?: array();
    ?>
        <div style="text-align: center; margin-top: 20px;">
            <?php foreach ($links as $link) echo $link; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php
if (comments_open()):
    comment_form(array(
        'class_container' => 'comment-form',
        'class_form'      => 'comment-form-fields',
        'title_reply'     => 'तपाईंको प्रतिक्रिया लेख्नुहोस्',
        'label_submit'    => 'पठाउनुहोस्',
        'class_submit'    => 'btn btn-red',
        'comment_field'   => '<textarea class="field" id="comment" name="comment" placeholder="आफ्नो विचार राख्नुहोस्... (सम्मानजनक र विषयसँग सम्बन्धित कुरा मात्र पठाउनुहोस्)" required></textarea>',
        'comment_notes_before' => '',
        'comment_notes_after'  => '<div class="hint">समीक्षा पछि मात्र प्रतिक्रिया प्रकाशित हुनेछ।</div>',
    ));
endif;
