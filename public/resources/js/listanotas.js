
listarNotas = (function() {
    $.ajax({
        url: "/siscredito/nota/consulta",
        type: 'POST',
        data: jQuery("#form_1").serialize()
    }).success(function(dados) {
        total();
        montarTabela(JSON.parse(dados));
    });
});

total = (function() {
    $.ajax({
        url: "/siscredito/nota/totalnotas",
        type: 'POST',
        data: jQuery("#form_1").serialize()
    }).success(function(resultado) {
        var max = resultado;
        criarPaginador(max);
    });
});

montarTabela = (function(dados) {
    $("#resultado").html("");
    $.each(dados, function(i, obj) {
        $("#resultado").append("<tr><td>" + formataCNPJ(obj.CNPJ) + "</td><td>" + formataData(obj.DATA_EMISSAO) + "</td><td>" + obj.NUMERO + "</td><td>" + obj.SERIE + "</td><td class='text-right'>" + formataDinheiro(obj.VALOR_ISS) + "</td><td class='text-right'>" + formataDinheiro(obj.VALOR_CREDITO) + "</span></td><td class='text-center'>" + obj.CREDITADO + "</td><td class='text-center'>" + obj.PAGO + "</td></tr>");
    });

});

paginar = (function(sel, paginar) {
    corrigePaginacao();
    $(sel).addClass("active");
    $("#paginacao").val(paginar);
    $.ajax({
        url: "/siscredito/nota/consulta",
        type: 'POST',
        data: jQuery("#form_1").serialize()
    }).success(function(dados) {

        montarTabela(JSON.parse(dados));
    });
});

criarPaginador = (function(max) {
    $("#pagina").html("");
    for (var i = 0; i < (max / 10); i++) {
        if (i === 0) {
            $("#pagina").append("<li class='active' onclick='paginar(this," + i + ")'><a href='#' >" + (i + 1) + "</a></li>");
        } else {
            $("#pagina").append("<li onclick='paginar(this," + i + ")'><a href='#' >" + (i + 1) + "</a></li>");
        }
    }
});

corrigePaginacao = (function() {
    $('#pagina li').removeClass('active');
});

dataDef = (function() {
    var hoje = new Date();
    var dia = hoje.getDate();
    var mes = hoje.getMonth() + 1;
    var ano = hoje.getFullYear();
    if (new String(mes).length < 2) {
        mes = "0" + mes;
    }
    if (new String(dia).length < 2) {
        dia = "0" + dia;
    }
    $("#periodo_inicial").val(dia + "/" + mes + "/" + ano);
    $("#periodo_final").val(dia + "/" + mes + "/" + ano);
});

$(document).ready(function() {
    dataDef();
});