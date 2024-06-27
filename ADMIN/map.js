//arxikopoihsh tou xarth
const map = L.map("map").setView([39.192232, 24.242514], 5);

const tiles = L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
  maxZoom: 19, //orismos megistou epipedou zoom
  attribution:
    '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
}).addTo(map);

navigator.geolocation.watchPosition(success, error); //parakolouthish ths topothesias xrhsth
let marker, circle, zoomed;
let markersLayer = new L.LayerGroup();
const markers = [];
map.addLayer(markersLayer);


let markerData = [];

function success(pos) {
  const lat = pos.coords.latitude;
  const lng = pos.coords.longitude;
  const accuracy = pos.coords.accuracy;
  if (marker) {
    map.removeLayer(marker);
    map.removeLayer(circle);
  }
  marker = L.marker([lat, lng]).addTo(map);
  circle = L.circle([lat, lng], { radius: accuracy }).addTo(map);

  //function gia to zoom tou xarth
  if (!zoomed) {
    map.fitBounds(circle.getBounds());
  }

  map.setView([lat, lng], 15);
}
