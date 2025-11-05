function recherchduproduit() {
  // Récupérer l'input et la liste déroulante
  var input, filter, ul, li, a, i;
  input = document.getElementById("produitrecher");
  filter = input.value.toUpperCase();
  ul = document.getElementById("nomProduite");
  li = ul.getElementsByTagName("option");
 
  // Boucler sur toutes les options
  for (i = 0; i < li.length; i++) {
    a = li[i];
    if (a.textContent.toUpperCase().indexOf(filter) > -1) {
      li[i].style.display = "";
    } else {
      li[i].style.display = "none";
    }
  }
  
}

function recherchduclient() {
  // Récupérer l'input et la liste déroulante
  var input, filter, ul, li, a, i;
  input = document.getElementById("clientrecher");
  filter = input.value.toUpperCase();
  ul = document.getElementById("clientt");
  li = ul.getElementsByTagName("option");
  
  // Boucler sur toutes les options
  for (i = 0; i < li.length; i++) {
    a = li[i];
    
    if (a.textContent.toUpperCase().indexOf(filter) > -1) {
      li[i].style.display = "";
    } else {
      li[i].style.display = "none";
    }
  }
  
}