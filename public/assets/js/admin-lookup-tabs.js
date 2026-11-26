const tabList = document.querySelector('[data-lookup-tabs]');
const tabs = Array.from(tabList.querySelectorAll('[data-panel]'));
tabList.setAttribute('role', 'tablist');

function selectLookupTab(selected) {
    for (const tab of tabs) {
        const active = tab === selected;
        tab.setAttribute('aria-selected', String(active));
        tab.tabIndex = active ? 0 : -1;
        document.getElementById(tab.dataset.panel).hidden = !active;
    }
}

const initialTab = tabs.find(tab => tab.hasAttribute('aria-current'));
for (const [index, tab] of tabs.entries()) {
    const panel = document.getElementById(tab.dataset.panel);
    tab.removeAttribute('aria-current');
    tab.setAttribute('role', 'tab');
    tab.setAttribute('aria-controls', panel.id);
    panel.setAttribute('role', 'tabpanel');
    panel.setAttribute('aria-labelledby', tab.id);
    tab.addEventListener('click', event => {
        if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
        event.preventDefault();
        selectLookupTab(tab);
        history.replaceState(null, '', tab.href);
    });
    tab.addEventListener('keydown', event => {
        const targets = {
            ArrowLeft: (index + tabs.length - 1) % tabs.length,
            ArrowRight: (index + 1) % tabs.length,
            Home: 0,
            End: tabs.length - 1,
            ' ': index
        };
        if (!Object.hasOwn(targets, event.key)) return;
        event.preventDefault();
        const target = tabs[targets[event.key]];
        target.click();
        target.focus();
    });
}
selectLookupTab(initialTab);
