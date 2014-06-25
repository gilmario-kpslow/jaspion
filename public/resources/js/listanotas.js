
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
        var max = parseInt(resultado / 10);
        criarPaginador(resultado);
        $("#max").val(max);
    });
});

montarTabela = (function(dados) {
    $("#resultado").html("");
    $.each(dados, function(i, obj) {
        $("#resultado").append("<tr class='" + (i % 2 === 0 ? 'warning' : '') + "'><td>" + formataCNPJ(obj.CNPJ) + "</td><td>" + obj.RAZAOSOCIAL + "</td><td>" + obj.NUMERO + "</td><td>" + obj.SERIE + "</td><td class='text-right'>" + formataDinheiro(obj.VALOR_ISS) + "</td><td class='text-right'>" + formataDinheiro(obj.VALOR_CREDITO) + "</span></td><td class='text-center'>" + (obj.PAGO === 'N' ? 'Indisponível' : obj.CREDITADO === 'N' ? 'Disponível' : 'Utilizado') + "</td></tr>");
    });

});

paginar = (function(sel, paginar) {
    if ($(sel).attr("class") != 'active') {
        corrigePaginacao();
        $(sel).addClass("active");
        $("#paginacao").val(paginar);
        $.ajax({
            url: "/siscredito/nota/consulta",
            type: 'POST',
            data: jQuery("#form_1").serialize(),
            global: false
        }).success(function(dados) {
            montarTabela(JSON.parse(dados));
        });
    }
});

proximo = (function() {
    var pagina = $("#paginacao").val();
    var max = $("#max").val();
    if (pagina < max) {
        pagina++;
        var sel = $("#pag" + pagina);
        paginar(sel, pagina);
    }
});
anterior = (function() {
    var min = $("#min").val();
    var pagina = $("#paginacao").val();
    if (pagina > min) {
        pagina--;

        var sel = $("#pag" + pagina);
        paginar(sel, pagina);
    }
});

criarPaginador = (function(max) {
    $("#pagina").html("");
    $("#pagina").append("<li onclick='anterior();' ><a href='#'>&laquo;</a></li>");
    for (var i = 0; i < (max / 10); i++) {
        if (i === 0) {
            $("#pagina").append("<li class='active' onclick='paginar(this," + i + ")' id='pag" + (i) + "'><a href='#' >" + (i + 1) + "</a></li>");
        } else {
            $("#pagina").append("<li onclick='paginar(this," + i + ")' id='pag" + (i) + "'><a href='#' >" + (i + 1) + "</a></li>");
        }
    }
    $("#pagina").append("<li onclick='proximo();'><a href='#'>&raquo;</a></li>");
});

corrigePaginacao = (function() {
    $('#pagina li').removeClass('active');
});

dataDef = (function() {
    var hoje = new Date();
    var mes = hoje.getMonth() + 1;
    var ano = hoje.getFullYear();
    var dia = 30;
    if (mes == 2) {
        if (ano % 4 == 0) {
            dia = 29;
        } else {
            dia = 28;
        }
    } else if (mes == 1 || mes == 3 || mes == 5 || mes == 7 || mes == 8 || mes == 10 || mes == 12) {
        dia = 31;
    }
    if (new String(mes).length < 2) {
        mes = "0" + mes;
    }
    if (new String(dia).length < 2) {
        dia = "0" + dia;
    }
    $("#periodo_inicial").val("01/" + mes + "/" + ano);

    $("#periodo_final").val(dia + "/" + mes + "/" + ano);
});

$(document).ready(function() {
    dataDef();
});