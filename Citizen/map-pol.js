// Arxikopoihsh tou xarth
const map = L.map("map").setView([39.192232, 24.242514], 5);

const tiles = L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
  maxZoom: 19, //orismos megistou epipedou zoom
  attribution:
    '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
}).addTo(map);

let citizenMarkersLayer = new L.LayerGroup();
map.addLayer(citizenMarkersLayer);

// Function to fetch citizen coordinates from the server and display markers
function fetchCitizenCoords() {
  fetch("get_citizen_coords.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.length > 0) {
        data.forEach((coord) => {
          createCitizenMarker(coord);
        });
      } else {
        console.error("No citizen coordinates found");
      }
    })
    .catch((error) => {
      console.error("Failed to fetch citizen coordinates", error);
    });
}

// Call the function to fetch citizen coordinates and display markers
fetchCitizenCoords();

//create standard marker for citizen coordinates
function createCitizenMarker(coord) {
  // Add marker for citizen coordinates to the citizenMarkersLayer
  L.marker([coord.lat, coord.lng]).addTo(citizenMarkersLayer);
}
