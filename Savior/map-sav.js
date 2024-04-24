const map = L.map("map").setView([39.192232, 24.242514], 5);

L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
  maxZoom: 19,
  attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
}).addTo(map);

let saviorMarker; // Declare saviorMarker variable

// Function to fetch savior coordinates from the server and display marker
function fetchSaviorCoords() {
  fetch("get_sav_coords.php")
    .then((response) => response.json())
    .then((data) => {
      if (data && data.lat && data.lng) {
        const saviorCoords = [parseFloat(data.lat), parseFloat(data.lng)];
        createSaviorMarker(saviorCoords);
      } else {
        console.error("No savior coordinates found");
      }
    })
    .catch((error) => {
      console.error("Failed to fetch savior coordinates", error);
    });
}

// Call the function to fetch savior coordinates and display marker
fetchSaviorCoords();

// Function to create marker for savior coordinates
function createSaviorMarker(coords) {
  // If there is an existing savior marker, remove it
  if (saviorMarker) {
    map.removeLayer(saviorMarker);
  }

  var vehicleIcon = L.icon({
    iconUrl: "vehicle.png",
    iconSize: [42, 42],
    iconAnchor: [21, 21],
  });

  // Create marker for savior coordinates with custom icon and draggable option
  saviorMarker = L.marker(coords, {
    icon: vehicleIcon,
    draggable: true  // Make the marker draggable
  }).addTo(map);

  // Add drag event listener to savior marker
  saviorMarker.on('dragend', function(event){
    const marker = event.target;
    const position = marker.getLatLng();

    // Prompt user to confirm updating coordinates in the database
    const isSure = window.confirm("Are you sure you want to update the coordinates in the database?");

    if (isSure) {
      // Update coordinates in the database
      updateCoordinates(position.lat, position.lng);
    } else {
      // If user cancels, revert to original position
      marker.setLatLng(coords);
    }
  });
}

// Function to update coordinates in the database
function updateCoordinates(lat, lng) {
  // Log the data being sent in the AJAX request for debugging
  console.log("Updating coordinates:", lat, lng);

  // Send AJAX request to update coordinates in the database
  fetch('update_sav_coords.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      lat: lat,
      lng: lng
    }),
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Failed to update coordinates');
    }
    console.log('Coordinates updated successfully:', lat, lng);
    // After successful update, fetch savior coordinates to update the marker position
    fetchSaviorCoords();
  })
  .catch(error => {
    console.error('Error updating coordinates:', error);
    // Revert to original position if there's an error
    fetchSaviorCoords();
  });
}

// Function to fetch marker coordinates for "vash" from the server and display the marker
function fetchVashMarkerCoords() {
  fetch("get_marker_coords.php")
    .then((response) => response.json())
    .then((data) => {
      if (data && data.lat && data.lng) {
        const vashCoords = [parseFloat(data.lat), parseFloat(data.lng)];
        createVashMarker(vashCoords);
      } else {
        console.error("No coordinates found for vash marker");
      }
    })
    .catch((error) => {
      console.error("Failed to fetch vash marker coordinates", error);
    });
}

// Call the function to fetch marker coordinates for "vash" and display the marker
fetchVashMarkerCoords();

// Function to create marker for "vash" on the map
function createVashMarker(coords) {
  var vashIcon = L.icon({
    iconUrl: "vash_mark.png",
    iconSize: [42, 42],
    iconAnchor: [16, 32],
  });

  // Add marker for "vash" to the map with custom icon
  L.marker(coords, { icon: vashIcon }).addTo(map);
}