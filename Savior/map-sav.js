document.addEventListener("DOMContentLoaded", function () {
  const dropdownButton = document.getElementById("dropdownButton");
  const dropdownContent = document.getElementById("dropdownContent");
  const filterButton = document.getElementById("filterButton");
  const showLinesCheckbox = document.getElementById("showLines");

  dropdownButton.addEventListener("click", function (event) {
    event.stopPropagation();
    dropdownContent.classList.toggle("show");
  });

  dropdownContent.addEventListener("click", function (event) {
    event.stopPropagation();
  });

  window.addEventListener("click", function () {
    if (dropdownContent.classList.contains("show")) {
      dropdownContent.classList.remove("show");
    }
  });

  filterButton.addEventListener("click", function () {
    console.log("Filter button clicked");
    applyFilters();
    dropdownContent.classList.remove("show");
  });

  let drawnLines = [];

  function applyFilters() {
    const showLines = showLinesCheckbox.checked;

    console.log("Show lines checkbox checked:", showLines);

    // Fetch or remove lines based on filter value
    if (showLines) {
      fetchUndertakenCoords();
    } else {
      removeDrawnLines(); // Remove drawn lines when showLines is false
    }

    // Show/hide markers based on the filter values
    filterMarkers();
  }

  function fetchUndertakenCoords() {
    console.log("Fetching undertaken coordinates");
    fetch("get_undertaken_coords.php")
      .then((response) => response.json())
      .then((data) => {
        if (data && data.length > 0) {
          console.log("Undertaken coordinates fetched:", data);
          removeDrawnLines(); // Remove existing lines before adding new ones
          data.forEach((undertaken) => {
            const offerCoords = [undertaken.offer_lat, undertaken.offer_lng];
            const vehicleCoords = [
              undertaken.vehicle_lat,
              undertaken.vehicle_lng,
            ];
            const polyline = L.polyline([offerCoords, vehicleCoords], {
              color: "red",
            }).addTo(map);
            drawnLines.push(polyline); // Store the drawn line
            console.log(
              "Line drawn between:",
              offerCoords,
              "and",
              vehicleCoords
            );
          });
          console.log("Total drawn lines:", drawnLines.length);
        } else {
          console.error("No ongoing offers found");
        }
      })
      .catch((error) => {
        console.error("Failed to fetch ongoing offers", error);
      });
  }

  function removeDrawnLines() {
    console.log("Removing drawn lines:", drawnLines.length);
    drawnLines.forEach((line) => {
      map.removeLayer(line);
      console.log("Line removed:", line);
    });

    drawnLines = []; // Clear the array of drawn lines
    console.log("Total lines after removal:", drawnLines.length);
  }

  function filterMarkers() {
    const showOpenOffers = document.getElementById("showOpenOffers").checked;
    const showTakenOffers = document.getElementById("showTakenOffers").checked;
    const showOpenRequests =
      document.getElementById("showOpenRequests").checked;
    const showUndertakenRequests = document.getElementById(
      "showUndertakenRequests"
    ).checked;
    const showLines = showLinesCheckbox.checked;

    console.log("Filters applied:", {
      showOpenOffers,
      showTakenOffers,
      showOpenRequests,
      showUndertakenRequests,
      showLines,
    });

    // Show/hide markers based on the filter values
    filterOfferMarkers(showOpenOffers, showTakenOffers);
    filterRequestMarkers(showOpenRequests, showUndertakenRequests);
  }

  function filterOfferMarkers(showOpen, showTaken) {
    console.log("Filtering offer markers");
    offerMarkers.forEach((marker) => {
      const isTaken =
        marker.options.icon.options.iconUrl.includes("offer_yellow.png");
      const shouldShow = (showOpen && !isTaken) || (showTaken && isTaken);
      console.log(
        `Offer marker at ${marker.getLatLng()} is ${
          isTaken ? "taken" : "open"
        }. Should show: ${shouldShow}`
      );
      if (shouldShow) {
        if (!map.hasLayer(marker)) {
          map.addLayer(marker);
          console.log(`Added offer marker at ${marker.getLatLng()}`);
        }
      } else {
        if (map.hasLayer(marker)) {
          map.removeLayer(marker);
          console.log(`Removed offer marker at ${marker.getLatLng()}`);
        }
      }
    });
  }

  function filterRequestMarkers(showOpen, showUndertaken) {
    console.log("Filtering request markers");

    requestMarkers.forEach((marker) => {
      const isTaken =
        marker.options.icon.options.iconUrl.includes("bell_yellow.png");
      const shouldShow = (showOpen && !isTaken) || (showUndertaken && isTaken);

      console.log(
        `Request marker at ${marker.getLatLng()} is ${
          isTaken ? "undertaken" : "open"
        }. Should show: ${shouldShow}`
      );

      if (shouldShow) {
        if (!map.hasLayer(marker)) {
          map.addLayer(marker);
          console.log(`Added request marker at ${marker.getLatLng()}`);
        }
      } else {
        if (map.hasLayer(marker)) {
          map.removeLayer(marker);
          console.log(`Removed request marker at ${marker.getLatLng()}`);
        }
      }
    });
  }
});
//arxikopoihsi xarti
const map = L.map("map").setView([39.192232, 24.242514], 5);

L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
  maxZoom: 19,
  attribution:
    '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
}).addTo(map);

let saviorMarker;
let vash_marker;
let offerMarkers = [];
let requestMarkers = [];

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

fetchSaviorCoords();

function createSaviorMarker(coords) {
  if (saviorMarker) {
    map.removeLayer(saviorMarker);
  }

  // Fetch items for the savior from the server
  fetch("get_items.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to fetch savior items: " + response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      const items = data.items;

      let itemList = "";
      items.forEach((item) => {
        itemList += `${item}<br>`;
      });
      const popupContent = `<b>Items:</b><br>${itemList}`;

      var vehicleIcon = L.icon({
        iconUrl: "vehicle.png",
        iconSize: [42, 42],
        iconAnchor: [21, 21],
      });

      saviorMarker = L.marker(coords, {
        icon: vehicleIcon,
        draggable: true,
      }).addTo(map);

      saviorMarker.on("click", function () {
        saviorMarker.bindPopup(popupContent).openPopup();
      });

      // Check if the vehicle is within 100 meters of the base
      checkdistance(coords);

      // Add drag event listener to savior marker
      saviorMarker.on("dragend", function (event) {
        const marker = event.target;
        const position = marker.getLatLng();

        // Ask the user if he is sure
        const isSure = window.confirm(
          "Are you sure you want to update the coordinates in the database?"
        );

        if (isSure) {
          // Update the coordinates in the database
          updateCoordinates(position.lat, position.lng);
        } else {
          // If user cancels stay in same position
          marker.setLatLng(coords);
        }
      });
    })
    .catch((error) => {
      console.error("Failed to fetch savior items", error);
    });
}

// Function to create marker for Vash coordinates
function createVashMarker(coords) {
  var vashIcon = L.icon({
    iconUrl: "vash_mark.png",
    iconSize: [42, 42],
    iconAnchor: [16, 32],
  });

  vash_marker = L.marker(coords, { icon: vashIcon }).addTo(map);
}

// Function to update coordinates in the database
function updateCoordinates(lat, lng) {
  // Log the data being sent in the AJAX request for debugging
  console.log("Updating coordinates:", lat, lng);

  // Send AJAX request to update coordinates in the database
  fetch("update_sav_coords.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      lat: lat,
      lng: lng,
    }),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to update coordinates");
      }
      console.log("Coordinates updated successfully:", lat, lng);
      // After successful update, fetch savior coordinates to update the marker position
      fetchSaviorCoords();
    })
    .catch((error) => {
      console.error("Error updating coordinates:", error);
      // Revert to original position if there's an error
      fetchSaviorCoords();
    });
}

// Function to fetch marker coordinates for base from the server and display the marker
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

fetchVashMarkerCoords();

function fetchvashitems() {
  fetch("get_base_items.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      console.log("Received items data:", data);

      let itemList = "";
      if (data.items && data.items.length > 0) {
        data.items.forEach((item) => {
          const item_name = item.name;
          const item_quan = item.quantity;
          // items with checkbox and quantity input
          itemList += `
            <div>
              <input type="checkbox" id="test" name="${item_name}" value="${item_name}" data-quantity="${item_quan}">
              <label for="${item_name}">${item_name} (${item_quan})</label>
              <input type="number" id="${item_name}_quantity" name="${item_name}_quantity" class="quantity-input" min="1" max="${item_quan}" value="0">
            </div>
          `;
        });

        const popupContent = `
          <div style="max-height: 200px; overflow-y: auto;">
            <b>Base Items:</b>
            ${itemList}
            <br><button class="take-on-btn">Take On</button>
            <button class="take-out-btn">Take Out</button>
          </div>
        `;

        vash_marker.bindPopup(popupContent).openPopup();

        // Event listener for the take-on button
        const takeOnBtn = document.querySelector(".take-on-btn");
        takeOnBtn.addEventListener("click", function () {
          const checkedItems = document.querySelectorAll(
            "input[id=test]:checked"
          );

          const itemstotake = Array.from(checkedItems).map((item) => {
            const quantityInput = document.getElementById(
              `${item.value}_quantity`
            );
            return {
              name: item.value,
              quantity: parseInt(quantityInput.value),
            };
          });

          fetch("get_items_on_vehicle.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({ items: itemstotake }),
          })
            .then((response) => {
              if (!response.ok) {
                throw new Error("Network response was not ok");
              }
              return response.json();
            })
            .then((data) => {
              console.log("Response from PHP:", data);
              location.reload();
            })
            .catch((error) => {
              console.error("Error executing action:", error);
            });
        });

        // Event listener for the "Take Out" button
        const takeOutBtn = document.querySelector(".take-out-btn");
        takeOutBtn.addEventListener("click", function () {
          const isSure = window.confirm(
            "Are you sure you want to move the items into the base?"
          );
          if (isSure) {
            fetch("get_items_from_vehicle.php")
              .then((response) => {
                if (!response.ok) {
                  throw new Error("Network response was not ok");
                }
                return response.json();
              })
              .then((data) => {
                if (!data.items) {
                  throw new Error("Items data is undefined");
                }

                const itemsToTakeOut = data.items;

                return fetch("update_products_and_clear_vehicle.php", {
                  method: "POST",
                  headers: {
                    "Content-Type": "application/json",
                  },
                  body: JSON.stringify({ items: itemsToTakeOut }),
                });
              })
              .then((response) => {
                if (!response.ok) {
                  throw new Error("Network response was not ok");
                }
                return response.json();
              })
              .then((data) => {
                console.log("Response from PHP:", data);
                alert("Items successfully moved into the base.");
                location.reload();
              })
              .catch((error) => {
                console.error("Error executing action:", error);
                alert(
                  "Failed to move items into the base. Please try again later."
                );
              });
          }
        });
      }
    });
}
// Function to check the distance between two sets of coordinates
function checkdistance(veh_coords) {
  if (vash_marker) {
    const vashCoords = vash_marker.getLatLng();
    const distance = vashCoords.distanceTo(veh_coords);
    if (distance <= 100) {
      //call the function if the vehicle is 100 meters or so away
      vash_marker.on("click", function () {
        fetchvashitems();
      });
    }
  }
}
// Function to fetch offers from the server and display them on the map
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
          const usrnm_veh = offer.usrnm_veh;
          const status = offer.status;

          // Check if the offer is taken
          const isTaken = offer.ret_date !== null && offer.usrnm_veh !== null;

          const complete = status === "COMPLETE";

          // Do not add the marker if the offer is complete
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

          // Add marker to the offerMarkers array
          offerMarkers.push(marker);

          // Construct the HTML string for the pop-up
          let popupContent = `<b>${item}</b><br>Quantity: ${quantity}<br>Name: ${name}<br>Surname: ${surname}<br>Phone: ${phone}<br>Vehicle: ${usrnm_veh}`;
          if (!isTaken) {
            // For offers that are still open, add the "Take On" button
            popupContent += `<br><button class="take-on-btn" data-offer-id="${offer_id}">Take On</button>`;
          }

          marker.bindPopup(popupContent);

          marker.on("popupopen", function () {
            if (!isTaken) {
              // Wait for the popup to open fully
              setTimeout(() => {
                const takeOnBtn = document.querySelector(
                  `.leaflet-popup-content-wrapper .take-on-btn[data-offer-id='${offer_id}']`
                );
                if (takeOnBtn) {
                  takeOnBtn.addEventListener("click", function () {
                    // Ask the user for confirmation
                    const isSure = window.confirm(
                      "Are you sure you want to take on this offer?"
                    );

                    if (isSure) {
                      // Send AJAX request to take on the offer
                      fetch("takeonoffer.php", {
                        method: "POST",
                        headers: {
                          "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                          offerId: offer_id,
                        }),
                      })
                        .then((response) => response.json())
                        .then((data) => {
                          if (data.error) {
                            alert(data.error);
                          } else {
                            alert(data.message);
                            window.location.reload();
                          }
                        })
                        .catch((error) => {
                          console.error("Error taking on offer:", error);
                        });
                    }
                  });
                }
              }, 100);
            }
          });
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

// Function to fetch requests from the server and display them on the map
function fetchRequests() {
  fetch("get_requests.php")
    .then((response) => response.json())
    .then((reqdata) => {
      console.log("Received requests data:", reqdata);

      if (reqdata.length > 0) {
        reqdata.forEach((request) => {
          console.log("Creating marker for request:", request);

          // Extract request data
          const req_id = request.req_id;
          const civ_name = request.civ_name;
          const civ_surname = request.civ_surname;
          const civ_phone = request.civ_phone;
          const lat = request.lat;
          const lng = request.lng;
          const req_product = request.req_product;
          const demand = request.demand;
          const veh_username = request.veh_username;
          const status = request.status;

          // Check if the request is taken
          const isTaken =
            request.under_date !== null && request.veh_username !== null;

          const complete = status === "COMPLETE";

          // Do not add the marker if the request is complete
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

          // Add marker to the requestMarkers array
          requestMarkers.push(marker);

          // Construct the HTML string for the pop-up
          let popupContent = `<b>${req_product}</b><br>Demand: ${demand}<br>Name: ${civ_name}<br>Surname: ${civ_surname}<br>Phone: ${civ_phone}<br>Vehicle: ${veh_username}`;
          if (!isTaken) {
            // For requests that are still open, add the "Take On" button
            popupContent += `<br><button class="take-on-btn" data-req-id="${req_id}">Take On</button>`;
          }

          marker.bindPopup(popupContent);

          marker.on("popupopen", function () {
            if (!isTaken) {
              // Wait for the popup to open fully
              setTimeout(() => {
                const takeOnBtn = document.querySelector(
                  `.leaflet-popup-content-wrapper .take-on-btn[data-req-id='${req_id}']`
                );
                if (takeOnBtn) {
                  takeOnBtn.addEventListener("click", function () {
                    // Ask the user for confirmation
                    const isSure = window.confirm(
                      "Are you sure you want to take on this request?"
                    );

                    if (isSure) {
                      // Send AJAX request to take on the request
                      fetch("takeonrequest.php", {
                        method: "POST",
                        headers: {
                          "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                          req_Id: req_id,
                        }),
                      })
                        .then((response) => response.json())
                        .then((data) => {
                          if (data.error) {
                            alert(data.error);
                          } else {
                            alert(data.message);
                            window.location.reload();
                          }
                        })
                        .catch((error) => {
                          console.error("Error taking on request:", error);
                        });
                    }
                  });
                }
              }, 100);
            }
          });
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
