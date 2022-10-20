const preLoad = function () {
    return caches.open("offline").then(function (cache) {
        // caching index and important routes
        return cache.addAll(filesToCache);
    });
};

self.addEventListener("install", function (event) {
    event.waitUntil(preLoad());
});

const filesToCache = [
    '/',
    '/offline.html',
    '/images/favicon.png',
    '/images/logo_3d.svg',
    '/images/logo_sph.svg',
    '/images/no-orders.svg',
    '/images/pwa/icon-48x48.webp',
    '/images/pwa/icon-72x72.webp',
    '/images/pwa/icon-96x96.webp',
    '/images/pwa/icon-128x128.webp',
    '/images/pwa/icon-192x192.webp',
    '/images/pwa/icon-384x384.webp',
    '/images/pwa/icon-512x512.webp',
    '/images/pwa/icon-512x512.png'
];

const checkResponse = function (request) {
    return new Promise(function (fulfill, reject) {
        fetch(request).then(function (response) {
            if (response.status !== 404) {
                fulfill(response);
            } else {
                reject();
            }
        }, reject);
    });
};

const addToCache = function (request) {
    return caches.open("offline").then(function (cache) {
        return fetch(request).then(function (response) {
            return cache.put(request, response);
        });
    });
};

const returnFromCache = function (request) {
    return caches.open("offline").then(function (cache) {
        return cache.match(request).then(function (matching) {
            if (!matching || matching.status === 404) {
                return cache.match("offline.html");
            } else {
                return matching;
            }
        });
    });
};

self.addEventListener("fetch", function (event) {
    if ( event.request.url.indexOf( '/www/' ) !== -1 ) {
        return false;
    }
    event.respondWith(checkResponse(event.request).catch(function () {
        return returnFromCache(event.request);
    }));
    if(!event.request.url.startsWith('http')){
        event.waitUntil(addToCache(event.request));
    }
});
