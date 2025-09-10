
function UpdateActif(){

    let donnees = document.getElementById("date").innerText;

    fetch('/actif/update',{
        method:'POST',
        headers:{
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(donnees)
    })
    .then(response => response.json())
    .then(data => { 
        console.log(data);     
    })
    .catch(error => {
        console.error(error);
    });
}

function UpdateActif_a(){

    let donnees = document.getElementById("date").innerText;

    fetch('/actif/a/update',{
        method:'POST',
        headers:{
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(donnees)
    })
    .then(response => response.json())
    .then(data => { 
        console.log(data);     
    })
    .catch(error => {
        console.error(error);
    });
}

function AnneBilan() {
    let date = document.getElementById("annee").value;
    window.location.href = '/actif/list/' +  date;
}

function AnneBilan_a() {
    let date = document.getElementById("annee").value;
    window.location.href = '/actif/list/' +  date;
}