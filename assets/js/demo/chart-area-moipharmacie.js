// Set new default font family and font color to mimic Bootstrap's default styling
var tabMontantMoi = new Array(32);
var tabJourMoi = new Array(32);
var varmois  = 1;

const  inputMars = document.getElementById("Mars");
//const  nombre = document.getElementById("nombre");
function funMars() {
  
  RechargeGraphe();
}

inputMars.addEventListener('input',funMars);
//nombre.addEventListener('input',funMars);

function RechargeGraphe() {
  console.log("Valeur du moi");
  varmois = document.getElementById("nombre").value;
  console.log(varmois);
  for (let index = 0; index < tabMontantMoi.length; index++) {
    tabMontantMoi[index] = 0; 
    tabJourMoi[index] = 0;  
  }

  fetch('phpphamacie/graphe/getdatamoi.php',{
      method:'POST',
        headers:{
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(varmois)
  })
    .then(response => {
      return response.json();
    })
    .then(dat => {
      console.log(dat); 
      // Parcours des données et affichage
      let ind = 0;
      dat.forEach(element => {
        for(let proriete in element){
           // console.log(element[proriete]); 
            result = element[proriete];
            tabMontantMoi[ind] = result.prix;
            tabJourMoi[ind] = result.jour;

            ind +=1  
        }
      });
     creationGrapeh();
    })
    .catch(error => {
      console.error(error);
  });

//Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
//Chart.defaults.global.defaultFontColor = '#858796'; 
}
for (let index = 0; index < tabMontantMoi.length; index++) {
    tabMontantMoi[index] = 0; 
    tabJourMoi[index] = 0;  
}

$(document).ready(function() {

  fetch('phpphamacie/graphe/getdatamoi.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      console.log(data); 
      // Parcours des données et affichage
      index = 0;
      data.forEach(element => {
        for(let proriete in element){
           // console.log(element[proriete]); 
            result = element[proriete];
            tabMontantMoi[index] = result.prix;
            tabJourMoi[index] = result.jour;

            index +=1  
        }
      });
     creationGrapeh();
    })
    .catch(error => {
      console.error('There has been a problem with your fetch operation:', error);
    });
});


Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

function creationGrapeh(){
  // Area Chart Example
  var ctx = document.getElementById("myAreaChart");
  var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: tabJourMoi,
      datasets: [{
        label: "Vente",
        lineTension: 0.3,
        backgroundColor: "rgba(78, 115, 223, 0.05)",
        borderColor: "rgba(78, 115, 223, 1)",
        pointRadius: 3,
        pointBackgroundColor: "rgba(78, 115, 223, 1)",
        pointBorderColor: "rgba(78, 115, 223, 1)",
        pointHoverRadius: 3,
        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
        pointHitRadius: 10,
        pointBorderWidth: 2,
        data: tabMontantMoi,
      }],
    },
    options: {
      maintainAspectRatio: false,
      layout: {
        padding: {
          left: 10,
          right: 25,
          top: 25,
          bottom: 0
        }
      },
      scales: {
        xAxes: [{
          time: {
            unit: 'date'
          },
          gridLines: {
            display: false,
            drawBorder: false
          },
          ticks: {
            maxTicksLimit: 32
          }
        }],
        yAxes: [{
          ticks: {
            maxTicksLimit:10 ,
            padding: 10,
            // Include a dollar sign in the ticks
            callback: function(value, index, values) {
              return 'CFA' + number_format(value);
            }
          },
          gridLines: {
            color: "rgb(234, 236, 244)",
            zeroLineColor: "rgb(234, 236, 244)",
            drawBorder: false,
            borderDash: [2],
            zeroLineBorderDash: [2]
          }
        }],
      },
      legend: {
        display: false
      },
      tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        titleMarginBottom: 10,
        titleFontColor: '#6e707e',
        titleFontSize: 14,
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        intersect: false,
        mode: 'index',
        caretPadding: 10,
        callbacks: {
          label: function(tooltipItem, chart) {
            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
            return datasetLabel + ' ' + number_format(tooltipItem.yLabel) + ' FCFA';
          }
        }
      }
    }
  });
}


