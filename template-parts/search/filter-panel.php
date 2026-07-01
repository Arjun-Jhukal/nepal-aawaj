<?php
/** Sticky filter sidebar — province, district, category, date, type, author. */
if (! defined('ABSPATH')) exit;
?>
<aside class="filter-panel">
    <h4>फिल्टर <button class="clear" onclick="clearFilters()">सबै हटाउनुहोस्</button></h4>

    <div class="filter-group">
        <div class="group-title">प्रदेश <span class="ct">PROVINCE</span></div>
        <select id="f-province" class="field">
            <option value="">सबै प्रदेश</option>
            <option>कोशी प्रदेश</option>
            <option>मधेस प्रदेश</option>
            <option selected>बागमती प्रदेश</option>
            <option>गण्डकी प्रदेश</option>
            <option>लुम्बिनी प्रदेश</option>
            <option>कर्णाली प्रदेश</option>
            <option>सुदूरपश्चिम प्रदेश</option>
        </select>
    </div>

    <div class="filter-group">
        <div class="group-title">जिल्ला <span class="ct">DISTRICT</span></div>
        <select id="f-district" class="field" style="margin-bottom: 8px;">
            <option value="">सबै जिल्ला</option>
            <option selected>काठमाडौं</option>
            <option>ललितपुर</option>
            <option>भक्तपुर</option>
            <option>काभ्रेपलाञ्चोक</option>
            <option>नुवाकोट</option>
            <option>धादिङ</option>
            <option>मकवानपुर</option>
            <option>चितवन</option>
            <option>सिन्धुपाल्चोक</option>
            <option>रसुवा</option>
            <option>रामेछाप</option>
            <option>दोलखा</option>
            <option>सिन्धुली</option>
        </select>
        <div style="font-family: var(--f-ui); font-size: 11px; color: var(--gray-6);">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: -1px;"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            जिल्ला सूची चयन गरिएको प्रदेशअनुसार देखिन्छ।
        </div>
    </div>

    <div class="filter-group">
        <div class="group-title">विभाग <span class="ct">CATEGORY</span></div>
        <div class="check-list">
            <label><input type="checkbox" checked> राजनीति <span class="ct">128</span></label>
            <label><input type="checkbox" checked> अर्थ <span class="ct">84</span></label>
            <label><input type="checkbox"> खेलकुद <span class="ct">62</span></label>
            <label><input type="checkbox"> मनोरञ्जन <span class="ct">41</span></label>
            <label><input type="checkbox"> प्रविधि <span class="ct">38</span></label>
            <label><input type="checkbox"> स्वास्थ्य <span class="ct">29</span></label>
            <label><input type="checkbox"> शिक्षा <span class="ct">24</span></label>
            <label><input type="checkbox"> संस्कृति <span class="ct">18</span></label>
            <label><input type="checkbox"> विश्व <span class="ct">52</span></label>
            <label><input type="checkbox"> विचार <span class="ct">16</span></label>
        </div>
    </div>

    <div class="filter-group">
        <div class="group-title">मिति <span class="ct">DATE RANGE</span></div>
        <div class="check-list" style="margin-bottom: 10px;">
            <label><input type="radio" name="date"> आज</label>
            <label><input type="radio" name="date" checked> यो हप्ता</label>
            <label><input type="radio" name="date"> यो महिना</label>
            <label><input type="radio" name="date"> यो वर्ष</label>
            <label><input type="radio" name="date"> कस्टम मिति</label>
        </div>
        <div class="range-row">
            <input type="date" class="field" value="2026-05-14">
            <input type="date" class="field" value="2026-05-21">
        </div>
    </div>

    <div class="filter-group">
        <div class="group-title">सामग्रीको प्रकार <span class="ct">TYPE</span></div>
        <div class="check-list">
            <label><input type="checkbox" checked> लेख / समाचार</label>
            <label><input type="checkbox" checked> भिडियो</label>
            <label><input type="checkbox"> पोडकास्ट</label>
            <label><input type="checkbox"> तस्बिर गैलरी</label>
            <label><input type="checkbox"> लाइभ ब्लग</label>
            <label><input type="checkbox"> इन्फोग्राफिक</label>
        </div>
    </div>

    <div class="filter-group">
        <div class="group-title">लेखक <span class="ct">AUTHOR</span></div>
        <select class="field">
            <option>सबै लेखक</option>
            <option>अनुप अधिकारी</option>
            <option>सुनिता पौडेल</option>
            <option>डा. रामकृष्ण आचार्य</option>
            <option>विकास तामाङ</option>
            <option>रीता कार्की</option>
        </select>
    </div>

    <div class="filter-group">
        <button class="btn btn-red" style="width: 100%;" onclick="runSearch()">परिणाम अपडेट गर्नुहोस्</button>
    </div>
</aside>
