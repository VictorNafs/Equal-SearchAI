self.addEventListener('install', event => {
  event.waitUntil(
    caches.open('v1').then(cache => {
      return cache.addAll([
        'index.php',
        'index.css',
        'logo_EQEX.ico',
        'logo_large.png',
        'loading.gif',
        'manifest.json',
        'mentions.html',
        'politique_cookies.html',
        'PC_M.css',
        'twitter_large.png',
      ]);
    })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request);
    })
  );
});
