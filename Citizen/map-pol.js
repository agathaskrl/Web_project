// Arxikopoihsh tou xarth
const map = L.map("map").setView([39.192232, 24.242514], 5);

const tiles = L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
  maxZoom: 19, //orismos megistou epipedou zoom
  attribution:
    '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
}).addTo(map);

let citizenMarkersLayer = new L.LayerGroup();
map.addLayer(citizenMarkersLayer);

// Function fernei tis syntetagmenes twn politwn apo ton server kai deixnei ta markers
function fetchCitizenCoords() {
  fetch("get_citizen_coords.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.length > 0) {
        data.forEach((coord) => {
          // Create a marker for each coordinate
          L.marker([coord.lat, coord.lng]).addTo(citizenMarkersLayer);
        });
      } else {
        console.error("No citizen coordinates found");
      }
    })
    .catch((error) => {
      console.error("Failed to fetch citizen coordinates", error);
    });
}
// Klhsh tou function gi na deijei to marker
fetchCitizenCoords();
