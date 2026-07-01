<?php
/** Search-specific pagination + a tail hint about quoted-string + filter usage. */
if (! defined('ABSPATH')) exit;
?>
<div style="display: flex; justify-content: center; align-items: center; gap: 6px; margin-top: 36px; padding-top: 24px; border-top: 1px solid var(--gray-2);">
    <span style="padding: 0 16px; opacity: 0.5; font-family: var(--f-ui); font-size: 13px;">← अघिल्लो</span>
    <span style="min-width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; background: var(--red); color: #fff; font-family: var(--f-ui); font-size: 14px; font-weight: 600;">1</span>
    <a href="#" style="min-width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; background: #fff; color: var(--ink); border: 1px solid var(--gray-3); font-family: var(--f-ui); font-size: 14px; font-weight: 600;">2</a>
    <a href="#" style="min-width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; background: #fff; color: var(--ink); border: 1px solid var(--gray-3); font-family: var(--f-ui); font-size: 14px; font-weight: 600;">3</a>
    <a href="#" style="min-width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; background: #fff; color: var(--ink); border: 1px solid var(--gray-3); font-family: var(--f-ui); font-size: 14px; font-weight: 600;">4</a>
    <a href="#" style="padding: 0 16px; font-family: var(--f-ui); font-size: 13px; color: var(--ink);">पछिल्लो →</a>
</div>

<div class="no-results-hint">
    <strong style="font-weight: 700;">सुझाव:</strong> थप विशिष्ट परिणाम पाउन प्रदेश र जिल्ला फिल्टर प्रयोग गर्नुहोस्। उद्धरण चिह्न <code class="en" style="background: rgba(0,0,0,0.06); padding: 1px 4px;">"बजेट २०८३"</code> प्रयोग गरेर ठ्याक्कै मिल्ने वाक्यांश खोज्न सक्नुहुन्छ।
</div>
