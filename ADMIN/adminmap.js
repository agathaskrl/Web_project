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
    iconSize: [32, 32],
    iconAnchor: [16, 32],
  });

  $.getJSON("get_veh_data.php", function (data) {
    data.forEach(function (vehicle) {
      var coords = [vehicle.lat, vehicle.lng]; // Use coordinates from data
      L.marker(coords, { icon: vehicon })
        .bindPopup(
          "<b>Username: </b>" +
            vehicle.sav_username +
            "<br><b>Cargo: </b>" +
            vehicle.cargo +
            "<br><b>Tasks: </b>" +
            vehicle.under_tasks
        )
        .addTo(map);
    });
  });
}

//klhsh ths function gia na deixei ta markes oxhmatwn
fetch_vehicle_coords();

//offers & requests :

function fetchOffers() {
  fetch("get_offers.php")
    .then((response) => response.json())
    .then((data) => {
      console.log("Received offers data:", data);

      if (data.length > 0) {
        data.forEach((offer) => {
          console.log("Creating marker for offer:", offer);

          // Extract offer data
          const offer_id = offer.offer_id;
          const name = offer.name;
          const surname = offer.surname;
          const phone = offer.phone;
          const lat = offer.lat;
          const lng = offer.lng;
          const item = offer.item;
          const quantity = offer.quantity;
          const subm_date = offer.subm_date;
          const ret_date = offer.ret_date;
          const usrnm_veh = offer.usrnm_veh;
          const status = offer.status;

          // Check if the offer is taken
          const isTaken = offer.ret_date !== null && offer.usrnm_veh !== null;

          const complete = status === "COMPLETE";

          // Do not add the marker if the request is complete
          if (complete) {
            console.log("Offer is complete", offer_id);
            return;
          }

          // Create a marker with the appropriate icon
          const iconUrl = isTaken ? "offer_yellow.png" : "offer_green.png";
          const marker = L.marker([lat, lng], {
            icon: L.icon({
              iconUrl: iconUrl,
              iconSize: [32, 32],
              iconAnchor: [16, 32],
              popupAnchor: [0, -32],
            }),
          }).addTo(map);

          // Construct the HTML string for the pop-up
          let popupContent = `
              <b>${item}</b>
              <b>Quantity:</b> ${quantity}</p>
              <p><b>Name:</b> ${name}</p>
              <p><b>Surname:</b> ${surname}</p>
              <p><b>Phone:</b> ${phone}</p>
              <p><b>Vehicle:</b> ${usrnm_veh}</p>
              <p><b>Submit Date:</b> ${subm_date}</p>
              <p><b>Undertaken Date:</b> ${ret_date}</p>`;

          marker.bindPopup(popupContent);
        });
      } else {
        console.error("No offers found");
      }
    })
    .catch((error) => {
      console.error("Failed to fetch offers", error);
    });
}

// Call the function to fetch and display offers on the map
fetchOffers();

//requests
// Function to fetch requests from the server and display them on the map
function fetchRequests() {
  fetch("get_requests.php")
    .then((response) => response.json())
    .then((reqdata) => {
      console.log("Received requests data:", reqdata);

      if (reqdata.length > 0) {
        reqdata.forEach((request) => {
          console.log("Creating marker for requests:", request);

          // Extract request data
          const req_id = request.req_id;
          const civ_name = request.civ_name;
          const civ_surname = request.civ_surname;
          const civ_phone = request.civ_phone;
          const lat = request.lat;
          const lng = request.lng;
          const req_product = request.req_product;
          const demand = request.demand;
          const req_date = request.req_date;
          const under_date = request.under_date;
          const veh_username = request.veh_username;
          const status = request.status;

          // Check if the request is taken
          const isTaken =
            request.under_date !== null && request.veh_username !== null;

          const complete = status === "COMPLETE";

          if (complete) {
            console.log("Request is complete", req_id); // Fixed the variable name
            return;
          }

          // Create a marker with the appropriate icon
          const iconUrl = isTaken ? "bell_yellow.png" : "bell_green.png";
          const marker = L.marker([lat, lng], {
            icon: L.icon({
              iconUrl: iconUrl,
              iconSize: [32, 32],
              iconAnchor: [16, 32],
              popupAnchor: [0, -32],
            }),
          }).addTo(map);

          // Construct the HTML string for the pop-up
          let popupContent = `
              <b>${req_product}</b>
            <div class="popup-body">
              <p><b>Demand:</b> ${demand}</p>
              <p><b>Name:</b> ${civ_name}</p>
              <p><b>Surname:</b> ${civ_surname}</p>
              <p><b>Phone:</b> ${civ_phone}</p>
              <p><b>Vehicle:</b> ${veh_username}</p>
              <p><b>Request Date:</b> ${req_date}</p>
              <p><b>Undertaken Date:</b> ${under_date}</p>`;

          marker.bindPopup(popupContent);
        });
      } else {
        console.error("No requests found");
      }
    })
    .catch((error) => {
      console.error("Failed to fetch requests", error);
    });
}

// Call the function to fetch and display requests on the map
fetchRequests();
