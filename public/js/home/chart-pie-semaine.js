// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.font.family = 'Nunito, -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
// Correction pour Chart.js v3+
Chart.defaults.color = '#858796';
var taball = [5, 15, 65,15];
$(document).ready(function() {
  fetch('/caisse/etat')
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      // Parcours des données et affichag
      // for (let proriete in data) {
      //   value = data[proriete];
      //   index = 0;
      //   for (let  key in value) { 
      //       taball[index] = value[key];
      //     //console.log(tab1);
      //     index +=1;
      //   }
      //  }
      CreationGraphe();
    })
    .catch(error => {
      console.error('There has been a problem with your fetch operation:', error);
    });
});
// Pie Chart Example

function CreationGraphe(){
  var ctx = document.getElementById("myPieChart");
  var myPieChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ["Achat", "Vente", "Dette","versement"],
      datasets: [{
        data: taball,
        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc','#D4AF37'],
        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
        hoverBorderColor: "rgba(234, 236, 244, 1)",
      }],
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
      },
      legend: {
        display: false
      },
      cutoutPercentage: 80,
    },
  });
}
