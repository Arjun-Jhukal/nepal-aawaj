<?php
/**
 * YouTube Channel Integration
 *
 * Fetches videos from a YouTube channel and caches them.
 * Can auto-create WordPress posts from YouTube videos.
 *
 * SETUP REQUIRED:
 * 1. Get a YouTube Data API v3 key from https://console.cloud.google.com
 * 2. Find your channel ID from YouTube (UC... format)
 * 3. Set both in Customizer → YouTube Integration
 */

// ============================================================
// SETTINGS (Customizer)
// ============================================================

function na_youtube_customizer($wp_customize) {
    $wp_customize->add_section('na_youtube', [
        'title'    => 'YouTube Integration',
        'priority' => 85,
    ]);

    $wp_customize->add_setting('na_yt_api_key', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('na_yt_api_key', [
        'label'       => 'YouTube API Key',
        'section'     => 'na_youtube',
        'type'        => 'text',
        'description' => 'Get from console.cloud.google.com → APIs → YouTube Data API v3',
    ]);

    $wp_customize->add_setting('na_yt_channel_id', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('na_yt_channel_id', [
        'label'       => 'YouTube Channel ID',
        'section'     => 'na_youtube',
        'type'        => 'text',
        'description' => 'e.g. UCxxxxxxxxxxxxxxxxxxxxxx',
    ]);

    $wp_customize->add_setting('na_yt_auto_import', [
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('na_yt_auto_import', [
        'label'   => 'Auto-import new videos as posts',
        'section' => 'na_youtube',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting('na_yt_import_category', [
        'default'           => '',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('na_yt_import_category', [
        'label'       => 'Import to Category ID',
        'section'     => 'na_youtube',
        'type'        => 'number',
        'description' => 'Category ID for auto-imported video posts (e.g. "video" category)',
    ]);
}
add_action('customize_register', 'na_youtube_customizer');

// ============================================================
// FETCH VIDEOS FROM YOUTUBE API
// ============================================================

/**
 * Get recent videos from the configured YouTube channel
 * Results are cached for 1 hour in a transient
 */
function na_get_youtube_videos($count = 10, $force_refresh = false) {
    $api_key    = get_theme_mod('na_yt_api_key', '');
    $channel_id = get_theme_mod('na_yt_channel_id', '');

    if (empty($api_key) || empty($channel_id)) {
        return [];
    }

    $cache_key = 'na_yt_videos_' . $count;

    if (!$force_refresh) {
        $cached = get_transient($cache_key);
        if ($cached !== false) {
            return $cached;
        }
    }

    $url = add_query_arg([
        'key'        => $api_key,
        'channelId'  => $channel_id,
        'part'       => 'snippet',
        'order'      => 'date',
        'maxResults' => $count,
        'type'       => 'video',
    ], 'https://www.googleapis.com/youtube/v3/search');

    $response = wp_remote_get($url, ['timeout' => 10]);

    if (is_wp_error($response)) {
        return [];
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (empty($body['items'])) {
        return [];
    }

    $videos = [];
    foreach ($body['items'] as $item) {
        $videos[] = [
            'id'          => $item['id']['videoId'],
            'title'       => $item['snippet']['title'],
            'description' => $item['snippet']['description'],
            'thumbnail'   => $item['snippet']['thumbnails']['high']['url'] ?? $item['snippet']['thumbnails']['default']['url'],
            'published'   => $item['snippet']['publishedAt'],
            'url'         => 'https://www.youtube.com/watch?v=' . $item['id']['videoId'],
            'embed'       => 'https://www.youtube.com/embed/' . $item['id']['videoId'],
        ];
    }

    // Cache for 1 hour
    set_transient($cache_key, $videos, HOUR_IN_SECONDS);

    return $videos;
}

// ============================================================
// AUTO-IMPORT VIDEOS AS POSTS
// ============================================================

/**
 * Import YouTube videos as WordPress posts
 * Runs on a twice-daily cron
 */
function na_youtube_auto_import() {
    if (!get_theme_mod('na_yt_auto_import', false)) return;

    $videos = na_get_youtube_videos(5, true);
    $category_id = get_theme_mod('na_yt_import_category', 0);

    foreach ($videos as $video) {
        // Check if already imported (by YouTube video ID in meta)
        $existing = get_posts([
            'post_type'  => 'post',
            'meta_key'   => '_na_youtube_id',
            'meta_value' => $video['id'],
            'posts_per_page' => 1,
        ]);

        if (!empty($existing)) continue;

        // Create post
        $post_id = wp_insert_post([
            'post_title'   => $video['title'],
            'post_content' => '<!-- wp:html --><div class="video-embed"><iframe src="' . esc_url($video['embed']) . '?rel=0" frameborder="0" allowfullscreen loading="lazy"></iframe></div><!-- /wp:html -->' . "\n\n" . '<p>' . esc_html($video['description']) . '</p>',
            'post_status'  => 'publish',
            'post_date'    => date('Y-m-d H:i:s', strtotime($video['published'])),
            'post_category' => $category_id ? [$category_id] : [],
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, '_na_youtube_id', $video['id']);
            update_post_meta($post_id, '_na_youtube_url', $video['url']);

            // Download and set thumbnail
            na_set_youtube_thumbnail($post_id, $video['thumbnail']);
        }
    }
}

// Schedule cron
function na_youtube_schedule_cron() {
    if (!wp_next_scheduled('na_youtube_import_cron')) {
        wp_schedule_event(time(), 'twicedaily', 'na_youtube_import_cron');
    }
}
add_action('init', 'na_youtube_schedule_cron');
add_action('na_youtube_import_cron', 'na_youtube_auto_import');

// Clean up cron on theme switch
function na_youtube_deactivate() {
    wp_clear_scheduled_hook('na_youtube_import_cron');
}
add_action('switch_theme', 'na_youtube_deactivate');

/**
 * Download YouTube thumbnail and set as featured image
 */
function na_set_youtube_thumbnail($post_id, $image_url) {
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $tmp = download_url($image_url);
    if (is_wp_error($tmp)) return;

    $file = [
        'name'     => 'yt-thumb-' . $post_id . '.jpg',
        'tmp_name' => $tmp,
    ];

    $attach_id = media_handle_sideload($file, $post_id);

    if (!is_wp_error($attach_id)) {
        set_post_thumbnail($post_id, $attach_id);
    }
}

// ============================================================
// TEMPLATE HELPERS
// ============================================================

/**
 * Check if a post is a YouTube video post
 */
function na_is_youtube_post($post_id = null) {
    if (!$post_id) $post_id = get_the_ID();
    return !empty(get_post_meta($post_id, '_na_youtube_id', true));
}

/**
 * Get YouTube video ID for a post
 */
function na_get_youtube_id($post_id = null) {
    if (!$post_id) $post_id = get_the_ID();
    return get_post_meta($post_id, '_na_youtube_id', true);
}

/**
 * Render YouTube video embed (lightweight: loads iframe on click)
 */
function na_render_youtube_embed($video_id, $title = '') {
    $thumb = "https://i.ytimg.com/vi/{$video_id}/hqdefault.jpg";
    $alt   = trim( (string) $title ) !== '' ? $title : 'YouTube video';
    return '<div class="yt-lite" data-id="' . esc_attr($video_id) . '">'
         . na_responsive_img_url( $thumb, $alt )
         . '<button class="yt-lite__play" aria-label="Play video">' . na_icon('play', 32) . '</button>'
         . '</div>';
}

/**
 * Admin page: manual import trigger
 */
function na_youtube_admin_page() {
    add_submenu_page(
        'tools.php',
        'YouTube Import',
        'YouTube Import',
        'manage_options',
        'na-youtube-import',
        'na_youtube_admin_render'
    );
}
add_action('admin_menu', 'na_youtube_admin_page');

function na_youtube_admin_render() {
    // Handle manual import
    if (isset($_POST['na_yt_import_now']) && check_admin_referer('na_yt_import')) {
        na_youtube_auto_import();
        echo '<div class="notice notice-success"><p>Import completed!</p></div>';
    }

    $api_key    = get_theme_mod('na_yt_api_key', '');
    $channel_id = get_theme_mod('na_yt_channel_id', '');
    $videos     = na_get_youtube_videos(5);
    ?>
    <div class="wrap">
        <h1>YouTube Import</h1>

        <?php if (empty($api_key) || empty($channel_id)): ?>
            <div class="notice notice-warning">
                <p>Set your YouTube API Key and Channel ID in <a href="<?php echo admin_url('customize.php?autofocus[section]=na_youtube'); ?>">Customizer → YouTube Integration</a></p>
            </div>
        <?php else: ?>
            <h2>Recent Videos (<?php echo count($videos); ?>)</h2>
            <table class="widefat">
                <thead><tr><th>Thumbnail</th><th>Title</th><th>Published</th><th>Status</th></tr></thead>
                <tbody>
                <?php foreach ($videos as $v):
                    $existing = get_posts(['post_type' => 'post', 'meta_key' => '_na_youtube_id', 'meta_value' => $v['id'], 'posts_per_page' => 1]);
                ?>
                    <tr>
                        <td><img src="<?php echo esc_url($v['thumbnail']); ?>" width="120"></td>
                        <td><a href="<?php echo esc_url($v['url']); ?>" target="_blank"><?php echo esc_html($v['title']); ?></a></td>
                        <td><?php echo date('Y-m-d', strtotime($v['published'])); ?></td>
                        <td><?php echo !empty($existing) ? '✅ Imported (<a href="' . get_edit_post_link($existing[0]->ID) . '">Edit</a>)' : '⏳ Not imported'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <form method="post" style="margin-top:1rem;">
                <?php wp_nonce_field('na_yt_import'); ?>
                <button type="submit" name="na_yt_import_now" class="button button-primary">Import Now</button>
            </form>
        <?php endif; ?>
    </div>
    <?php
}
