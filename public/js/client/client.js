function add_client() {
    var nom = document.getElementById("nom").value;
    var telephone = document.getElementById("telephone").value;

    let tab = {};
    tab.nom = nom;
    tab.telephone = telephone;

    fetch('/clients/add/client', {
        method: 'POST',
        body: JSON.stringify(tab),
        headers: {
            'Content-Type': 'application/json'// Important pour Symfony
        }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
                    return response.json();
        })
        .then(data => {
            if (data.success) {
                console.log(data);
                document.getElementById("nom").value ="";
                document.getElementById("telephone").value="";
                document.getElementById("message").innerHTML=' <div class="alert alert-success" role="alert">Information du client enregistrer avec success</div>';
                window.location.reload();
            } else {
               console.log(data);
               document.getElementById("message").innerHTML=' <div class="alert alert-danger" role="alert">Information du client non enregistrer</div>';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}

function myFunctionP() {
  // Récupérer l'input et la liste déroulante
  var input, filter, ul, li, a, i;
  input = document.getElementById("recherche");
  filter = input.value.toUpperCase();
  ul = document.getElementById("poussin_client");
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