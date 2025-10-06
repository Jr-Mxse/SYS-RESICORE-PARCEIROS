<?php

/**
 * <b>Função Datatable:</b>
 * Versão 2017-10-14 20:38
 * Função responsável por gerar o Script do Datatable!
 * @param STRING $tabela = Nome da Tabela
 * @param STRING $api = Endereço da Api
 * @param STRING $order = Ordem à ser Exibido [0, 'asc'],[1, 'asc'] (não adianta configurar no Select)
 * @param STRING $lista = Exibição de Registros, primeiro número é a quantidade inicial chamada. [10,25,50,100]
 * @param STRING $botoes = Botões de Exportação / Impressão
 * @param STRING $columns = Configurações Exrtas ans colunas, exemplo: [{orderable: false,width: '7%'} ... Se a coluna não é ordenável e Largura
 * @param STRING $paginar = Se preenchido qualquer valor, remove paginação.
 * 
 * @copyright (c) 2016, Wellington Junior Mx Soluções Empresariais
 */
function Datatable($tabela, $api, $order = null, $lista = null, $botoes = null, $columns = null, $paginar = null, $pesquisar = null, $footer = null)
{
        if (empty($order)) {
                $order = "[0, 'asc'],[1, 'asc']";
        }
        if (empty($lista)) {
                $lista = "[10,25,50,100]";
        }
        $sp_lista = explode(",", str_replace("[", "", $lista));
        $inicio = $sp_lista[0];
?>
        <script type="text/javascript" language="javascript" class="init">
                $(document).ready(function() {
                        $('#<?= $tabela ?>').dataTable({
                                <?php if (!empty($pesquisar)) { ?> "searching": false,
                                <?php } ?>
                                <?php if (!empty($paginar)) { ?> "paging": false,
                                <?php } ?> "pagingType": "full_numbers",
                                <?php if (!empty($api)) { ?> "processing": true,
                                        "serverSide": true,
                                        "ajax": "<?= $api ?>",
                                <?php } ?> "responsive": true,
                                "stateSave": true,
                                "language": {
                                        "sEmptyTable": "Nenhum registro encontrado",
                                        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                                        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                                        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                                        "sInfoPostFix": "",
                                        "sInfoThousands": ".",
                                        "sLengthMenu": "_MENU_",
                                        "sLoadingRecords": "Carregando...",
                                        "sProcessing": "Processando...",
                                        "sZeroRecords": "Nenhum registro encontrado",
                                        "sSearch": "",
                                        "oPaginate": {
                                                "sNext": "Próximo",
                                                "sPrevious": "Anterior",
                                                "sFirst": "Primeiro",
                                                "sLast": "Último"
                                        },
                                        "oAria": {
                                                "sSortAscending": ": Ordenar colunas de forma ascendente",
                                                "sSortDescending": ": Ordenar colunas de forma descendente"
                                        }
                                },
                                "order": [<?= $order ?>],
                                "lengthMenu": [<?= $lista ?>, <?= $lista ?>],
                                "pageLength": <?= $inicio ?>,
                                <?php if ($botoes) { ?> "dom": '<"top-controls d-flex"B l f>rtip',
                                        "buttons": [
                                                <?php
                                                if (in_array("print", $botoes)) {
                                                ?> {
                                                                extend: 'print',
                                                                text: 'Imprimir',
                                                                exportOptions: {
                                                                        columns: ':visible'
                                                                },
                                                                customize: function(win) {
                                                                        $(win.document.body)
                                                                                .css('font-size', '8pt')
                                                                        $(win.document.body).find('table')
                                                                                .addClass('compact')
                                                                                .css('font-size', 'inherit');
                                                                }
                                                        },
                                                <?php
                                                }
                                                if (in_array("pdf", $botoes)) {
                                                ?> {
                                                                extend: 'pdf',
                                                                exportOptions: {
                                                                        columns: ':visible'
                                                                }
                                                        },
                                                <?php
                                                }
                                                if (in_array("excel", $botoes)) {
                                                ?> {
                                                                extend: 'excel',
                                                                exportOptions: {
                                                                        columns: ':visible'
                                                                }
                                                        },
                                                <?php
                                                }
                                                if (in_array("selecionar", $botoes)) {
                                                ?> {
                                                                extend: 'colvis',
                                                                text: 'Selecionar Colunas',
                                                        }
                                                <?php } ?>
                                        ],
                                <?php
                                }
                                if (!empty($columns)) {
                                ?>

                                        columns: <?= $columns ?>,
                                        fixedColumns: true,
                                        autoWidth: false
                                <?php
                                }
                                if (!empty($footer)) {
                                ?> "footerCallback": function(row, data, start, end, display) {
                                                var api = this.api(),
                                                        data;
                                                var intVal = function(i) {
                                                        return typeof i === 'string' ?
                                                                i.replace(/[\$,]/g, '').replace(/[\$.]/g, '') / 100 :
                                                                typeof i === 'number' ?
                                                                i : 0;
                                                };
                                                <?php
                                                $n = 0;
                                                if (isset($footer[1])) :
                                                        $n = 1;
                                                endif;
                                                ?>

                                                pageTotal<?= $n ?> = api.column(<?= $footer[$n] ?>, {
                                                                page: 'current'
                                                        })
                                                        .data()
                                                        .reduce(function(a, b) {
                                                                return intVal(a) + intVal(b);
                                                        }, 0);
                                                numero<?= $n ?> = pageTotal<?= $n ?>;
                                                numero<?= $n ?> = numero<?= $n ?>.toFixed(2).split('.');
                                                numero<?= $n ?>[0] = numero<?= $n ?>[0].split(/(?=(?:...)*$)/).join('.');
                                                <?php
                                                if (isset($footer[1])) :
                                                        echo "$(api.column(" . ($footer[1] - 1) . ").footer()).html('<div style=\"text-align: left\">Resumo por página</div>');";
                                                        $sp = explode(" ", $footer[0]);
                                                        if (isset($sp[1])) {
                                                                echo "$(api.column(" . ($footer[1] + 1) . ").footer()).html('<div style=\"text-align: left\">{$sp[0]}</div>');";
                                                                echo "$(api.column(" . ($footer[1] + 2) . ").footer()).html('<div style=\"text-align: left\">{$sp[1]}</div>');";
                                                        } else {
                                                                echo "$(api.column(" . ($footer[1] + 1) . ").footer()).html('<div style=\"text-align: left\">{$footer[0]}</div>');";
                                                        }
                                                endif;
                                                ?>
                                                $(api.column(<?= $footer[$n] ?>).footer()).html(numero<?= $n ?>.join(','));
                                        }
                                <?php
                                }
                                ?>
                        });
                });
        </script>
<?php
}


