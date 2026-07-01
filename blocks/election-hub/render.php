<?php
/**
 * Block: Election Hub.
 *
 * ACF fields (group_block_election_hub):
 *   - title_np / title_en / status_text
 *   - last_updated   (date_time_picker)
 *   - parties        (repeater): { party (post_object → "party"), seats, color }
 *   - constituencies (repeater): { name, leading_candidate (post_object → "candidate"), party (post_object → "party"), votes }
 */
if (! defined('ABSPATH')) exit;

$title_np      = na_field('title_np',     null, 'निर्वाचन परिणाम');
$title_en      = na_field('title_en',     null, 'Election Results');
$status        = na_field('status_text',  null, 'अपडेट हुँदै');
$last_updated  = na_field('last_updated', null, current_time('mysql'));
$parties       = na_field('parties',      null, array());
$constituencies = na_field('constituencies', null, array());
?>
<section class="section election-hub">
    <div class="container-wide">
        <div class="section-head">
            <h2><?php echo esc_html($title_np); ?> <span class="en-sub"><?php echo esc_html($title_en); ?></span></h2>
            <span class="election-hub__status">● <?php echo esc_html($status); ?></span>
        </div>

        <div class="election-hub__parties">
            <?php foreach ((array) $parties as $row):
                $party = $row['party'] ?? null;
                if ($party && ! $party instanceof WP_Post) $party = get_post($party);
                if (! $party) continue;
                $color = $row['color'] ?? '#DC1F26';
                $seats = (int) ($row['seats'] ?? 0);
            ?>
                <a href="<?php echo esc_url(get_permalink($party)); ?>" class="election-party" style="--party-color: <?php echo esc_attr($color); ?>;">
                    <span class="election-party__dot"></span>
                    <span class="election-party__name"><?php echo esc_html(get_the_title($party)); ?></span>
                    <span class="election-party__seats"><?php echo esc_html(na_to_devanagari($seats)); ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <table class="election-table">
            <thead>
                <tr>
                    <th>निर्वाचन क्षेत्र</th>
                    <th>अग्र उम्मेदवार</th>
                    <th>दल</th>
                    <th>मत</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ((array) $constituencies as $row):
                    $candidate = $row['leading_candidate'] ?? null;
                    if ($candidate && ! $candidate instanceof WP_Post) $candidate = get_post($candidate);
                    $party_p = $row['party'] ?? null;
                    if ($party_p && ! $party_p instanceof WP_Post) $party_p = get_post($party_p);
                ?>
                    <tr>
                        <td><?php echo esc_html($row['name'] ?? ''); ?></td>
                        <td>
                            <?php if ($candidate): ?>
                                <a href="<?php echo esc_url(get_permalink($candidate)); ?>"><?php echo esc_html(get_the_title($candidate)); ?></a>
                            <?php else: echo '—'; endif; ?>
                        </td>
                        <td><?php echo $party_p ? '<a href="' . esc_url(get_permalink($party_p)) . '">' . esc_html(get_the_title($party_p)) . '</a>' : '—'; ?></td>
                        <td><?php echo esc_html(na_to_devanagari(number_format((int) ($row['votes'] ?? 0)))); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="election-hub__updated">अन्तिम अपडेट: <?php echo esc_html(mysql2date('j F Y, g:i a', $last_updated)); ?></div>
    </div>
</section>
