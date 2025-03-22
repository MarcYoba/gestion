// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

var tabQTm = new Array(32);
var tabDatem = new Array(32);
var varmoi  = 1;
const  inputQuantite = document.getElementById("Quantite");
//const  inputnombre = document.getElementById("nombre");

function funQuantite() {
  
  RechargeGrapheQuantite();
}

inputQuantite.addEventListener('input',funQuantite);
//inputnombre.addEventListener('input',funQuantite);

function EtudeEvolutive() {
  console.log("Evolution : ");
  let evolution= document.getElementById("nombre3").value;
  
  fetch('phpphamacie/graphe/getevolution.php',{
      method:'POST',
        headers:{
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(evolution)
  })
    .then(response => {
      return response.json();
    })
    .then(data => {
      console.log(data); 
      
      document.getElementById("montant1").innerText = data.montantN
      document.getElementById("client1").innerText = data.ClientN
      document.getElementById("montant2").innerText = data.montantN1
      document.getElementById("client2").innerText = data.ClientN1
      document.getElementById("Total").innerText = Math.round((data.montantN1 - data.montantN)/data.montantN);
      document.getElementById("Poucentage").innerText = (Math.round((data.montantN1 - data.montantN)/data.montantN))*100;
      document.getElementById("Totalclient").innerText = Math.round((data.ClientN1 - data.ClientN)/data.ClientN);
      document.getElementById("Poucentageclient").innerText = (Math.round((data.ClientN1 - data.ClientN)/data.ClientN))*100;
      
    })
    .catch(error => {
      console.error(error);
  });

//Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
//Chart.defaults.global.defaultFontColor = '#858796'; 
}


function RechargeGrapheQuantite() {
  console.log("QUANTITE du moi");
  varmoi = document.getElementById("nombre2").value;
  console.log(varmoi);
  for (let index = 0; index < tabQTm.length; index++) {
    tabQTm[index] = 0; 
    tabDatem[index] = 0;  
  }

  fetch('phpphamacie/graphe/getquantitemoi.php',{
      method:'POST',
        headers:{
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(varmoi)
  })
    .then(response => {
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
            tabQTm[index] = result.quantite;
            tabDatem[index] = result.jour;
            //console.log(result.quantite); 
            //console.log(result.jour); 
              index +=1  
          }
        });
        creationBard();
    })
    .catch(error => {
      console.error(error);
  });

//Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
//Chart.defaults.global.defaultFontColor = '#858796'; 
}
for (let index = 0; index < tabDatem.length; index++) {
  tabDatem[index] = 0;
  tabQTm[index] = 0;
}
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

$(function() {
  fetch('phpphamacie/graphe/getquantitemoi.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => { 
        index = 0;
        data.forEach(element => {
          for(let proriete in element){
           // console.log(element[proriete]); 
            result = element[proriete];
            tabQTm[index] = result.quantite;
            tabDatem[index] = result.jour;
            //console.log(result.quantite); 
            //console.log(result.jour); 
              index +=1  
          }
        });
        creationBard();
    })
    .catch(error => {
      console.error('There has been a problem with your fetch operation:', error);
    });
});
//console.log("data");
// Bar Chart Example
function creationBard(){
    var ctx = document.getElementById("myBarChart");
var myBarChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: tabDatem,
    datasets: [{
      label: "Quantite",
      backgroundColor: "#4e73df",
      hoverBackgroundColor: "#2e59d9",
      borderColor: "#4e73df",
      data: tabQTm,
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
          unit: 'month'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: tabQTm.length
        },
        maxBarThickness: 25,
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: 200,
          maxTicksLimit: 5,
          padding: 10,
          // Include a dollar sign in the ticks
          callback: function(value, index, values) {
            return   number_format(value) + 'KG';
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
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ' ' + number_format(tooltipItem.yLabel) + ' KG';
        }
      }
    },
  }
});

}
