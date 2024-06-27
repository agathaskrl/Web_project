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

    // Fetch i afairei tis grammes me vasi to filtro
    if (showLines) {
      fetchUndertakenCoords();
    } else {
      removeDrawnLines(); // afairei tis grammes otan to showLines einai false
    }

    // emfanise/krypse ta markers me vasi to filtro
    filterMarkers();
  }

  function fetchUndertakenCoords() {
    console.log("Fetching undertaken coordinates");
    fetch("get_undertaken_coords.php")
      .then((response) => response.json())
      .then((data) => {
        if (data && data.length > 0) {
          console.log("Undertaken coordinates fetched:", data);
          removeDrawnLines(); // afairei tis uparxouses grammes prin valei nees 
          data.forEach((undertaken) => {
            const offerCoords = [undertaken.offer_lat, undertaken.offer_lng];
            const vehicleCoords = [
              undertaken.vehicle_lat,
              undertaken.vehicle_lng,
            ];
            const polyline = L.polyline([offerCoords, vehicleCoords], {
              color: "red",
            }).addTo(map);
            drawnLines.push(polyline); // apothikeysi twn grammwn drawn line
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

    drawnLines = []; //adeiasma tou pinaka drawn lines
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

    // emfanise/krypse ta markers me vasi to filtro
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

// Function na pairnei syntetagmenes tou savior apo ti vasi kai emfanisi marker sto xarti 
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

  // Fetch ta proionta gia ton savior apo ti vash
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

      // Check an to oxima vehicle einai se aktina 100 metra apo ti vash 
      checkdistance(coords);

      // savior marker draggable 
      saviorMarker.on("dragend", function (event) {
        const marker = event.target;
        const position = marker.getLatLng();

        // epivevaiwsi allagis topothesias
        const isSure = window.confirm(
          "Are you sure you want to update the coordinates in the database?"
        );

        if (isSure) {
          // Update tis syntetagmenes sti vash
          updateCoordinates(position.lat, position.lng);
        } else {
          // an patisei akyrwsi paramenei stin arxiki tou thesi
          marker.setLatLng(coords);
        }
      });
    })
    .catch((error) => {
      console.error("Failed to fetch savior items", error);
    });
}

// Function gia dimiourgia marker tis vashs me syntetagmenes
function createVashMarker(coords) {
  var vashIcon = L.icon({
    iconUrl: "vash_mark.png",
    iconSize: [42, 42],
    iconAnchor: [16, 32],
  });

  vash_marker = L.marker(coords, { icon: vashIcon }).addTo(map);
}

// Function gia update twn syntetagmenwn sti vash
function updateCoordinates(lat, lng) {
  // elegxos an stelnontai ta dedomena tou AJAX request for debugging
  console.log("Updating coordinates:", lat, lng);

  // apostoli AJAX request gia na ginei update stis syntetagmenes sti vash
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
      // afou ginoun update, fetch syntetagmenes tou savior gia na enimerwthi i thesi tou marker 
      fetchSaviorCoords();
    })
    .catch((error) => {
      console.error("Error updating coordinates:", error);
      // epistrofi stin arxiki thesi an uparxei error
      fetchSaviorCoords();
    });
}

// Function  na ferei tis syntetagmenes tis vasis apo ti vasi kai emfanisi marker
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

        // Event listener gia to take-on button
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

        // Event listener gia to "Take Out" button
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
// Function gia elegxo apostasis metaksi syntetagmenwn 
function checkdistance(veh_coords) {
  if (vash_marker) {
    const vashCoords = vash_marker.getLatLng();
    const distance = vashCoords.distanceTo(veh_coords);
    if (distance <= 100) {
      //to function kaleitai an to vehicle einai se akrina 100 metrwn 
      vash_marker.on("click", function () {
        fetchvashitems();
      });
    }
  }
}
// Function gia na ferei ta offers apo ti vasi kai emfanisi twn markers sto xarti 
function fetchOffers() {
  fetch("get_offers.php")
    .then((response) => response.json())
    .then((data) => {
      console.log("Received offers data:", data);

      if (data.length > 0) {
        data.forEach((offer) => {
          console.log("Creating marker for offer:", offer);

          // offer data
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

          // elegxos an to offer is taken
          const isTaken = offer.ret_date !== null && offer.usrnm_veh !== null;

          const complete = status === "COMPLETE";

          // den emfanizetai to marker sto xarti an einai completed 
          if (complete) {
            console.log("Offer is complete", offer_id);
            return;
          }

          // dimiourgia marker me to katallilo icon 
          const iconUrl = isTaken ? "offer_yellow.png" : "offer_green.png";
          const marker = L.marker([lat, lng], {
            icon: L.icon({
              iconUrl: iconUrl,
              iconSize: [32, 32],
              iconAnchor: [16, 32],
              popupAnchor: [0, -32],
            }),
          }).addTo(map);

          // prosthiki marker ston pinaka offerMarkers
          offerMarkers.push(marker);

          //  HTML domi gia to pop up
          let popupContent = `<b>${item}</b><br>Quantity: ${quantity}<br>Name: ${name}<br>Surname: ${surname}<br>Phone: ${phone}<br>Vehicle: ${usrnm_veh}`;
          if (!isTaken) {
            // gia offers pou den exoun analifthei, emfanisi "Take On" button
            popupContent += `<br><button class="take-on-btn" data-offer-id="${offer_id}">Take On</button>`;
          }

          marker.bindPopup(popupContent);

          marker.on("popupopen", function () {
            if (!isTaken) {
              // Wait na fortwsei to popup
              setTimeout(() => {
                const takeOnBtn = document.querySelector(
                  `.leaflet-popup-content-wrapper .take-on-btn[data-offer-id='${offer_id}']`
                );
                if (takeOnBtn) {
                  takeOnBtn.addEventListener("click", function () {
                    // epivevaiwsi apo to xristi gia analipsi
                    const isSure = window.confirm(
                      "Are you sure you want to take on this offer?"
                    );

                    if (isSure) {
                      // apostoli AJAX request gia analipsi tou offer 
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

// klisi tou function gia na kanei fetch kai na emfanisei ta offers sto xarti
fetchOffers();

// Function gia na kanei fetch ta requests apo ti vasi kai na ta emfanisei sto xarti
function fetchRequests() {
  fetch("get_requests.php")
    .then((response) => response.json())
    .then((reqdata) => {
      console.log("Received requests data:", reqdata);

      if (reqdata.length > 0) {
        reqdata.forEach((request) => {
          console.log("Creating marker for request:", request);

          // request data
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

          // elegxos an to request exei analifthei
          const isTaken =
            request.under_date !== null && request.veh_username !== null;

          const complete = status === "COMPLETE";

          // den emfanizetai to marker sto xarti an einai completed
          if (complete) {
            console.log("Request is complete", req_id); // Fixed the variable name
            return;
          }

          // dimiourgia marker me to katallilo icon
          const iconUrl = isTaken ? "bell_yellow.png" : "bell_green.png";
          const marker = L.marker([lat, lng], {
            icon: L.icon({
              iconUrl: iconUrl,
              iconSize: [32, 32],
              iconAnchor: [16, 32],
              popupAnchor: [0, -32],
            }),
          }).addTo(map);

          //prosthiki marker ston pinaka requestMarkers
          requestMarkers.push(marker);

          //HTML domi gia to pop-up
          let popupContent = `<b>${req_product}</b><br>Demand: ${demand}<br>Name: ${civ_name}<br>Surname: ${civ_surname}<br>Phone: ${civ_phone}<br>Vehicle: ${veh_username}`;
          if (!isTaken) {
            // gia requests pou den exoun analifthei, emfanisi tou "Take On" button
            popupContent += `<br><button class="take-on-btn" data-req-id="${req_id}">Take On</button>`;
          }

          marker.bindPopup(popupContent);

          marker.on("popupopen", function () {
            if (!isTaken) {
              // Wait na fortwsei to popup
              setTimeout(() => {
                const takeOnBtn = document.querySelector(
                  `.leaflet-popup-content-wrapper .take-on-btn[data-req-id='${req_id}']`
                );
                if (takeOnBtn) {
                  takeOnBtn.addEventListener("click", function () {
                    // epivevaiwsi apo xristi
                    const isSure = window.confirm(
                      "Are you sure you want to take on this request?"
                    );

                    if (isSure) {
                      // apostoli AJAX request gia na analifthei to request
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

//klisi tou function gia na kanei fetch kai na emfanisei ta requests sto xarti 
fetchRequests();
