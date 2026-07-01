<?php
/**
 * Template Name: Offline
 * Slug: offline
 * Shown when user is offline and page is not cached
 */
get_header();
?>

<main class="error-page">
    <div class="container">
        <div class="error-page__content">
            <h1 class="error-page__code" style="font-size:48px">अफलाइन</h1>
            <h2 class="error-page__title">इन्टरनेट जडान छैन</h2>
            <p class="error-page__desc">कृपया आफ्नो इन्टरनेट जडान जाँच गर्नुहोस् र पुन: प्रयास गर्नुहोस्।</p>
            <div class="error-page__actions">
                <button onclick="window.location.reload()" class="error-page__btn">पुन: प्रयास</button>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
?>
