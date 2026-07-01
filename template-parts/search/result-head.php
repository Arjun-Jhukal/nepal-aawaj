<?php
/** Result heading — "<q>" को परिणाम + count + sort select + active filter chips.
 *  $args['q'] = current query. */
if (! defined('ABSPATH')) exit;

$q = isset($args['q']) ? (string) $args['q'] : '';
?>
<div class="result-head">
    <div>
        <h2>"<span class="q" id="qDisplay"><?php echo esc_html($q); ?></span>" को परिणाम</h2>
        <div class="count en" style="margin-top: 4px;">४२ परिणाम · ०.०४ सेकेन्डमा · बागमती / काठमाडौं</div>
    </div>
    <select class="sort">
        <option>उपयुक्तता अनुसार</option>
        <option>नयाँ पहिले</option>
        <option>पुरानो पहिले</option>
        <option>सबैभन्दा पढिएको</option>
    </select>
</div>

<div class="active-filters">
    <span class="active-filter">प्रदेश: बागमती <button onclick="this.parentElement.remove()">✕</button></span>
    <span class="active-filter">जिल्ला: काठमाडौं <button onclick="this.parentElement.remove()">✕</button></span>
    <span class="active-filter">विभाग: राजनीति <button onclick="this.parentElement.remove()">✕</button></span>
    <span class="active-filter">विभाग: अर्थ <button onclick="this.parentElement.remove()">✕</button></span>
    <span class="active-filter">मिति: यो हप्ता <button onclick="this.parentElement.remove()">✕</button></span>
    <span class="active-filter">प्रकार: लेख <button onclick="this.parentElement.remove()">✕</button></span>
    <span class="active-filter">प्रकार: भिडियो <button onclick="this.parentElement.remove()">✕</button></span>
</div>
