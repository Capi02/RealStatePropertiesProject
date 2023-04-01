function insertCheckOutData(){
 
        const form = new FormData(this);
    
        fetch("../data/checkOutConfirmation.php",{
            method: "POST",
            body: form
        })
            .then( respond => respond.json())
            .then( data => {
                if( data === "true"){
                    document.querySelector("#username").value = "";
                    document.querySelector("#cellphone").value = "";
                    document.querySelector("#email").value = "";
                    alert("La compra se realizo correctamente!");
                    console.log(formulario);
                    console.log(data);
                    console.log(respond);
                }
            })
    
}
