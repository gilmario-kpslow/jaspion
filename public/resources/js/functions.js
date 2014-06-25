
var tempo = new Date().getTime();
mascaraCpf = (function() {
    $(".cpf_mask").mask("999.999.999-99");
});
mascaraCep = (function() {
    $(".cep_mask").mask("99.999-999");
});
mascaraTelefone = (function() {
    $(".tel_mask").mask("(99) 9999-9999?9");
});
mascaraData = (function() {
    $(".data_mask").mask("99/99/9999");
});

$(document).ready(function() {
    mascaraCpf();
    mascaraCep();
    mascaraTelefone();
    carregando();
    mascaraData();
});

verificaCpf = (function() {
    var cpf = new String($("#cpf").val());
    cpf = cpf.replace(/[^\d]+/g, '');
    if (cpf.length > 0) {
        if (validaCPF(cpf)) {
            $.ajax({
                url: "/siscredito/cidadao/valida",
                type: 'POST',
                data: "cpf=" + cpf,
                global: false
            }).success(function(d) {
                if (d !== "true") {
                    mensagem("Esse CPF já está cadastrado.", 0);
                    $("#cpf").val("");
                    $("#cpf").focus();
                    return false;
                }
            });
        } else {
            mensagem("CPF incorreto ou invalido.", 0);
            $("#cpf").val("");
            $("#cpf").focus();
            return false;
        }
    } else {
        mensagem("CPF vazio ou incorreto", 0);
        $("#cpf").val("");
        return false;
    }
});

validaEmail = (function() {
    var email = $("#email").val();
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if (!emailReg.test(email)) {
        mensagem("Email invalido", 1);
        $("#email").val("");
        $("#email").focus();
        return false;
    } else {
        $.ajax({
            url: "/siscredito/cidadao/validaemail",
            type: 'POST',
            data: "email=" + email,
            global: false
        }).success(function(dados) {
            if (dados !== "true") {
                mensagem("Esse email já está cadastrado.", 0);
                $("#email").val("");
                $("#email").focus();
                return false;
            } else {
                return true;
            }
        });
    }
});

mensagem = (function(mensagem, tipo) {
    switch (tipo) {
        case 0:
            $(".alerta").html("<div class='alert alert-danger alerta'><button type='button' class='close' data-dismiss='alert'>×</button>" + mensagem + "</div>");
//            setTimeout(ocultaMensagem, 3000);
            break;
        case 1:
            $(".alerta").html("<div class='alert alert-warning alerta'><button type='button' class='close' data-dismiss='alert'>×</button>" + mensagem + "</div>");
//            setTimeout(ocultaMensagem, 3000);
            break;
        case 2:
            $(".alerta").html("<div class='alert alert-success alerta'><button type='button' class='close' data-dismiss='alert'>×</button>" + mensagem + "</div>");
//            setTimeout(ocultaMensagem, 3000);
            break;
        default :
            $(".alerta").html("<div class='alert alert-info alerta'><button type='button' class='close' data-dismiss='alert'>×</button>" + mensagem + "</div>");
//            setTimeout(ocultaMensagem, 3000);
    }


});

verificaCep = (function() {
    var cep = new String($("#cep").val());
    cep = cep.replace(/[^\d]+/g, '');
    $("#men_modal").html("Consultando CEP");
    $.ajax({
        url: "/siscredito/cidadao/consultacep",
        type: 'POST',
        data: "cep=" + cep
    }).success(function(dados) {
        if (dados !== "") {
            var e = JSON.parse(dados);
            $("#logradouro").val(e.tipoDeLogradouro + " " + e.logradouro);
            $("#uf").val(e.estado);
            $("#cidade").append("<option value='" + e.cidade.toUpperCase() + "'>" + e.cidade.toUpperCase() + "</option>");
            $("#cidade").val(e.cidade.toUpperCase());
            $("#bairro").val(e.bairro);
            $("#numero").focus();
        } else {
            $("#logradouro").val("");
            $("#uf").val("");
            $("#cidade").val("");
            $("#cidade").val("");
            $("#bairro").val("");
            mensagem("CEP não encontado", 1);
        }
    });
});




carregando = (function() {
    $(document).ajaxStart(function() {
        if (new Date().getTime() - tempo > 900000) {
            window.location.href = "/siscredito/usuario/fimsessao";
        } else {
            tempo = new Date().getTime();
            $("#carregando").modal('show');
        }
    });
    $(document).ajaxStop(function() {
        $("#carregando").modal('hide');
    });
});

validaCPF = (function(cpf) {
//    cpf = cpf.replace(/[^\d]+/g, '');
    if (cpf == '')
        return false;
    // Elimina CPFs invalidos conhecidos
    if (cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" || cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" || cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" || cpf == "88888888888" || cpf == "99999999999") {
        return false;
    }

    var add = 0;
    for (i = 0; i < 9; i++) {
        add += parseInt(cpf.charAt(i)) * (10 - i);
    }
    var rev = 11 - (add % 11);
    if (rev == 10 || rev == 11) {
        rev = 0;
    }
    if (rev != parseInt(cpf.charAt(9))) {
        return false;
    }
    // Valida 2o digito
    add = 0;
    for (i = 0; i < 10; i++) {
        add += parseInt(cpf.charAt(i)) * (11 - i);
    }
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11) {
        rev = 0;
    }
    if (rev != parseInt(cpf.charAt(10))) {
        return false;
    }
    return true;
});

confirmaSenha = (function() {
    var senhaNova = $("#senha_n").val();
    var confirma = $("#senha_c").val()
    if (senhaNova.length < 6) {
        mensagem("Atenção", "As senha devem conter um minimo de 6 caracteres.");
        $("#senha_n").focus();
        return false;
    } else if (senhaNova != confirma) {
        mensagem("Atenção", "As senha não conferem.");
        $("#senha_n").focus();
        return false;
    }
});

carregaUF = (function() {
    $("#uf").html("");
    $("#uf").append("<option value=''>-selecione-</option>");
    $.ajax({
        url: "/siscredito/municipio/uf",
        type: 'POST',
        global: false
    }).success(function(dados) {
        if (dados !== "") {
            var e = JSON.parse(dados);
            for (var u in e) {
                $("#uf").append("<option value='" + e[u].UF + "'>" + e[u].UF + "</option>");
            }
            if ($("#restore_uf").val().length > 0) {
                $("#uf").val($("#restore_uf").val());
                carregaMunicipio();
            }
        }
    });
});


carregaMunicipio = (function() {
    $("#cidade").html("");
    var uf = $("#uf").val();
    $("#cidade").append("<option value=''>-- selecione --</option>");
    $.ajax({
        url: "/siscredito/municipio/municipios",
        type: 'POST',
        data: "UF=" + uf,
        global: false
    }).success(function(dados) {
        if (dados !== "") {
            var e = JSON.parse(dados);
            for (var u in e) {
                $("#cidade").append("<option value='" + e[u].NOME + "'>" + e[u].NOME + "</option>");
            }
            if ($("#restore_cidade").val().length > 0) {
                $("#cidade").val($("#restore_cidade").val());
            }
        }
    });
});

ocultaMensagem = (function() {
    $(".alerta").animate({opacity: '0'}, "slow");
    setTimeout(fimMensagem, 1000);
});

fimMensagem = (function() {
    $(".alerta").html("");
    $(".alerta").animate({opacity: '1'}, "fast");
});

formataData = (function(data) {
    var dia;
    var mes;
    var ano;
    dia = new String(data).substr(8, 2);
    mes = new String(data).substr(5, 2);
    ano = new String(data).substr(0, 4);
    return dia + "/" + mes + "/" + ano;
});

formataDinheiro = (function(din) {
    var tmp = new String(din);
    var arr = tmp.split("\.");
    var cen = arr[1];
    var dec = arr[0];
    var j = "";
    for (var i = 0; i < dec.length; i++) {
        if (i > 0 && i % 3 === 0) {
            j = "." + j;
        }
        j = dec[dec.length - i - 1] + j;
    }
    dec = j;
    return "R$ " + dec + "," + cen;
});

formataCNPJ = (function(cnpj) {
    cnpj = new String(cnpj);
    return cnpj.substring(0, 2) + "." + cnpj.substring(2, 5) + "." + cnpj.substring(5, 8) + "/" + cnpj.substring(8, 12) + "-" + cnpj.substring(12, 14);

});




