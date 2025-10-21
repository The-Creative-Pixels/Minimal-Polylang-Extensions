document.addEventListener('DOMContentLoaded', function() {
    if (typeof PolylangLanguages === 'undefined') return;

    // Determine current language key
    const currentLang = Object.keys(PolylangLanguages)
        .find(key => PolylangLanguages[key].current_lang) || '';

    // === Update links on elements with .lang-btn.lang-XX classes
    document.querySelectorAll('.lang-btn').forEach(el => {
        const langClass = Array.from(el.classList)
            .find(c => c.startsWith('lang-') && c !== 'lang-btn');
        if (!langClass) return;

        const lang = langClass.replace('lang-', '');
        const link = el.querySelector('a');
        if (link && PolylangLanguages[lang]) {
            link.href = PolylangLanguages[lang].url;
        }
    });

  // Replace "LAN" placeholder anywhere inside elements with class .lang-switcher
   if (currentLang) {
    document.querySelectorAll('.lang-switcher').forEach(container => {
        console.log('Checking .lang-switcher container:', container);
        function replaceLanTextNodes(node) {
            node.childNodes.forEach(child => {
                if (child.nodeType === Node.TEXT_NODE) {
                    if (child.textContent.trim() === 'LAN') {
                        console.log('Replacing LAN text:', child);
                        child.textContent = currentLang.toUpperCase();
                    }
                } else if (child.nodeType === Node.ELEMENT_NODE) {
                    replaceLanTextNodes(child);
                }
            });
        }
        replaceLanTextNodes(container);
    });
}

    console.log('Manual Polylang Switcher: links and LAN labels updated.');
});