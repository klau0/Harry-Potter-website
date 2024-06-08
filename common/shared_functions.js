function dinamicNumberInput(input){
    if (isNaN(input.value) || isNaN(parseFloat(input.value))){
        input.value = input.value.substring(0, input.value.length-1);
    }

    let uj_meret = input.value.length * 10;

    if (uj_meret > 0){
        input.style.width = uj_meret + "px";
    }

    if (input.style.width.substring(0, input.style.width.length - 2) > 100){
        input.style.width = "100px";
    }
}