function updatePssif(){

    let donnees = document.getElementById("date").innerText;

    fetch('/passif/update',{
        method:'POST',
        headers:{
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(donnees)
    })
    .then(response => response.json())
    .then(data => { 
        console.log(data);
        if (data.success) {
            document.getElementById('message').innerHTML = "<h3 class='text-success'>Votre Passif est à jour</h3>" 
        }
            
    })
    .catch(error => {
        console.error(error);
    });
}

function updatePssifA(){

    let donnees = document.getElementById("date").innerText;

    fetch('/passif/a/update',{
        method:'POST',
        headers:{
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(donnees)
    })
    .then(response => response.json())
    .then(data => { 
        console.log(data);
        if (data.success) {
            document.getElementById('message').innerHTML = "<h3 class='text-success'>Votre Passif est à jour</h3>" 
        }
            
    })
    .catch(error => {
        console.error(error);
    });
}