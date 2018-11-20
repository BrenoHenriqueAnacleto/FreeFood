$(document).ready(function () {
    
    $(document).on('click', '#exibir_itens', function (event) {

        acoesItem.exibe_oculta_itens();
    });

    var acoesItem = {
        Inicializa: function () {
            
            acoesItem.AdicionarItem();
            acoesItem.RemoverItem();
        },
        AdicionarItem: function () {
            
            if ($('#add_item').length) {
                
                $(document).on('click', '#add_item', function (e) {
                    
                    e.preventDefault();
                    var template = $('.item')[0];

                    template = $(template).clone();

                    var qtd_itens = parseInt($('#qtd_itens').val()) + 1;

                    template.attr('id', 'item' + qtd_itens);

                    template.find(':input').each(function() {

                        var nome      = this.name;
                        var novo_nome = nome.substr(0, 
                                                    (nome.indexOf("[") + 1)) 
                                                            + qtd_itens 
                                                            + nome.substr(nome.indexOf("]"),
                                                    (nome.length - 1));
                        this.name  = novo_nome;
                        this.value = '';
                    });

                    template.appendTo('#itens');
                    $('#qtd_itens').val(qtd_itens);
                    $('.estado', template).trigger('change');

                });
            }
        },
        RemoverItem: function () {
            if ($('.remover_item').length) {
                $(document).on('click', '.remover_item', function () {

                    var qtd = $('.item').length;

                    var item = $(this).closest('.item');

                    if (qtd > 1) {

                        bootbox.confirm('Você realmente deseja remover este item?', 'Não', 'Sim', function (resposta) {

                            if (resposta) {

                                $(item).hide('slow', function () {
                                    $(this).remove();
                                });
                            }
                        });
                    }
                });
            }
        },
        exibe_oculta_itens: function () {

            var icone_link = $('i', $('#exibir_itens'));
            var itens = $('#itens');
            var display = document.getElementById('itens').style.display;

            if (display == 'none') {

                $(itens).show('slow');

                $(icone_link).removeClass('elusive-icon-chevron-down');
                $(icone_link).addClass('elusive-icon-chevron-up');
            } else {

                $(itens).hide('slow');
                $(icone_link).removeClass('elusive-icon-chevron-up');
                $(icone_link).addClass('elusive-icon-chevron-down');
            }
        }
    };

    acoesItem.Inicializa();
});