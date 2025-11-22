/**
 * Main JS for InfoTV
 * Получение manifest и ротация страниц
 */

async function fetchManifest(device_uuid, api_key) {
    const res = await fetch(`/api/manifest?device_uuid=${device_uuid}`, {
        headers: {
            'Authorization': `Bearer ${api_key}`
        }
    });
    return await res.json();
}

async function runCarousel(device_uuid, api_key) {
    const manifest = await fetchManifest(device_uuid, api_key);
    const playlist = manifest.displays[0].playlist;
    let index = 0;

    function showNext() {
        const page = playlist[index];
        console.log(`Отображаем страницу: ${page.page} (${page.template})`);
        index = (index + 1) % playlist.length;
        setTimeout(showNext, page.duration * 1000);
    }
    showNext();
}
