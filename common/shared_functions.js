function dinamicNumberInput(input){
    if (isNaN(input.value) || isNaN(parseFloat(input.value))){
        input.value = input.value.substring(0, input.value.length-1);
    }

    if(input.value.length > 0){
        input.size = input.value.length;
    }

    if (input.size > 10){
        input.size = 10;
    }
}