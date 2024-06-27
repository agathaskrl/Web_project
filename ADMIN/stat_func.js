const ctx = document.getElementById("statistics").getContext("2d");
let statistics;

const colors = [
  "rgba(75, 192, 192, 0.2)",
  "rgba(255, 99, 132, 0.2)",
  "rgba(255, 206, 86, 0.2)",
  "rgba(153, 102, 255, 0.2)",
];

function fetchData(timePeriod) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: "fetch_data_offer_requests.php",
      method: "GET",
      data: { timePeriod: timePeriod },
      dataType: "json",
      success: function (response) {
        resolve(response);
      },
      error: function (xhr, status, error) {
        reject(error);
      },
    });
  });
}

function createChart(data) {
  if (!data || !data.datasets || data.datasets.length === 0) {
    console.error("Invalid or empty data provided:", data);
    return;
  }

  //Orise ton pianka label se keno gia etiketes
  const labels = data.labels || [];

  if (statistics) {
    statistics.destroy();
  }

  statistics = new Chart(ctx, {
    type: "bar",
    data: {
      labels: labels,
      datasets: data.datasets.map((dataset, index) => ({
        ...dataset,
        backgroundColor: colors[index % colors.length],
        borderColor: colors[index % colors.length].replace("0.2", "1"),
        borderWidth: 1,
      })),
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
}
//Ananewnei to chart analoga me to epilgemeno xroniko diasthma
function updateChart() {
  const timePeriod = document.getElementById("time-period").value;
  fetchData(timePeriod)
    .then((data) => {
      createChart(data);
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
}

document.addEventListener("DOMContentLoaded", () => {
  updateChart();
});
