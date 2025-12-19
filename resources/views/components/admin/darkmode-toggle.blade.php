<button id="darkmode-toggle" class="p-2 rounded border border-gray-200 hover:bg-gray-100" type="button" aria-pressed="false">
    <span class="sr-only">Toggle dark mode</span>
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
</button>
<script>
    (function () {
        const btn = document.getElementById('darkmode-toggle');
        if (!btn) return;
        const root = document.documentElement;
        const key = 'xeon-darkmode';
        const saved = localStorage.getItem(key);
        if (saved === '1') root.classList.add('dark');
        btn.setAttribute('aria-pressed', root.classList.contains('dark'));
        btn.addEventListener('click', () => {
            root.classList.toggle('dark');
            const enabled = root.classList.contains('dark');
            btn.setAttribute('aria-pressed', enabled);
            localStorage.setItem(key, enabled ? '1' : '0');
        });
    })();
</script>
