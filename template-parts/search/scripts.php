<?php
/** Search interactions — runSearch/clearFilters globals, dynamic district list. */
if (! defined('ABSPATH')) exit;
?>
<script>
(function () {
    window.runSearch = function () {
        var v = document.getElementById('q').value;
        document.getElementById('qDisplay').textContent = v || '(खाली)';
        var head = document.querySelector('.result-head .count');
        if (!head) return;
        head.textContent = 'खोज्दै…';
        setTimeout(function () { head.textContent = '४२ परिणाम · ०.०४ सेकेन्डमा · बागमती / काठमाडौं'; }, 400);
    };
    window.clearFilters = function () {
        document.querySelectorAll('.active-filter').forEach(function (f) { f.remove(); });
        document.querySelectorAll('.check-list input[type=checkbox]').forEach(function (c) { c.checked = false; });
        document.querySelectorAll('select.field').forEach(function (s) { s.value = ''; });
    };

    var districtsByProvince = {
        'कोशी प्रदेश': ['मोरङ', 'सुनसरी', 'झापा', 'इलाम', 'पाँचथर', 'ताप्लेजुङ', 'धनकुटा', 'तेह्रथुम', 'भोजपुर', 'संखुवासभा', 'खोटाङ', 'ओखलढुंगा', 'सोलुखुम्बु', 'उदयपुर'],
        'मधेस प्रदेश': ['सप्तरी', 'सिराहा', 'धनुषा', 'महोत्तरी', 'सर्लाही', 'रौतहट', 'बारा', 'पर्सा'],
        'बागमती प्रदेश': ['काठमाडौं', 'ललितपुर', 'भक्तपुर', 'काभ्रेपलाञ्चोक', 'नुवाकोट', 'धादिङ', 'मकवानपुर', 'चितवन', 'सिन्धुपाल्चोक', 'रसुवा', 'रामेछाप', 'दोलखा', 'सिन्धुली'],
        'गण्डकी प्रदेश': ['कास्की', 'लम्जुङ', 'तनहुँ', 'स्याङ्जा', 'पर्वत', 'बागलुङ', 'गुल्मी', 'म्याग्दी', 'मुस्ताङ', 'मनाङ', 'गोरखा'],
        'लुम्बिनी प्रदेश': ['रूपन्देही', 'कपिलवस्तु', 'नवलपरासी', 'पाल्पा', 'अर्घाखाँची', 'दाङ', 'प्युठान', 'रोल्पा', 'बाँके', 'बर्दिया', 'पूर्वी रुकुम', 'पश्चिमी रुकुम'],
        'कर्णाली प्रदेश': ['सुर्खेत', 'दैलेख', 'जाजरकोट', 'कालिकोट', 'जुम्ला', 'मुगु', 'हुम्ला', 'डोल्पा', 'सल्यान', 'पश्चिम रुकुम'],
        'सुदूरपश्चिम प्रदेश': ['कैलाली', 'कञ्चनपुर', 'डडेलधुरा', 'बैतडी', 'दार्चुला', 'बझाङ', 'बाजुरा', 'अछाम', 'डोटी']
    };
    var prov = document.getElementById('f-province');
    if (prov) {
        prov.addEventListener('change', function (e) {
            var v = e.target.value;
            var sel = document.getElementById('f-district');
            var opts = ['<option value="">सबै जिल्ला</option>'];
            if (v && districtsByProvince[v]) {
                districtsByProvince[v].forEach(function (d) { opts.push('<option>' + d + '</option>'); });
            } else {
                Object.keys(districtsByProvince).forEach(function (k) {
                    districtsByProvince[k].forEach(function (d) { opts.push('<option>' + d + '</option>'); });
                });
            }
            sel.innerHTML = opts.join('');
        });
    }
})();
</script>
