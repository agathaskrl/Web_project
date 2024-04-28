// Arxikopoihsh tou xarth
const map = L.map("map").setView([39.192232, 24.242514], 5);

L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
  maxZoom: 19, //max level
  attribution:
    '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
}).addTo(map);

// arxikopoihsh synentgmenwn
let markcoords = [0, 0];

//fernei ta coordinates apothn vash
function fetch_current_coords() {
  fetch("get_marker_coords.php")
    .then((response) => response.json())
    .then((data) => {
      if (data && data.lat && data.lng) {
        markcoords = [parseFloat(data.lat), parseFloat(data.lng)];
        createmark(markcoords); // Pass markcoords to createmark
      }
    })
    .catch((error) => {
      console.error("Failed to fetch marker coordinates", error);
    });
}

// Function gia na gieni draagable o marker
function createmark(coords) {
  // Receive coords as parameter
  var vashicon = L.icon({
    iconUrl: "vash_mark.png",
    iconSize: [42, 42],
    iconAnchor: [16, 32],
  });

  // Add marker_vash to the map with custom icon
  let marker_vash = L.marker(coords, { draggable: true, icon: vashicon }).addTo(
    map
  );

  marker_vash.on("dragend", function (event) {
    const marker_vash = event.target;
    const position = marker_vash.getLatLng();

    // Check if the user is sure about moving the marker_vash
    const isSure = window.confirm("Are you sure you want to move the base?");

    if (!isSure) {
      // if he cancles stays in origianl position
      marker_vash.setLatLng(markcoords);
    } else {
      //update the new coordintes
      markcoords = [position.lat, position.lng];
      savemarkerdata(markcoords[0], markcoords[1]);
    }
  });
}

//fere ta deodmena tou marker_vash
fetch_current_coords();

//function gia save marker_vash data in the server
function savemarkerdata(lat, lng) {
  //Dhmioyrgia AJAX requests gia na kanei save ta nea coords
  fetch("save_marker_data.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ lat: lat, lng: lng }),
  })
    .then((response) => {
      if (response.ok) {
        console.log("marker data saved successfully");
      } else {
        console.error("Failed to save marker_vash data");
      }
    })
    .catch((error) => {
      console.error("Error saving marker_vash data", error);
    });
}

//dunction gi na brei ta coords twn vihicles
function fetch_vehicle_coords() {
  fetch("get_veh_coords.php")
    .then((response) => response.json())
    .then((data) => {
      if (data && data.length > 0) {
        data.forEach((vehicle) => {
          // Create marker for each vehicle
          create_vehicle_marker([
            parseFloat(vehicle.lat),
            parseFloat(vehicle.lng),
          ]);
        });
      } else {
        console.error("No vehicle marker coordinates found");
      }
    })
    .catch((error) => {
      console.error("Failed to fetch vehicle marker coordinates", error);
    });
}

function create_vehicle_marker() {
  var vehicon = L.icon({
    iconUrl: "vehicle.png",
    iconSize: [42, 42],
    iconAnchor: [16, 32],
  });

  $.getJSON("get_veh_data.php", function (data) {
    data.forEach(function (vehicle) {
      var coords = [vehicle.lat, vehicle.lng]; // Use coordinates from data
      L.marker(coords, { icon: vehicon })
        .bindPopup(
          "<h3>Username:</h3><p>" +
            vehicle.sav_username +
            "</p><h3>Cargo:</h3><p>" +
            vehicle.cargo +
            "</p><h3>Tasks:</h3>" +
            vehicle.under_tasks +
            "</p>"
        )
        .addTo(map);
    });
  });
}

//klhsh ths function gia na deixei ta markes oxhmatwn
fetch_vehicle_coords();
