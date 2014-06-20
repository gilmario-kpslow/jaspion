editaCadastro = (function() {
    $("#form_solicita").attr("action", "/siscredito/cidadao/solicitacao");
    $("#form_solicita").submit();
});

confirmaCadastro = (function() {
    $("#form_solicita").attr("action", "/siscredito/cidadao/adiciona");
    $("#form_solicita").submit();
});