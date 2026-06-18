const inputCedula = document.querySelectorAll("#inputCedula")

inputCedula.forEach(input => {
    input.addEventListener('input', function() {
        
        this.value = this.value.replace(/\D/g, '');
        
        if (this.value.length > 8) {
            this.value = this.value.slice(0, 8);
        }
    });
});


if (inputCedula) {
    inputCedula.forEach(inputCedula => {
        inputCedula.addEventListener("input", (event) => {
            const cedulaValue = event.target.value
            if (cedulaValue.length < 7 || cedulaValue.length > 8) {
                inputCedula.style.border = "2px red solid"
            } else {
                inputCedula.style.border = "2px green solid"
            }
        })
    });    
}