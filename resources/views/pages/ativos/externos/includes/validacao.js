// carrega imagem

function carregarImg() {

    var target = document.getElementById('target');
    var file = document.querySelector("input[type=file]").files[0];
    var reader = new FileReader();

    reader.onloadend = function() {
        target.src = reader.result;
    };

    if (file) {
        reader.readAsDataURL(file);


    } else {
        target.src = "";
    }
}

//Validar se o equipamento Calibrado
$(document).ready(function() {

    $('#calibracao').change(function() {
        var selectValue = $(this).val(); // Obtém o valor selecionado

        // Verifica o valor selecionado = Sim
        if (selectValue == "Sim") {
            document.getElementById("div_calibracao").style.display = 'flex';
            document.getElementById("menssagem_alert").style.display = ' flex'

            var valorQtde = $('#quantidade').val();

            if (selectValue == "Sim" && valorQtde > 1) {

                alert("ATENÇÃO!!! Só é pemitido cadastro único para equipamento calibraveis");

                document.getElementById("quantidade").value = 1;

                var x = document.getElementById("quantidade").value = 1;

                console.log(x)
                alert
            }

        } else {
            document.getElementById("div_calibracao").style.display = 'none';
            document.getElementById("menssagem_alert").style.display = ' none'
        }
    });

});

function valorQuantidade(inputElement) {

    // Obtenha o valor do input quantidade
    var valorQtde = inputElement.value;

    if ($('#calibracao').val() == "Sim" && valorQtde > 1) {

        alert("deu ruimm");

        inputElement.value = 1;
    }

}