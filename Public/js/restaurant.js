async function getAdresse(lat, lon){
    // Faire une requête reverse geocoding à Nominatim pour obtenir l'adresse
    let geocodeUrl = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json&addressdetails=1`;
    
    try {
        // Faire une requête fetch à l'API Nominatim et attendre la réponse
        let response = await fetch(geocodeUrl);
        let data = await response.json();

        if (data && data.address) {
            let address = data.address;
            let fullAddress = `${address.road || ''}, ${address.city || ''}, ${address.country || ''}`;
            return fullAddress;
        } else {
            console.log("Aucune adresse trouvée pour les coordonnées données.");
            return "Adresse inconnue";
        }
    } catch (error) {
        console.error("Erreur lors de la récupération de l'adresse :", error);
        return "Erreur lors de la récupération de l'adresse";
    }
}


let element = document.getElementById('osm-map');
let lon = element.getAttribute("lon")
let lat = element.getAttribute("lat")

// Carte
// Height has to be set. You can do this in CSS too.
element.style = 'height:300px;';

// Create Leaflet map on map element.
let map = L.map(element);

// Add OSM tile layer to the Leaflet map.
L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Target's GPS coordinates.
let target = L.latLng(lat, lon);

// Set map's center to target with zoom 14.
map.setView(target, 14);

// Place a marker on the same location.
L.marker(target).addTo(map);



getAdresse(lat, lon).then(address => {
    console.log("Adresse récupérée :", address);
    // Afficher l'adresse récupérée dans un élément HTML (par exemple avec l'id "Adresse")
    adresse = document.getElementById("Adresse")
    strong = document.createElement("strong")
    strong.textContent = "Adresse : "
    adresse.appendChild(strong)
    // Créer un élément pour afficher l'adresse (par exemple, un <span>)
    let addressElement = document.createElement("span");
    addressElement.textContent = address;
    adresse.appendChild(addressElement);
}).catch(error => {
    console.error("Erreur lors de la récupération de l'adresse :", error);
    document.getElementById("Adresse").textContent = "Erreur lors de la récupération de l'adresse";
});



