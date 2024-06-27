document.addEventListener("DOMContentLoaded", function () {
  // arxikopoihsh xarth
  const map = L.map("map").setView([39.192232, 24.242514], 5);

  L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
  }).addTo(map);

  let markcoords = [0, 0];
  let vehicleMarkers = [];
  let offerMarkers = [];
  let requestMarkers = [];

  // fernei coordinates apo th vash
  function fetch_current_coords() {
    fetch("get_marker_coords.php")
      .then((response) => response.json())
      .then((data) => {
        if (data && data.lat && data.lng) {
          markcoords = [parseFloat(data.lat), parseFloat(data.lng)];
          createmark(markcoords);
        }
      })
      .catch((error) => {
        console.error("Failed to fetch marker coordinates", error);
      });
  }
//sunartisi gia na ginei dragable o marker
  function createmark(coords) {
    const vashicon = L.icon({
      iconUrl: "vash_mark.png",
      iconSize: [42, 42],
      iconAnchor: [16, 32],
    });

    const marker_vash = L.marker(coords, {
      draggable: true,
      icon: vashicon,
    }).addTo(map);

    marker_vash.on("dragend", function (event) {
      const marker_vash = event.target;
      const position = marker_vash.getLatLng();
//check an einai sigouros o xristis gia metakinisi vashs
      const isSure = window.confirm("Are you sure you want to move the base?");
      if (!isSure) {
        //an akurwsi paramenei stin arxiki thesi
        marker_vash.setLatLng(markcoords);
      } else {
        //apothikeuei nees suntetagmenes
        markcoords = [position.lat, position.lng];
        savemarkerdata(markcoords[0], markcoords[1]);
      }
    });
  }
//fernei ta dedomena tou marker vash
  fetch_current_coords();

  //function gia apothikeysh toy marker_vash dedoemnwn ston server
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
          console.log("Marker data saved successfully");
        } else {
          console.error("Failed to save marker data");
        }
      })
      .catch((error) => {
        console.error("Error saving marker data", error);
      });
  }

  //function gia na brei ta coords twn vehicles
  function fetch_vehicle_coords() {
    fetch("get_veh_coords.php")
      .then((response) => response.json())
      .then((data) => {
        if (data && data.length > 0) {
          data.forEach((vehicle) => {
            create_vehicle_marker([parseFloat(vehicle.lat), parseFloat(vehicle.lng)], vehicle);
          });
        } else {
          console.error("No vehicle marker coordinates found");
        }
      })
      .catch((error) => {
        console.error("Failed to fetch vehicle marker coordinates", error);
      });
  }

//Function gia na ftiaxnei vehicle markers
  function create_vehicle_marker(coords, vehicle) {
    const vehicon = L.icon({
      iconUrl: "vehicle.png",
      iconSize: [32, 32],
      iconAnchor: [16, 32],
    });

    $.getJSON("get_veh_data.php", function (data) {
      data.forEach(function (vehicle) {
        var coords = [vehicle.lat, vehicle.lng]; // Use coordinates from data
        const marker = L.marker(coords, { icon: vehicon, data: { ...vehicle } })
          .bindPopup(
            "<b>Username: </b>" +
              vehicle.sav_username +
              "<br><b>Cargo: </b>" +
              vehicle.cargo +
              "<br><b>Tasks: </b>" +
              vehicle.under_tasks
          )
          .addTo(map);
        vehicleMarkers.push(marker);
      });
    });
  }
  console.log(vehicleMarkers);

  fetch_vehicle_coords();


  // Fetch dedomenwn kai dimiourgia offer markers
  function fetchOffers() {
    fetch("get_offers.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.length > 0) {
          data.forEach((offer) => {
            if (offer.status === "COMPLETE") {
              return;
            }

            const iconUrl =
              offer.ret_date !== null && offer.usrnm_veh !== null
                ? "offer_yellow.png"
                : "offer_green.png";
            const marker = L.marker([offer.lat, offer.lng], {
              icon: L.icon({
                iconUrl: iconUrl,
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32],
              }),
            }).addTo(map);

            const popupContent = `
              <b>${offer.item}</b>
              <p><b>Quantity:</b> ${offer.quantity}</p>
              <p><b>Name:</b> ${offer.name}</p>
              <p><b>Surname:</b> ${offer.surname}</p>
              <p><b>Phone:</b> ${offer.phone}</p>
              <p><b>Vehicle:</b> ${offer.usrnm_veh}</p>
              <p><b>Submit Date:</b> ${offer.subm_date}</p>
              <p><b>Undertaken Date:</b> ${offer.ret_date}</p>`;

            marker.bindPopup(popupContent);
            offerMarkers.push(marker);
          });
        } else {
          console.error("No offers found");
        }
      })
      .catch((error) => {
        console.error("Failed to fetch offers", error);
      });
  }

  // Fetch dedokmenwn kai dimiourgia request markers
  function fetchRequests() {
    fetch("get_requests.php")
      .then((response) => response.json())
      .then((reqdata) => {
        if (reqdata.length > 0) {
          reqdata.forEach((request) => {
            if (request.status === "COMPLETE") {
              return;
            }

            const iconUrl =
              request.under_date !== null && request.veh_username !== null
                ? "bell_yellow.png"
                : "bell_green.png";
            const marker = L.marker([request.lat, request.lng], {
              icon: L.icon({
                iconUrl: iconUrl,
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32],
              }),
            }).addTo(map);

            const popupContent = `
              <b>${request.req_product}</b>
              <p><b>Demand:</b> ${request.demand}</p>
              <p><b>Name:</b> ${request.civ_name}</p>
              <p><b>Surname:</b> ${request.civ_surname}</p>
              <p><b>Phone:</b> ${request.civ_phone}</p>
              <p><b>Vehicle:</b> ${request.veh_username}</p>
              <p><b>Request Date:</b> ${request.req_date}</p>
              <p><b>Undertaken Date:</b> ${request.under_date}</p>`;
              marker.bindPopup(popupContent);
              requestMarkers.push(marker);
            });
          } else {
            console.error("No requests found");
          }
        })
        .catch((error) => {
          console.error("Failed to fetch requests", error);
        });
    }
  
    fetchOffers();
    fetchRequests();
  
    // Fetch coords twn task undertaken kai dhmiourgia grammwn anamesa se ayta ta dyo
function fetch_undertaken_coords() {
  fetch("get_undertaken_coords.php")
    .then((response) => response.json())
    .then((data) => {
      if (data && data.length > 0) {
        data.forEach((undertaken) => {
          const offerCoords = [undertaken.offer_lat, undertaken.offer_lng];
          const vehicleCoords = [undertaken.vehicle_lat, undertaken.vehicle_lng];

          // Dhmiourgia grammhs gia vehicles kai tasks
          const polyline = L.polyline([offerCoords, vehicleCoords], { color: 'red' }).addTo(map);
        });
      } else {
        console.error("No ongoing offers found");
      }
    })
    .catch((error) => {
      console.error("Failed to fetch ongoing offers", error);
    });
}

fetch_undertaken_coords();
  
    //Gia tis allages twn filtrwn
    document
      .getElementById("showOpenOffers")
      .addEventListener("change", function () {
        filterMarkers(offerMarkers, this.checked, "offer_green.png");
      });
  
    document
      .getElementById("showTakenOffers")
      .addEventListener("change", function () {
        filterMarkers(offerMarkers, this.checked, "offer_yellow.png");
      });
  
    document
      .getElementById("showOpenRequests")
      .addEventListener("change", function () {
        filterMarkers(requestMarkers, this.checked, "bell_green.png");
      });
  
    document
      .getElementById("showUndertakenRequests")
      .addEventListener("change", function () {
        filterMarkers(requestMarkers, this.checked, "bell_yellow.png");
      });
  
    document
      .getElementById("showAvailableVehicles")
      .addEventListener("change", function () {
        filterVehicleMarkers(vehicleMarkers, this.checked, "vehicle.png", "av");
      });
  
    document
      .getElementById("showOccupiedVehicles")
      .addEventListener("change", function () {
        filterVehicleMarkers(vehicleMarkers, this.checked, "vehicle.png", "occ");
      });
  
    // Function twn filtrwn  
    function filterMarkers(markers, show, iconUrl) {
      markers
        .filter((marker) => marker.options.icon.options.iconUrl === iconUrl)
        .forEach((marker) => {
          if (show) {
            marker.addTo(map);
          } else {
            map.removeLayer(marker);
          }
        });
    }
  
    function filterVehicleMarkers(markers, show, iconUrl, name) {
      if (name === "occ") {
        markers
          .filter((mark) => mark.options.data.under_tasks != 0)
          .forEach((marker) => {
            const vehicle = marker.options.data;
            if (vehicle && marker.options.icon.options.iconUrl === iconUrl) {
              console.log("Vehicle:", vehicle);
              if (show) {
                marker.addTo(map);
              } else {
                map.removeLayer(marker);
              }
            }
          });
      }
      if (name === "av") {
        markers
          .filter((mark) => mark.options.data.under_tasks == 0)
          .forEach((marker) => {
            const vehicle = marker.options.data;
            if (vehicle && marker.options.icon.options.iconUrl === iconUrl) {
              console.log("Vehicle:", vehicle);
              if (show) {
                marker.addTo(map);
              } else {
                map.removeLayer(marker);
              }
            }
          });
      }
    }
  
    //event listener gia to filter map button
    const dropdownButton = document.getElementById("dropdownButton");
    const dropdownContent = document.getElementById("dropdownContent");
  
    dropdownButton.addEventListener("click", function (event) {
      dropdownContent.classList.toggle("show");
      event.stopPropagation(); // Prevent the click event from propagating to the window
    });
  
    // event listener gia to apply filter button
    const applyFilterButton = document.getElementById("applyFilterButton");
    applyFilterButton.addEventListener("click", function (event) {
      // Diaxeirish ths epiloghs twn filtrwn
      console.log("Apply filter button clicked");
      offerMarkers.forEach((marker) => marker.removeFrom(map));
      requestMarkers.forEach((marker) => marker.removeFrom(map));
      filterMarkers(offerMarkers, document.getElementById("showOpenOffers").checked, "offer_green.png");
      filterMarkers(offerMarkers, document.getElementById("showTakenOffers").checked, "offer_yellow.png");
      filterMarkers(requestMarkers, document.getElementById("showOpenRequests").checked, "bell_green.png");
      filterMarkers(requestMarkers, document.getElementById("showUndertakenRequests").checked, "bell_yellow.png");
      filterVehicleMarkers(vehicleMarkers, document.getElementById("showAvailableVehicles").checked, "vehicle.png", "av");
      filterVehicleMarkers(vehicleMarkers, document.getElementById("showOccupiedVehicles").checked, "vehicle.png", "occ");
      event.stopPropagation(); // Prevent the click event from propagating to the window
    });
  
    // kleisimo tou dropdown an o xristis clicks ejw apo ayto
    window.addEventListener("click", function (event) {
      if (!event.target.matches("#dropdownButton")) {
        if (dropdownContent.classList.contains("show")) {
          dropdownContent.classList.remove("show");
        }
      }
    });
  
    // Apotroph diadwshs deodmenwn me to click 
    dropdownContent.addEventListener("click", function (event) {
      event.stopPropagation();
    });
  });
  
           
