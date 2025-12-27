const brouillon = JSON.parse(localStorage.getItem("myBrouillon"));

function calculeprixTotalquantitetotal(){
    const tableau = document.getElementById('dataTable');

    quantiteTotal = 0;
    prixtotal = 0;
    for (let index = 2; index < tableau.rows.length; index++) {
        const cellule4 = tableau.rows[index].cells[2];
        const cellule5 = tableau.rows[index].cells[4];

        quantiteTotal += parseFloat(cellule4.textContent);
        prixtotal += parseFloat(cellule5.textContent); 
    }
}
function getVenteData(idvente){
  fetch('/brouillon/vente', {
      method: 'POST',
      body: JSON.stringify(idvente),
      headers: {
        'Content-Type': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      value = {};
        console.log(data);
        localStorage.setItem("myBrouillon", JSON.stringify(data.message));
        window.location.href = '/vente/create';
        document.getElementById("brouillon").style.display="none";
    })
    .catch(error => {
      console.error('Erreur lors de la requête :', error);
    });
};

function editevente(){
    if (typeof brouillon === 'undefined' || brouillon === null) {
        console.log("La variable est undefined ou null");
    }else{
        brouillon.forEach(element => {
            LigneventeMofier(element);
        });
        localStorage.removeItem('myBrouillon');
        document.getElementById("brouillon").style.display="none";
        document.getElementById("modifiervente").style.display="none";
    }
}
editevente();

function LigneventeMofier(donnees){
    const tableau = document.getElementById('dataTable');

        const nbligne = tableau.rows.length;
        //creer une nouvelle ligne
       const nouvelleLigne = tableau.insertRow();
       
        const nouvellecellule = nouvelleLigne.insertCell();
        const input = document.createElement('p');
        input.innerHTML = donnees.client ;
        input.classList.add('form-control', 'form-control-user');
        nouvellecellule.appendChild(input);
    
        console.log(donnees);
        const nouvellecellule2 = nouvelleLigne.insertCell();
        const p2 = document.createElement('p');
        p2.innerHTML = donnees.produit;
        p2.classList.add('form-control', 'form-control-user');
        nouvellecellule2.appendChild(p2);
    
        const nouvellecellule3 = nouvelleLigne.insertCell();
        const p3 = document.createElement('p');
        p3.innerHTML = donnees.quantite;
        p3.classList.add('form-control', 'form-control-user');
        nouvellecellule3.appendChild(p3);
    
        const nouvellecellule4 = nouvelleLigne.insertCell();
        const p4 = document.createElement('p');
        p4.innerHTML = donnees.prix;
        p4.classList.add('form-control', 'form-control-user');
        nouvellecellule4.appendChild(p4);
    
        const nouvellecellule5 = nouvelleLigne.insertCell();
        const p5 = document.createElement('p');
        p5.innerHTML = (donnees.montant);
        p5.classList.add('form-control', 'form-control-user');
        nouvellecellule5.appendChild(p5);
        
        const nouvellecellule6 = nouvelleLigne.insertCell();
        const p6 = document.createElement('p');
        p6.id = (nbligne +1);
       // p6.innerHTML ='<a class="btn btn-primary" onclick="getLigne(dataTable,'+(nbligne +1)+')"><i class="fas fa-pencil-alt"></i></a>  ' + (nbligne +1);
       // p6.classList.add('form-control', 'form-control-user');
        nouvellecellule6.appendChild(p6);
        
        quantiteTotal =0;//+= donnees.quantite;
        prixtotal =0;//+= donnees.prix;

         calculeprixTotalquantitetotal();
         document.getElementById("quantitetotal").innerHTML = donnees.quantiteTotal;
         document.getElementById("prixtotal").textContent = donnees.prixtotal; 
         document.getElementById("Total").value = donnees.prixtotal;
         document.getElementById("enregistremet").innerHTML = '<button  class="btn btn-primary btn-user btn-block" onclick="enregistrementDonnees('+'dataTable'+')" id="enregistrer">Enregistrer vente</button>';   
}
