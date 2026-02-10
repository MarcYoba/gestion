const rechercheproduit = document.getElementById("inventaire_a_inventaire");

rechercheproduit.addEventListener('input',calculeTotal);

function calculeTotal(){
    let quantite = parseInt(document.getElementById("quantite").value) || 0 ;
    let stock = parseInt(document.getElementById("inventaire_a_inventaire").value) || 0 ;
    let contoire = parseInt(document.getElementById("stock").value) || 0 ;
    let vendu = parseInt(document.getElementById("vendu").value) || 0 ;
    
    document.getElementById("inventaire_a_ecart").value = (stock) - (quantite + contoire);    
}

function recherchequantite(){
    
    let data = {};
    const nomproduit  = document.getElementById("inventaire_a_produit").value ;
    const date = document.getElementById("inventaire_a_createtAt").value ;
    // collection de l'id du produit
    data.nom = nomproduit;
    data.date = date;
    //console.log(nomproduit);

    fetch('/produit/a/recherche/quantite',{
        method:'POST',
        headers:{
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => { 
        if (data.success == true) {
            document.getElementById("quantite").value = data.quantite;
            document.getElementById("inventaire_a_quantite").value = data.contoire;
            document.getElementById("vendu").value = data.facturation;
            document.getElementById("stock").value = data.contoire;
            console.log(data);
        }else if(data.success == false){
        }else{
            console.log(data);
        }     
    })
    .catch(error => {
        console.error(error);
    });
       
}

function rechercheInventaire() {
            // Récupérer l'input et la liste déroulante
    var input, filter, ul, li, a, i;
    input = document.getElementById("produitrecher");
    filter = input.value.toUpperCase();
    ul = document.getElementById("inventaire_a_produit");
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