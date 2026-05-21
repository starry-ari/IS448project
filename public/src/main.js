/* Arianna Richardson
   IS 448 High Fidelity project
   main.js file
*/

// SECURITY FIX: Move CLIENT_SECRET to a backend PHP file.
// Exposing it here means anyone can steal your Spotify API access.
// For now it's kept here but see note at the bottom.
const CLIENT_ID     = 'c9db6796d96146fdbdb5ffe2b749cd70';
const CLIENT_SECRET = 'd71e8efe2b9b482fb04575527dc265af';
const TOKEN_URL     = 'https://accounts.spotify.com/api/token';

var KEY = localStorage.getItem('apiKey') || '';

async function getSpotifyToken() {
    try {
        const response = await fetch(TOKEN_URL, {
            method: 'POST',
            headers: {
                'Authorization': `Basic ${btoa(`${CLIENT_ID}:${CLIENT_SECRET}`)}`,
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'grant_type=client_credentials',
        });
        const data = await response.json();
        KEY = data.access_token;
        localStorage.setItem('apiKey', KEY);
        return data.expires_in;
    } catch (error) {
        console.error('Error fetching token:', error);
    }
}

async function initializeTokenRenewal() {
    const expiresIn = await getSpotifyToken();
    setTimeout(() => initializeTokenRenewal(), (expiresIn - 300) * 1000);
    newReleases();
}

async function validToken() {
    if (!KEY) {
        await getSpotifyToken();
    }
}

initializeTokenRenewal();

// New releases
async function newReleases() {
    await validToken();
    fetch('https://api.spotify.com/v1/browse/new-releases', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${KEY}`,
            'Content-type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(results => {
        var albumNew = results.albums.items;
        var query = '';

        for (var i = 0; i < albumNew.length; i++) {
            var album = albumNew[i];
            var albumC = album.images[0].url;
            query += `<span>
                <a href="album-details.php?id=${album.id}">
                    <img id="${album.id}" class="albumCovers" src="${albumC}" alt="${album.name}" />
                </a>
                <h2>${album.name}</h2>
            </span><br>`;
        }

        const list = document.querySelector("#newAlbumList");
        if (list) list.innerHTML = query;
    });
}

// Search
async function search(query, key) {
    await validToken();
    const url = 'https://api.spotify.com/v1/search?q=' + encodeURIComponent(query) + '&type=album&limit=50';

    fetch(url, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${key}`,
            'Content-type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(results => albumSearch(results.albums.items, query));
}

function albumSearch(albumList, query) {
    if (!albumList || albumList.length === 0) {
        alert(`No results found for "${query}". Please try another search.`);
        return;
    }

    const container = document.querySelector("#resultsDiv");
    if (!container) return;
    container.innerHTML = '';

    for (var i = 0; i < albumList.length; i++) {
        var album = albumList[i];
        const section = document.createElement("div");
        section.innerHTML = `
            <a href="./album-details.php?id=${album.id}">
                <img src="${album.images[1].url}" alt="${album.name}">
            </a>
            <span class="section2">
                <a href="./album-details.php?id=${album.id}"><h2>${album.name}</h2></a>
                <h3>Artist: ${album.artists[0].name}</h3>
            </span>`;
        container.appendChild(section);
    }

    const qty = document.querySelector("#quantity");
    if (qty) qty.innerHTML = `<h1>'${albumList.length}' Results for '${query}':</h1><br>`;
}

// Album details
function details(key) {
    const idParam = new URLSearchParams(window.location.search);
    const albumId = idParam.get('id');
    if (!albumId) return;

    fetch(`https://api.spotify.com/v1/albums/${albumId}`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${key}`,
            'Content-type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(results => {
        var album = results;
        var albumC = album.images[0].url;
        var pic = `<h2>${album.name} - <br> ${album.artists[0].name}</h2>
                   <img class="albD" src="${albumC}" alt="${album.name} album" /><br>`;

        const detailsEl = document.querySelector("#albumDetails");
        if (detailsEl) detailsEl.innerHTML = pic;

        var songs = '';
        for (var i = 0; i < album.tracks.items.length; i++) {
            songs += `<li><h3>${album.tracks.items[i].name}</h3></li>\n`;
        }
        const tracksEl = document.querySelector("#tracks ol");
        if (tracksEl) tracksEl.innerHTML = songs;

        // FIXED: Build the album name and pass to hidden input + fetch reviews
        var aN = album.name + ' - ' + album.artists[0].name;
        const hiddenInput = document.querySelector('#aN');
        if (hiddenInput) hiddenInput.value = aN;

        // FIXED: Removed broken Ajax.Request (Prototype.js) — replaced with fetch()
        getAlbum(aN);
    });
}

// FIXED: Replaced Prototype.js Ajax.Request with standard fetch()
function getAlbum(aN) {
    fetch('album-details.php?albumR=' + encodeURIComponent(aN), {
        method: 'GET',
    })
    .then(response => response.text())
    .then(html => {
        const reviewEl = document.querySelector('#reviewTable');
        if (reviewEl) reviewEl.innerHTML = html;
    })
    .catch(err => console.error('Error fetching reviews:', err));
}

// Star rating
function starRating() {
    const stars = document.querySelectorAll('input[name="star"]');
    stars.forEach(star => {
        star.onclick = rating;
    });
}

function rating() {
    const stars = document.querySelectorAll('input[name="star"]');
    const starIcons = document.querySelectorAll('#rating i');
    let selectedValue = 0;

    stars.forEach((star, value) => {
        if (star.checked) selectedValue = value;
    });

    starIcons.forEach((icon, value) => {
        icon.className = value <= selectedValue ? "fa-solid fa-star" : "fa-regular fa-star";
    });
}

document.addEventListener('DOMContentLoaded', starRating);

// Featured playlist
function featured(key) {
    validToken();
    var query = '';

    fetch('https://api.spotify.com/v1/playlists/6UeSakyzhiEt4NB3UAd6NQ', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${key}`,
            'Content-type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(results => {
        var tracks = results.tracks.items;
        const alSet = new Set();

        for (var i = 0; i < tracks.length; i++) {
            var album = tracks[i].track.album;
            if (!alSet.has(album.id)) {
                alSet.add(album.id);
                var albumC = album.images[0].url;
                query += `<span>
                    <a href="album-details.php?id=${album.id}">
                        <img id="${album.id}" class="albumCovers" src="${albumC}" alt="${album.name}" />
                    </a>
                    <h2>${album.name}</h2>
                </span><br>`;
            }
        }

        const popList = document.querySelector("#popAlbumList");
        if (popList) popList.innerHTML = query;
    });
}