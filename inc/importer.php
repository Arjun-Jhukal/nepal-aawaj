<?php
/**
 * Dummy Content Importer.
 *
 * Adds a Tools → "Rastriya Aawaj Importer" page with three actions:
 *   1. Seed categories      — creates the 11 categories from na_get_categories().
 *   2. Seed pages           — creates Home/About/Contact/Privacy/Terms with the
 *                              block layout from inc/page-formatters.php and
 *                              assigns the matching page template.
 *   3. Seed sample posts    — creates ~20 dummy posts spread across categories
 *                              so the homepage blocks have real data to render.
 *
 * Each action is idempotent: re-running won't duplicate posts, it just
 * updates the existing entries (matched by slug).
 *
 * This is dev-only scaffolding — once the live site is populated, the
 * importer page can be removed by deleting this require in functions.php.
 */
if (! defined('ABSPATH')) exit;

/**
 * Admin menu entry.
 */
function na_importer_menu() {
    add_management_page(
        __('Rastriya Aawaj Importer', 'rastriya-aawaj'),
        __('NA Importer', 'rastriya-aawaj'),
        'manage_options',
        'na-importer',
        'na_importer_page'
    );
}
add_action('admin_menu', 'na_importer_menu');

/**
 * Importer screen.
 */
function na_importer_page() {
    if (! current_user_can('manage_options')) return;

    $messages = array();
    if (isset($_POST['na_import_action']) && check_admin_referer('na_importer')) {
        $action = sanitize_key(wp_unslash($_POST['na_import_action']));
        switch ($action) {
            case 'all':
                $messages[] = na_import_categories();
                $messages[] = na_import_posts();
                $messages[] = na_import_pages();
                $messages[] = na_assign_front_page();
                break;
            case 'categories':
                $messages[] = na_import_categories();
                break;
            case 'posts':
                $messages[] = na_import_posts();
                break;
            case 'pages':
                $messages[] = na_import_pages();
                $messages[] = na_assign_front_page();
                break;
            case 'reset':
                $messages[] = na_import_reset();
                break;
        }
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Rastriya Aawaj Importer', 'rastriya-aawaj'); ?></h1>
        <p><?php echo esc_html__('Seeds categories, sample posts, and theme pages so the front-end has data to render. Safe to re-run — actions are idempotent.', 'rastriya-aawaj'); ?></p>

        <?php foreach ($messages as $msg): ?>
            <div class="notice notice-success"><p><?php echo wp_kses_post($msg); ?></p></div>
        <?php endforeach; ?>

        <form method="post" style="margin-top:24px;">
            <?php wp_nonce_field('na_importer'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php echo esc_html__('Run everything', 'rastriya-aawaj'); ?></th>
                    <td>
                        <button type="submit" name="na_import_action" value="all" class="button button-primary">
                            <?php echo esc_html__('Import categories + posts + pages', 'rastriya-aawaj'); ?>
                        </button>
                        <p class="description"><?php echo esc_html__('Recommended after a fresh theme activation.', 'rastriya-aawaj'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Individual', 'rastriya-aawaj'); ?></th>
                    <td>
                        <button type="submit" name="na_import_action" value="categories" class="button"><?php echo esc_html__('Categories only', 'rastriya-aawaj'); ?></button>
                        <button type="submit" name="na_import_action" value="posts" class="button"><?php echo esc_html__('Sample posts only', 'rastriya-aawaj'); ?></button>
                        <button type="submit" name="na_import_action" value="pages" class="button"><?php echo esc_html__('Pages + templates only', 'rastriya-aawaj'); ?></button>
                    </td>
                </tr>
                <tr>
                    <th scope="row" style="color:#a00;"><?php echo esc_html__('Reset', 'rastriya-aawaj'); ?></th>
                    <td>
                        <button type="submit" name="na_import_action" value="reset" class="button"
                            onclick="return confirm('<?php echo esc_js(__('This will trash all importer-created posts/pages. Continue?', 'rastriya-aawaj')); ?>');">
                            <?php echo esc_html__('Remove all importer content', 'rastriya-aawaj'); ?>
                        </button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php
}

/**
 * Importer key — meta stamp we set on every importer-created post so
 * the reset action knows what's safe to delete.
 */
const NA_IMPORTER_META = '_na_importer';

/* -------------------------------------------------------------------- */
/* Categories                                                           */
/* -------------------------------------------------------------------- */

function na_import_categories() {
    $count = 0;
    foreach (na_get_categories() as $cat) {
        $existing = get_term_by('slug', $cat['slug'], 'category');
        if ($existing) {
            wp_update_term($existing->term_id, 'category', array(
                'name'        => $cat['np'],
                'description' => $cat['en'],
            ));
        } else {
            wp_insert_term($cat['np'], 'category', array(
                'slug'        => $cat['slug'],
                'description' => $cat['en'],
            ));
            $count++;
        }
    }
    return sprintf(
        /* translators: %d = number of new categories */
        __('Categories: created %d new term(s); existing terms refreshed.', 'rastriya-aawaj'),
        $count
    );
}

/* -------------------------------------------------------------------- */
/* Sample posts                                                         */
/* -------------------------------------------------------------------- */

/**
 * Dummy post seed data — 20 posts spread across categories.
 *
 * @return array<int,array{title:string,excerpt:string,content:string,category:string,author?:string}>
 */
function na_dummy_posts() {
    return array(
        array('title' => 'संसद बैठक स्थगित: बजेट अधिवेशनमा थप तनाव, सरकार र प्रतिपक्ष आमने-सामने', 'category' => 'politics',      'excerpt' => 'मुख्य प्रतिपक्षी दलले राजनीतिक नियुक्तिमा छानबिनको माग गर्दै संसद बैठक अवरुद्ध गरेका छन्।'),
        array('title' => 'सत्ता गठबन्धनमा फेरि तनाव: मन्त्रिमण्डल फेरबदलको चर्चा',                'category' => 'politics',      'excerpt' => 'गठबन्धनभित्र देखिएको तनाव र राजनीतिक समीकरण फेरबदलको सम्भावना।'),
        array('title' => 'गठबन्धनको भविष्य: तीन वर्ष पुग्दा कति टिक्ला यो सरकार?',                'category' => 'politics',      'excerpt' => 'सत्तासीन गठबन्धनभित्र देखिएको तनाव र राजनीतिक समीकरण फेरबदलको सम्भावनालाई लिएर विश्लेषण।'),

        array('title' => 'रुपैयाँ डलरसँग रु. १३२.५० मा स्थिर, बजार सकारात्मक',                  'category' => 'economy',       'excerpt' => 'विदेशी मुद्रा बजारमा रुपैयाँको स्थिति आज दिनभरि स्थिर रह्यो।'),
        array('title' => 'वाणिज्य बैंकहरूले ब्याजदर घटाए, ऋण लिने सहज',                          'category' => 'economy',       'excerpt' => 'केन्द्रीय बैंकको नयाँ निर्देशनसँगै बजारमा तरलता बढेसँगै ब्याजदरमा गिरावट।'),
        array('title' => 'नेप्से २१४८ अंकमा बन्द, बैंकिङ समूह अग्रणी',                          'category' => 'economy',       'excerpt' => 'आजको कारोबार सकिँदा नेप्से सकारात्मक रह्यो।'),
        array('title' => 'सुनको मूल्य फेरि बढ्यो, तोलाको रु. १,४८,२००',                          'category' => 'economy',       'excerpt' => 'अन्तर्राष्ट्रिय बजारमा सुनको मूल्य बढेसँगै नेपाली बजारमा पनि असर।'),

        array('title' => 'हिमाल आरोहणमा शेर्पाहरूको नयाँ कीर्तिमान, १४औं चुचुरो सम्म',            'category' => 'world',         'excerpt' => 'विश्वका १४ वटा आठ हजार मिटर माथिका शिखरमा सफल आरोहण।'),
        array('title' => 'सीमा क्षेत्रमा सुरक्षा बढाइयो, संयुक्त गस्ती तीव्र',                     'category' => 'world',         'excerpt' => 'दुई देशका सुरक्षा निकायबीच समन्वय गरिएको।'),

        array('title' => 'विश्वकपका लागि नेपाली टोली घोषणा, रोहित नेतृत्वमा',                    'category' => 'sports',        'excerpt' => 'विश्वकप क्रिकेटका लागि १५ सदस्यीय टोली घोषणा।'),
        array('title' => 'नेपाली फुटबल लिगमा रेकर्ड भिड, टिकट तीन घण्टामै सकियो',                'category' => 'sports',        'excerpt' => 'दशरथ रंगशालामा भएको खेल हेर्न दर्शकको ओइरो।'),

        array('title' => 'नेपाली फिल्मले अन्तर्राष्ट्रिय महोत्सवमा पुरस्कार जित्यो',              'category' => 'entertainment', 'excerpt' => 'काठमाडौंमा बनेको फिल्म युरोपको चलचित्र महोत्सवमा सम्मानित।'),
        array('title' => 'नयाँ नेपाली गीतले युट्युबमा १ करोड हेराइ पुर्\u{200d}यायो',                   'category' => 'entertainment', 'excerpt' => 'गायकको पछिल्लो रिलिजले एक हप्तामै रेकर्ड बनायो।'),

        array('title' => 'नेपालमा ५जी सेवाको परीक्षण सुरु, उपत्यकाबाट सुरुवात',                  'category' => 'tech',          'excerpt' => 'दूरसञ्चार प्राधिकरणले परीक्षण अनुमति दिएको।'),
        array('title' => 'AI नीति मस्यौदा सार्वजनिक, सर्वसाधारणबाट सुझाव माग',                  'category' => 'tech',          'excerpt' => 'सूचना तथा सञ्चार मन्त्रालयले मस्यौदा वेबसाइटमा राख्यो।'),

        array('title' => 'डेङ्गुको प्रकोप: तराईमा दैनिक १२० नयाँ बिरामी थपिँदै',                  'category' => 'health',        'excerpt' => 'स्वास्थ्य मन्त्रालयले सतर्क रहन आग्रह गरेको छ।'),
        array('title' => 'खोप अभियानको नयाँ चरण सुरु, बालबालिकालाई प्राथमिकता',                 'category' => 'health',        'excerpt' => 'नियमित खोप तालिका अनुसार नयाँ चरण सञ्चालन।'),

        array('title' => 'इन्द्रजात्राको रौनक: काठमाडौंमा परम्परागत झाँकी प्रदर्शन',              'category' => 'culture',       'excerpt' => 'सहरका विभिन्न क्षेत्रमा लाखौंको उपस्थिति।'),

        array('title' => 'SEE परीक्षाको नतिजा यही महिनाभित्र, बोर्डद्वारा तयारी अन्तिम चरणमा',     'category' => 'education',     'excerpt' => 'परीक्षा बोर्डले नतिजा तयारी अन्तिम चरणमा रहेको जनाएको।'),

        array('title' => 'हाम्रो हिमाल, हाम्रो भविष्य: जलवायु परिवर्तनको असरमा हिमाली जिल्ला',     'category' => 'multimedia',    'excerpt' => '२८ मिनेटको डकुमेन्ट्री।'),

        array('title' => 'सम्पादकीय: सूचना अधिकार र पारदर्शिताको प्रश्न',                       'category' => 'opinion',       'excerpt' => 'राज्यका हरेक तहमा पारदर्शिता आवश्यक छ।'),
    );
}

function na_import_posts() {
    na_import_categories(); // ensure terms exist

    $created = 0;
    $updated = 0;
    foreach (na_dummy_posts() as $i => $row) {
        $slug = sanitize_title('na-dummy-' . ($i + 1));
        $existing = get_page_by_path($slug, OBJECT, 'post');

        $args = array(
            'post_type'    => 'post',
            'post_status'  => 'publish',
            'post_title'   => $row['title'],
            'post_name'    => $slug,
            'post_excerpt' => $row['excerpt'],
            'post_content' => "<!-- wp:paragraph -->\n<p>" . esc_html($row['excerpt']) . "</p>\n<!-- /wp:paragraph -->",
            'post_date'    => date('Y-m-d H:i:s', strtotime("-{$i} hours")),
        );

        if ($existing) {
            $args['ID'] = $existing->ID;
            $id = wp_update_post($args, true);
            $updated++;
        } else {
            $id = wp_insert_post($args, true);
            $created++;
        }

        if (is_wp_error($id) || ! $id) continue;

        $term = get_term_by('slug', $row['category'], 'category');
        if ($term) wp_set_post_terms($id, array($term->term_id), 'category', false);

        update_post_meta($id, NA_IMPORTER_META, '1');
        update_post_meta($id, 'na_view_count', wp_rand(1200, 32000));
    }
    return sprintf(
        /* translators: 1: created count, 2: updated count */
        __('Sample posts: %1$d created, %2$d updated.', 'rastriya-aawaj'),
        $created,
        $updated
    );
}

/* -------------------------------------------------------------------- */
/* Pages                                                                */
/* -------------------------------------------------------------------- */

function na_import_pages() {
    $created = 0;
    $updated = 0;
    foreach (na_page_blueprints() as $slug => $bp) {
        $content   = (string) call_user_func($bp['formatter']);
        $existing  = get_page_by_path($slug, OBJECT, 'page');

        $args = array(
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_title'   => $bp['title'],
            'post_name'    => $slug,
            'post_content' => $content,
        );
        if ($existing) {
            $args['ID'] = $existing->ID;
            $id = wp_update_post($args, true);
            $updated++;
        } else {
            $id = wp_insert_post($args, true);
            $created++;
        }
        if (is_wp_error($id) || ! $id) continue;

        update_post_meta($id, NA_IMPORTER_META, '1');

        // Seed ACF defaults so dynamic templates render real content the
        // moment the page is created.
        na_seed_acf_for_page($slug, $id);
    }
    return sprintf(
        /* translators: 1: created count, 2: updated count */
        __('Pages: %1$d created, %2$d updated.', 'rastriya-aawaj'),
        $created,
        $updated
    );
}

/**
 * Populate ACF fields on importer-created pages with placeholder data.
 * Each entry mirrors the field structure defined in /acf-json/.
 */
function na_seed_acf_for_page($slug, $post_id) {
    if (! function_exists('update_field')) return;

    switch ($slug) {
        case 'about':
            update_field('about_kicker',   'हाम्रो बारेमा · ABOUT US', $post_id);
            update_field('about_headline', 'सत्य, निष्पक्ष र राष्ट्रको आवाज', $post_id);
            update_field('about_lede',     'राष्ट्रिय आवाज नेपालको एक स्वतन्त्र डिजिटल समाचार पोर्टल हो — विश्वसनीय समाचार, गहिरो विश्लेषण र दृढ पत्रकारिताको माध्यमबाट हाम्रो समयको कथा लेख्ने प्रयास।', $post_id);
            update_field('about_mission',  '<p>स्वतन्त्र, निष्पक्ष र भरपर्दो पत्रकारिता मार्फत नेपाली समाजलाई बलियो बनाउनु हाम्रो लक्ष्य हो।</p>', $post_id);
            update_field('about_principles', array(
                array('title' => 'सत्यता',    'body' => 'तथ्यमा आधारित समाचार, जाँचिएको स्रोत।',          'icon' => 'search'),
                array('title' => 'निष्पक्षता', 'body' => 'कुनै दलगत स्वार्थबिनाको खबर।',                   'icon' => 'bell'),
                array('title' => 'जवाफदेहिता', 'body' => 'गल्ती भए सुधार, पाठकप्रति उत्तरदायी।',           'icon' => 'home'),
                array('title' => 'पारदर्शिता',  'body' => 'सम्पादकीय मापदण्ड र वित्तीय स्वार्थ खुला।',      'icon' => 'email'),
            ), $post_id);
            update_field('about_stats', array(
                array('value' => '१०० लाख+', 'label' => 'मासिक पाठक'),
                array('value' => '५०+',      'label' => 'पत्रकार'),
                array('value' => '७७',       'label' => 'जिल्ला कभरेज'),
                array('value' => '२४/७',     'label' => 'समाचार अपडेट'),
            ), $post_id);
            update_field('about_team', array(
                array('name' => 'सरिता शाह',     'role' => 'प्रधान सम्पादक'),
                array('name' => 'किरण थापा',     'role' => 'राजनीति डेस्क प्रमुख'),
                array('name' => 'पंकज खनाल',     'role' => 'अर्थ डेस्क प्रमुख'),
                array('name' => 'मनिषा राई',    'role' => 'मनोरञ्जन सम्पादक'),
            ), $post_id);
            update_field('about_timeline', array(
                array('year' => '२०७८', 'title' => 'सुरुवात',    'body' => 'काठमाडौंमा सानो टोलीबाट सुरु।'),
                array('year' => '२०७९', 'title' => 'विस्तार',    'body' => 'सात प्रदेशमा ब्युरो स्थापना।'),
                array('year' => '२०८०', 'title' => 'मल्टिमिडिया', 'body' => 'भिडियो र पोडकास्ट डिभिजन सुरु।'),
                array('year' => '२०८१', 'title' => 'मोबाइल',     'body' => 'iOS र Android एप रिलिज।'),
            ), $post_id);
            break;

        case 'contact':
            update_field('contact_intro', '<p>समाचार सुझाव, प्रतिक्रिया, साझेदारी, विज्ञापन वा कुनै पनि प्रश्न — हामीसँग जोडिनुहोस्।</p>', $post_id);
            update_field('contact_form', array(
                'to_email' => 'hello@nepalaawaj.com',
                'subject'  => 'वेबसाइटबाट सम्पर्क',
                'button'   => 'पठाउनुहोस्',
            ), $post_id);
            update_field('contact_departments', array(
                array('name' => 'समाचार डेस्क',  'lead' => 'सम्पादक', 'email' => 'news@nepalaawaj.com',    'phone' => '+977-1-4444555'),
                array('name' => 'विज्ञापन',      'lead' => 'व्यवसाय', 'email' => 'ads@nepalaawaj.com',      'phone' => '+977-1-4444556'),
                array('name' => 'साझेदारी',     'lead' => 'BD टोली', 'email' => 'partners@nepalaawaj.com', 'phone' => '+977-1-4444557'),
            ), $post_id);
            break;

        case 'privacy':
            update_field('legal_last_updated', current_time('Y-m-d'), $post_id);
            update_field('legal_intro', '<p>तपाईंको गोपनीयता हाम्रो सर्वोच्च प्राथमिकता हो।</p>', $post_id);
            update_field('legal_sections', na_legal_sections_privacy(), $post_id);
            break;

        case 'terms':
            update_field('legal_last_updated', current_time('Y-m-d'), $post_id);
            update_field('legal_intro', '<p>राष्ट्रिय आवाजको सेवा प्रयोग गर्नुअघि यी सर्तहरू ध्यानपूर्वक पढ्नुहोस्।</p>', $post_id);
            update_field('legal_sections', na_legal_sections_terms(), $post_id);
            break;
    }
}

function na_legal_sections_privacy() {
    return array(
        array('anchor' => 's1', 'title' => 'परिचय',                'body' => '<p>राष्ट्रिय आवाजमा स्वागत छ। यो गोपनीयता नीति तपाईंको व्यक्तिगत जानकारीको सङ्कलन र प्रयोगका बारेमा बताउँछ।</p>'),
        array('anchor' => 's2', 'title' => 'सङ्कलित जानकारी',       'body' => '<p>खाता विवरण, प्रतिक्रिया, न्युजलेटर सदस्यता र प्राविधिक डेटा सङ्कलन हुन्छ।</p>'),
        array('anchor' => 's3', 'title' => 'जानकारीको प्रयोग',      'body' => '<p>सेवा प्रदान, व्यक्तिगत सिफारिस, सुरक्षा र विश्लेषणका लागि।</p>'),
        array('anchor' => 's4', 'title' => 'कुकीज र ट्र्याकिङ',     'body' => '<p>आवश्यक, विश्लेषणात्मक, व्यक्तिगतीकरण र विज्ञापन कुकीज।</p>'),
        array('anchor' => 's5', 'title' => 'तेस्रो पक्षसँग साझेदारी', 'body' => '<p>हामी व्यक्तिगत जानकारी कसैलाई बिक्री गर्दैनौं।</p>'),
        array('anchor' => 's6', 'title' => 'डेटा सुरक्षा',           'body' => '<p>SSL एन्क्रिप्सन, सुरक्षित पासवर्ड हासिङ र नियमित अडिट।</p>'),
        array('anchor' => 's7', 'title' => 'तपाईंका अधिकार',        'body' => '<p>पहुँच, सुधार, हटाउने र डेटा पोर्टेबिलिटीका अधिकार।</p>'),
        array('anchor' => 's8', 'title' => 'बालबालिकाको गोपनीयता',  'body' => '<p>१३ वर्ष मुनिका बालबालिकाको जानकारी सङ्कलन गरिँदैन।</p>'),
        array('anchor' => 's9', 'title' => 'परिवर्तन',              'body' => '<p>ठूलो परिवर्तन भएमा ३० दिन अग्रिम सूचना।</p>'),
        array('anchor' => 's10','title' => 'सम्पर्क',                'body' => '<p>privacy@nepalaawaj.com मा सम्पर्क गर्नुहोस्।</p>'),
    );
}

function na_legal_sections_terms() {
    return array(
        array('anchor' => 's1',  'title' => 'सर्तहरूको स्वीकृति',       'body' => '<p>हाम्रो सेवा प्रयोग गरेर तपाईंले यी सर्तहरू स्वीकार गर्नुहुन्छ।</p>'),
        array('anchor' => 's2',  'title' => 'सेवाको परिभाषा',           'body' => '<p>राष्ट्रिय आवाज एक डिजिटल समाचार पोर्टल हो।</p>'),
        array('anchor' => 's3',  'title' => 'प्रयोगकर्ता खाता',         'body' => '<p>खाता खोल्न सही जानकारी अनिवार्य।</p>'),
        array('anchor' => 's4',  'title' => 'सामग्री र बौद्धिक सम्पत्ति','body' => '<p>हाम्रो सामग्री प्रतिलिपि अधिकारले सुरक्षित।</p>'),
        array('anchor' => 's5',  'title' => 'प्रयोगकर्ता आचरण',         'body' => '<p>घृणाजनक, अवैध वा हानिकारक सामग्री निषेधित।</p>'),
        array('anchor' => 's6',  'title' => 'कमेन्ट र प्रतिक्रिया',     'body' => '<p>कमेन्ट मध्यस्थताका विषय हुनेछन्।</p>'),
        array('anchor' => 's7',  'title' => 'विज्ञापन र भुक्तानी',      'body' => '<p>विज्ञापनदाताको सामग्री हाम्रो विचार होइन।</p>'),
        array('anchor' => 's8',  'title' => 'दायित्वको सीमा',           'body' => '<p>सेवा "जस्तो छ" आधारमा प्रदान गरिन्छ।</p>'),
        array('anchor' => 's9',  'title' => 'सेवा स्थगन',               'body' => '<p>सर्त उल्लंघन भएमा खाता बन्द हुनसक्छ।</p>'),
        array('anchor' => 's10', 'title' => 'विवाद समाधान',             'body' => '<p>नेपालको अदालतको अधिकारक्षेत्र।</p>'),
        array('anchor' => 's11', 'title' => 'सर्त परिवर्तन',             'body' => '<p>सर्तहरू समय-समयमा अद्यावधिक हुनेछन्।</p>'),
        array('anchor' => 's12', 'title' => 'सम्पर्क',                   'body' => '<p>legal@nepalaawaj.com मा सम्पर्क गर्नुहोस्।</p>'),
    );
}

/**
 * Wire the freshly-created "Home" page as the static front page so the
 * block-based layout in post_content actually renders. front-page.php
 * already short-circuits to its own layout when active, but setting
 * show_on_front lets editors switch between modes.
 */
function na_assign_front_page() {
    $home = get_page_by_path('home', OBJECT, 'page');
    if (! $home) return __('Front page assignment skipped (Home page not found).', 'rastriya-aawaj');

    update_option('show_on_front', 'page');
    update_option('page_on_front', $home->ID);
    return __('Reading settings → Static front page set to "Home".', 'rastriya-aawaj');
}

/* -------------------------------------------------------------------- */
/* Reset                                                                */
/* -------------------------------------------------------------------- */

function na_import_reset() {
    $q = new WP_Query(array(
        'post_type'      => array('post', 'page'),
        'post_status'    => 'any',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_key'       => NA_IMPORTER_META,
    ));
    $count = 0;
    foreach ($q->posts as $id) {
        if (wp_delete_post($id, true)) $count++;
    }
    return sprintf(
        /* translators: %d = number removed */
        __('Removed %d importer-created posts/pages.', 'rastriya-aawaj'),
        $count
    );
}
