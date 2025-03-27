function calculerTotal(ligneIndex){
            const quantite = document.getElementById(`cellule_dataTable_${ligneIndex * 3}`).textContent;
            const prix = document.getElementById(`cellule_dataTable_${ligneIndex * 3 + 1}`).textContent;
            const Totalcellule = document.getElementById(`cellule_dataTable_${ligneIndex * 3 + 2}`);

            const total = quantite * prix;

            Totalcellule.textContent = total;
        }
        
const  inputQuantite = document.getElementById("quantite");
const  inputPrix = document.getElementById("prixglobal");
var quantiteTotal = 0;
var prixtotal = 0;

function calculeprixTotalquantitetotal(){
    const tableau = document.getElementById('dataTable');
  
    for (let index = 2; index < tableau.rows.length; index++) {

        
        const cellule4 = tableau.rows[index].cells[2];
        const cellule5 = tableau.rows[index].cells[4];

        quantiteTotal += parseFloat(cellule4.textContent);
        prixtotal += parseFloat(cellule5.textContent);
        
    }
}

function calculeTotal(){
    
    document.getElementById("resultat").textContent = document.getElementById("quantite").value * document.getElementById("prixglobal").value;

    quantiteTotal = 0;
    prixtotal = 0;
    calculeprixTotalquantitetotal();
    document.getElementById("quantitetotal").innerHTML = quantiteTotal + parseFloat(document.getElementById("quantite").value);
    document.getElementById("prixtotal").textContent = prixtotal + parseFloat(document.getElementById("quantite").value * document.getElementById("prixglobal").value);
    document.getElementById("verificatiobDonne").innerHTML ='';
}
inputQuantite.addEventListener('input',calculeTotal);

inputPrix.addEventListener('input',calculeTotal)


function ajouterLigne(dataTable,...donnees){

    const  inputFournisseur = document.getElementById("fournisseur").value;
    const  inputDescrition = document.getElementById("nomProduit").value;
    const  inputQuantite = document.getElementById("quantite").value;
    const  inputPrix = document.getElementById("prixglobal").value;
    
    if (inputFournisseur !="" && inputDescrition !="" && inputQuantite !=0 && inputPrix !=0) {
        const tableau = document.getElementById(dataTable);
        document.getElementById("verificatiobDonne").innerHTML ='';
        //creer une nouvelle ligne
       const nouvelleLigne = tableau.insertRow();
       
        const nouvellecellule = nouvelleLigne.insertCell();
        const input = document.createElement('p');
        input.innerHTML = inputFournisseur;
        input.classList.add('form-control', 'form-control-user');
        nouvellecellule.appendChild(input);
    
        
        const nouvellecellule2 = nouvelleLigne.insertCell();
        const p2 = document.createElement('p');
        p2.innerHTML = inputDescrition;
        p2.classList.add('form-control', 'form-control-user');
        nouvellecellule2.appendChild(p2);
    
        const nouvellecellule3 = nouvelleLigne.insertCell();
        const p3 = document.createElement('p');
        p3.innerHTML = inputQuantite;
        p3.classList.add('form-control', 'form-control-user');
        nouvellecellule3.appendChild(p3);
    
        const nouvellecellule4 = nouvelleLigne.insertCell();
        const p4 = document.createElement('p');
        p4.innerHTML = inputPrix;
        p4.classList.add('form-control', 'form-control-user');
        nouvellecellule4.appendChild(p4);
    
        const nouvellecellule5 = nouvelleLigne.insertCell();
        const p5 = document.createElement('p');
        p5.innerHTML = (inputQuantite * inputPrix);
        p5.classList.add('form-control', 'form-control-user');
        nouvellecellule5.appendChild(p5);
    
        quantiteTotal = 0;
        prixtotal = 0;
        calculeprixTotalquantitetotal();
        document.getElementById("quantitetotal").innerHTML = quantiteTotal;
        document.getElementById("prixtotal").textContent = prixtotal;
        document.getElementById("quantite").value='';
        document.getElementById("prixglobal").value='';
       // document.getElementById("prixtotal").textContent = '';
        document.getElementById("fournisseur").value='';
        document.getElementById("nomProduit").value='';  
    }
    else{
        document.getElementById("verificatiobDonne").innerHTML = '<p class="bg-danger"> verifiez les donne enregistrer </p>';
    }
    
    
}



function enregistrementDonnees(){
    const tableau = document.getElementById('dataTable');
    const datevar = document.getElementById('datefacture').value;
    console.log(datevar);

    let donnees = [];
    let data = {};
    if (tableau.rows.length >=3) {
        for (let index = 2; index < tableau.rows.length; index++) {

            const cellule1 = tableau.rows[index].cells[0];
            const cellule2 = tableau.rows[index].cells[1];
            const cellule3 = tableau.rows[index].cells[2];
            const cellule4 = tableau.rows[index].cells[3];
            const cellule5 = tableau.rows[index].cells[4];
    
            data.fournisseur = cellule1.textContent;
            data.produit = cellule2.textContent;
            data.quantite = cellule3.textContent;
            data.prix = cellule4.textContent;
            data.total = cellule5.textContent;
            data.datevalue = datevar;

            //console.log(data);
            donnees.push({...data});  //on peut aussi  declarer directement let data = {} dans la boucle pour redure le programme
            data.value++;
            //donnees.unshift(data);
            
        }
        //console.log(donnees);
        
        fetch('/achat/create',{
            method:'POST',
            headers:{
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(donnees)
        })
        .then(response => response.json())
        .then(data => { 
            if (data.success == true) {
                document.getElementById("verificatiobDonne").innerHTML = '<p class="bg-info"> enregistrement des donne avec success </p>';
                window.location.href = "/achat/list"
            }else if(data.errors){
                document.getElementById("verificatiobDonne").innerHTML = '<p class="bg-danger"> echec enregistrer </p>';
            }
            else{
                console.log(data);
            }
            
        })
        .catch(error => {
            console.error(error);
        });
        
      
    } else {
        document.getElementById("verificatiobDonne").innerHTML = '<p class="bg-warning"> ajouter une ligne pour vendre </p>'; 
    }
    
}

        
        
function calculerTotal(ligneIndex){
    const quantite = document.getElementById(`cellule_dataTable_${ligneIndex * 3}`).textContent;
    const prix = document.getElementById(`cellule_dataTable_${ligneIndex * 3 + 1}`).textContent;
    const Totalcellule = document.getElementById(`cellule_dataTable_${ligneIndex * 3 + 2}`);

    const total = quantite * prix;

    Totalcellule.textContent = total;
}

function recherproduit() {
    // Récupérer l'input et la liste déroulante
    var input, filter, ul, li, a, i;
    input = document.getElementById("produitname");
    filter = input.value.toUpperCase();
    ul = document.getElementById("nomProduit");
    li = ul.getElementsByTagName("option");
    console.log(li.length);
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

function recherfourniseur() {
    // Récupérer l'input et la liste déroulante
    var input, filter, ul, li, a, i;
    input = document.getElementById("fourni");
    filter = input.value.toUpperCase();
    ul = document.getElementById("fournisseur");
    li = ul.getElementsByTagName("option");
    console.log(li.length);
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