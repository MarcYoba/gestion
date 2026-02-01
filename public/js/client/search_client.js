function recherduclient() {
  // Récupérer l'input et la liste déroulante
  var input, filter, ul, li, a, i;
  input = document.getElementById("recherche");
  filter = input.value.toUpperCase();
  ul = document.getElementById("ABCOMPTA_client");
  li = ul.getElementsByTagName("option");

  // Boucler sur toutes les options
  for (i = 0; i < li.length; i++) {
    a = li[i];
    if (a.value.toUpperCase().indexOf(filter) > -1) {
      li[i].style.display = "";
    } else {
      li[i].style.display = "none";
    }
  }
  
}

function recherduclientVersement() {
  // Récupérer l'input et la liste déroulante
  var input, filter, ul, li, a, i;
  input = document.getElementById("rechecliet");
  filter = input.value.toUpperCase();
  ul = document.getElementById("versement_a_client");
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

function recherduclientVaccin() {
  // Récupérer l'input et la liste déroulante
  var input, filter, ul, li, a, i;
  input = document.getElementById("recherche");
  filter = input.value.toUpperCase();
  ul = document.getElementById("vaccin_client");
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

function recherduclientVersementb() {
  // Récupérer l'input et la liste déroulante
  var input, filter, ul, li, a, i;
  input = document.getElementById("recherche");
  filter = input.value.toUpperCase();
  ul = document.getElementById("versement_clients");
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