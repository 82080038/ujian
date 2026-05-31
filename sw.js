const CACHE_NAME = 'tryoutku-v1';
const urlsToCache = [
  '/ujian/',
  '/ujian/assets/css/custom.css',
  '/ujian/assets/js/app.js'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request);
    })
  );
});
