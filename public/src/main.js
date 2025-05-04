/* Arianna Richardson
   IS 448 High Fidelity project
   main.js file
*/

// Utilizing the Web Spotify API to populate both sites with album information.
/*I used this API so it would be easier to integrate album data in the
 long run without having to enter in the album data manually.
*/




var KEY = '';
const CLIENT_ID = 'c9db6796d96146fdbdb5ffe2b749cd70'
const CLIENT_SECRET = 'd71e8efe2b9b482fb04575527dc265af'
const TOKEN_URL = 'https://accounts.spotify.com/api/token';

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

        //console.log('Access Token:', KEY);
        return data.expires_in; 
    } catch (error) {
       // console.error('Error fetching token:', error);
    }
}
//Function to renew API token 5 min before expiration
async function initializeTokenRenewal() {
    const expiresIn = await getSpotifyToken();
 
    
    setTimeout(() => initializeTokenRenewal(), (expiresIn - 300) * 1000);
    //console.log("expires in:" + expiresIn);

   newReleases();
   
   

   
}




//Checks if token is valid to use in other API requests
async function validToken() {
    if (!KEY){
  
        await getSpotifyToken();
    }

    
}
 
initializeTokenRenewal();





// Using fetch() and GET method to access the new releases album covers endpoint from the Spotify API
async function newReleases(){
    await validToken();
fetch('https://api.spotify.com/v1/browse/new-releases?', {
	method: 'GET',
	headers: {
		'Authorization': `Bearer ${KEY}`,
                'Content-type': 'application/json'
    }
    

})

//Response is converted to a JSON to parse though data easily
.then(response => {
    return response.json();
})



//Album information is returned as results to be sifted through 
.then(results => {search
   var albumNew =  results.albums.items;
  
   var query = '';
    
   // Looping through new albums and displaying covers
   for(var i = 0; i < albumNew.length; i++) {
        var album = albumNew[i];
        var albumC = album.images[0].url; // Album cover URL
        
        //HTML to display album covers
        var newA = ` <span><a href="album-details.php?id=${album.id}"><img id="${album.id}"class="albumCovers" src="${albumC}" alt="${album.name}" /></a>
       <h2>${album.name}</h2> </span> <br>`;
        query += newA;
   }
  // console.log(KEY);



   // Adding new album covers to HTML
   document.querySelector("#newAlbumList").innerHTML = query;
   const images = document.querySelectorAll("img");

   images.forEach(image => {
    image.addEventListener("click", function () {
      // `this` refers to the clicked image
     



  window.location.href = ' album-details.php?id=' + this.id;
     
   });

});

});

}
   
   




// Searching Albums

// Fetching using search endpoint


async function search(query, key){
    await validToken();
    //console.log("current: "+ KEY);



//console.log(query);
const url = 'https://api.spotify.com/v1/search?q='+ encodeURIComponent(query) + "&type=album&limit=50";






   fetch( url, {
	method: 'GET',
    headers: {
		'Authorization': `Bearer ${key}`,
        'Content-type': 'application/json'
    }
    
})
.then(response => {
    
    return response.json();
})
.then(results => {
   
    albumSearch(results.albums.items, query);
}
)
};



 function albumSearch(albumList,query){
   
   
    if (!albumList || albumList.length === 0) {
        alert(`No results found for "${query}". Please try another search.`);
        return;
    }
    
    const container = document.querySelector("#resultsDiv");
    var album;
 
    for(var i = 0; i < albumList.length; i++) {
        album = albumList[i];
        const section = document.createElement("div");
        section.innerHTML = 

        `<a href="./album-details.php"> <img src="${album.images[1].url}" alt="${album.name}" ></a>
        <span class=section2><a href="./album-details.php?id=${album.id}"><h2>${album.name}</h2> </a>
        <h3>Artist: ${album.artists[0].name}</h3></span>`;
        container.appendChild(section);
    }
    document.querySelector("#quantity").innerHTML = `<h1> '${albumList.length}' Results for '${query}':</h1>
    <br>`;
    const titles = document.querySelectorAll("h2 a");

    titles.forEach(title => {
     title.addEventListener("click", function () {
       // `this` refers to the title of the album
   window.location.href = './album-details.php?id=' + this.id;
      
    })});


    
}

var aN;

//----------------ALBUM DETAILS PAGE ------------------

function details(key){
    
    
    const idParam = new URLSearchParams(window.location.search);
    const albumId = idParam.get('id');
      
  
    
 


// Fetching using the album's specific ID as an endpoint
fetch(`https://api.spotify.com/v1/albums/${albumId}`, {
	method: 'GET',
    headers: {
		'Authorization': `Bearer ${key}`,
        'Content-type': 'application/json'
    }
})

//Response is converted to a JSON to parse through data easily
.then(response => {
    return response.json();
})
.then(results => {
  var album = results; // Storing the album data

   var query = '';
   var songs ='';

   // Retrieving album name and cover image
   var albumC = album.images[0].url;
   var pic = `<?php $albumR = ${album.name}?> <h2>${album.name} - <br> ${album.artists[0].name}</h2><img class="albD" src="${albumC}" alt="${album.name} album" /> <br>`;
   query += pic;
    

   // Adding album details to the page
   document.querySelector("#albumDetails").innerHTML = query;
 aN = document.querySelector('#aN');

aN = album.name +' - ' + album.artists[0].name;
console.log(aN);

   //Adding album tracks:

  for(var i = 0; i < album.tracks.items.length; i++) {

    var track = album.tracks.items[i];
 songs+= `<li><h3>${track.name}</h3></li>\n`;


}

  
document.querySelector("#tracks ol").innerHTML = songs;
getAlbum(aN);
});


}
//Star rating functionality
function starRating() {
    // Add event listeners for each radio button
    
    const stars = document.querySelectorAll('input[name="star"]');
    stars.forEach(star => {
        star.onclick = rating;
    });
}
/* possible favorite button functionality
function favorite() {
    const button = document.getElementById('AD');
    if (button.innerText === "Favorite") {
        button.innerText = "Added";
        button.style.backgroundColor='white';
        button.style.color='#ff87a3';
        console.log("yes");
        $("#fave").val("yes");
    } else {
        button.innerText = "Favorite";
        button.style.backgroundColor='#ff87a3';
        button.style.color='white';
        $("#fave").val("no");
        console.log("no");
    }
        */
//Ajax code implementation for album name.



function getAlbum(aN){
    var nameA=aN.value;
    console.log(nameA);
    new Ajax.Request ('album-details.php',

        {
            method: "GET",
            parameters: {albumR: nameA},
            onSuccess: getAlbumReview
        }
    );

   }
   function getAlbumReview(ajax){

   }

function rating() {
    const stars = document.querySelectorAll('input[name="star"]');
    const starIcons = document.querySelectorAll('#rating i');

    let selectedValue = 0;

    // Selected star value
    stars.forEach((star, value) => {
        if (star.checked) {
            selectedValue = value; 
          
        }
    });

    // Update the class for each star icon
    starIcons.forEach((icon, value) => {
        if (value <= selectedValue) {
            icon.className = "fa-solid fa-star"; // Highlight selected stars
        } else {
            icon.className = "fa-regular fa-star"; // Reset unselected stars
        }
    });
    
}

// Initialize the functionality
document.addEventListener('DOMContentLoaded', starRating);



 function featured(key){
validToken();
var query ='';
fetch('https://api.spotify.com/v1/playlists/6UeSakyzhiEt4NB3UAd6NQ', {
	method: 'GET',
	headers: {
		'Authorization': `Bearer ${key}`,
                'Content-type': 'application/json'
    }
    

})

//Response is converted to a JSON to parse though data easily
.then(response => {
    return response.json();
})



//Album information is returned as results to be sifted through 
.then(results => {
   var tracks =  results.tracks.items;
  
   const alSet = new Set();
   // Looping through new albums and displaying covers
   for(var i = 0; i < tracks.length; i++) {
  
     // Added a set to prevent duplicate albums.
     var album = tracks[i].track.album;
   

     if (!alSet.has(album.id)) {
        alSet.add(album.id);
     
        var albumC = album.images[0].url; // Album cover URL
      
        //HTML to display album covers
        var newA = ` <span><a href="album-details.php?id=${album.id}"><img id="${album.id}"class="albumCovers" src="${albumC}" alt="${album.name}" /></a>
       <h2>${album.name}</h2> </span> <br>`;
        query += newA;
   }
}




   // Adding new album covers to HTML
   document.querySelector("#popAlbumList").innerHTML = query;
 
   });

}

   