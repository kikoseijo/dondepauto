/**
 * Created by Desarrollador 1 on 15/04/2016.
 */

var AdvertiserService = function() {

    var table;
    var urlSearch;
    var actualAdvertiser;

    function initTable(urlSearch) {
        urlSearch = urlSearch;

        table = $('#advertisers-datatable').DataTable({
            "order": [[1, "desc"]],
            "ajax": urlSearch,
            "pageLength": 50,
            "deferRender": true,
            "processing": true,
            "serverSide": true,
            "columns": [
                { "data": null, "orderable": false },
                { "data": "created_at", name:"created_at"},
                { "data": "company" },
                { "data": "city_name" },
                { "data": "name" }, // 4
                { "data": "email" }, // 5
                { "data": "phone"}, // 6
                { "data": "cel"}, // 7
                { "data": "state" }, // 8
                { "data": "count_by_contact_intentions" }, // 9
                { "data": "count_logs" , "name": "count_logs" }, // 19
                { "data": "count_views" , "name": "count_views" }, // 11
                { "data": "contacts" }, // 12

                { "data": "state_id", "name": "state_id", "searchable": false, "visible": false }, // 13
                { "data": "city_id", "name": "city_id", "searchable": false, "visible": false }, // 14
                { "data": "address"}, // 15
                { "data": "economic_activity_id", "name": "economic_activity_id", "searchable": false, "visible": false },
                { "data": "created_at_datatable", "name": "intention_at", "searchable": false, "visible": false},
                { "data": "last_log_login_at_datatable"},
                { "data": "activated_at_datatable"}, // 19

                { "data": null, "name": "action"},
                { "data": null, "name": "action_range"}, // 21
                { "data": "tag_id", "name": "tag_id"} // 22
            ],
            "columnDefs": [
                {
                    "targets": [0,1,2,4,6,7,8,9,10,11,12],
                    "searchable": false
                },
                {
                    "targets": [3,5,13,14,15,16,17,18,19,20,21,22],
                    "visible": false,
                    "searchable": false
                },
                {
                    className: "text-center",
                    "targets": [0,8,9,10,11]
                },
                {
                    className: "text-small",
                    "targets": [1,2,4,6,7]
                }
            ],
            "language": {
                "lengthMenu": "Ver _MENU_ por página",
                "zeroRecords": "Lo siento, no se enontraron anunciantes",
                "info": "Página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay anunciantes",
                "infoFiltered": "(Filtrado de _MAX_ asignados)",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primera",
                    "last": "Última",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                
                if(aData.has_contact_today == 1) {
                    $(nRow).addClass('success');
                }

                $('td:eq(0)', nRow).html(
                    "<button class='btn btn-xs btn-success advertiserModal' data-advertiser='" + JSON.stringify(aData) + "' title='Ver Anunciante' data-toggle='modal' data-target='#userModal'><i class='fa fa-search-plus'></i></button>"
                );

                $('td:eq(1)', nRow).html(aData.created_at.substring(0,10));

                if(!aData.company.trim()) {
                    $('td:eq(2)', nRow).html('--');
                }
                
                $('td:eq(6)', nRow).html(
                    UserService.getHtmlTableStates(aData.states, 171)
                );

                if(aData.count_intentions > 0) {
                    $('td:eq(7)', nRow).html(
                        getHtmlIntentionStates(aData)
                    );
                }

                if(aData.count_logs > 0) {
                    $('td:eq(8)', nRow).html(UserService.getHtmlLogs(aData.count_logs, aData.last_login_at_humans));                    
                }

                if(aData.count_views > 0) {
                    $('td:eq(9)', nRow).html(UserService.getHtmlLogs(aData.count_views, aData.range_view_at_humans));                    
                }

                if(aData.contacts && aData.contacts.length > 0) {
                    div = UserService.getLastContact(aData.contacts);
                    $('td:eq(10)', nRow).html(div.html());
                }
                else {
                    $('td:eq(10)', nRow).html('0');    
                }

            },
            "drawCallback": function(settings, json) {
                $("#countDatatable").html(settings.fnRecordsDisplay());
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        UserService.initDatatable(table);
        UserService.initSimpleSearchSelect("#registration_states",13);
        UserService.initSimpleSearchSelect('#cities', 14);
        UserService.initSimpleSearchSelect("#economic_activities", 16);
        UserService.initActions(20);
        UserService.initActionsRange(21);
        UserService.initSimpleSearchSelect("#tag_id", 22);

        $("#advertisers-datatable_filter input").unbind();

        $("#advertisers-datatable_filter input").bind('keyup', function(e) {
            if(e.keyCode == 13) {
                table.search(this.value).draw();   
            }
        }); 
    }

    function reload()
    {
        table.search(UserService.getFilterSearch());
        table.draw();
    }
    
    function initModalEvent() {
        $(document).on("click", ".advertiserModal", function () {
            var advertiser = $(this).data('advertiser');
            drawModal(advertiser);
        });
    }

    function initQuoteModalEvent()
    {
        $("#newQuote").click(function() {
            console.log(actualAdvertiser.comments);
            $(".questionsModal #user_company").text(actualAdvertiser.company);
            $(".questionsModal #advertiser_comments").text(actualAdvertiser.comments);
            $(".questionsModal").modal();
        });

        $("#form-questions").click(function() {
            var modal     = $(".questionsModal");
            var button    = modal.find("#form-questions");
            var loading   = modal.find("#sk-spinner-modal");
            var url       = $('#userModal #newQuote').attr('data-url');
            var questions = [];

            loading.show();
            button.prop("disabled", true);
            modal.find("textarea").each(function(){
                questions[$(this).attr('data-question-id')] = $(this).val();
            });

            var parameters = {
                'title':        modal.find("#title").val(),
                'action_date':  modal.find("#question_action_date").val(),
                'contact_type': modal.find("#question_contact_type").val(),
                'cities'    :   modal.find("#question_cities").val(),
                'audiences':    modal.find("#question_audiences").val(),
                'advertiser_comments':  modal.find("#advertiser_comments").val(),
                'questions[]':  questions
            };

            $.post(url, parameters, function( data ) {
                if(data.success) {
                    loading.hide();
                    reload();
                    modal.modal('toggle');
                    button.prop("disabled", false);
                    var socialContact = UserService.getSocialContact(data.contact);
                    $('#userModal #comments').prepend(socialContact);
                    $('#userModal #text-comments').text(modal.find("#advertiser_comments").val());
                }
                else {
                    console.log('error');
                }

            }).fail(function(data) {
                loading.hide();
                modal.modal('toggle');
                button.prop("disabled", false);
                
                swal({
                    title: 'Hubo un error',
                    text: 'Código ' + data.status,
                    type: "warning",
                });
            });
        });
    }

    function getHtmlIntentionStates(aData) {
        var html        = $('<div style="width:70px; margin:0 auto;"></div>').addClass('text-center');
        var interest    = $('<span style="margin: 0 1px;"></span>')
                            .addClass('badge badge-default')
                            .text(aData.count_interest_intentions)
                            .attr('data-toggle', 'tooltip')
                            .attr('data-placement', 'top')
                            .attr('title', 'Intereses');

        var by_contact  = $('<span style="margin: 0 1px;"></span>')
                            .addClass('badge badge-warning')
                            .text(aData.count_by_contact_intentions)
                            .attr('data-toggle', 'tooltip')
                            .attr('data-placement', 'top')
                            .attr('title', 'Leads por contactar');

        var management  = $('<span style="margin: 0 1px;"></span>')
                            .addClass('badge badge-info')
                            .text(aData.count_management_intentions)
                            .attr('data-toggle', 'tooltip')
                            .attr('data-placement', 'top')
                            .attr('title', 'Leads en gestión');

        html.append(interest)
            .append(by_contact)
            .append(management);        

        return html;
    }

    function drawModal(advertiser) {
        actualAdvertiser = advertiser;
        UserService.drawModalUser("userModal", advertiser, "anunciantes");
        /** Commercial state **/
        $('#userModal #count_intentions').text(advertiser.count_intentions);
        $('#userModal #count_leads').text(advertiser.count_leads);
        $('#userModal #interest').text(advertiser.count_interest_intentions);
        $('#userModal #by_contact').text(advertiser.count_by_contact_intentions);
        $('#userModal #management').text(advertiser.count_management_intentions);
        $('#userModal #sold').text(advertiser.count_sold_intentions);
        $('#userModal #discarded').text(advertiser.count_discarded_intentions);
        $('#userModal #text-comments').text(advertiser.comments);
        $("#advertiser_comments").val(advertiser.comments);

        $('#delete_advertiser').attr("data-url", '/anunciantes/' + advertiser.id);

        var intention_at_start = $('#intention_at_start').val();
        var intention_at_end = $('#intention_at_end').val();

        if(intention_at_start || intention_at_end) {
            $('#userModal #lead_dates').text(' | De ' + intention_at_start + ' a ' + intention_at_end);  
        }
        else {
            $('#userModal #lead_dates').text(' ');  
        }

        /** Proposals **/
        $('#userModal a#link-proposals').attr('href', '/anunciantes/' + advertiser.id);
        $('#userModal #count-proposals').text('(' + advertiser.count_proposals + ')');
        $('#userModal #created_at').text(advertiser.created_at_humans);

        /** Contacts **/
        $('#userModal #newContact').attr('data-url', '/anunciantes/' + advertiser.id + '/contacts');

        $('#userModal #newQuote').attr('data-url', '/anunciantes/' + advertiser.id + '/quotes');

        $('#userModal #comments').html('');

        $.each(advertiser.contacts, function( index, contact ) {
            var socialContact = UserService.getSocialContact(contact);
            $('#userModal #comments').append(socialContact);
        });
    }

    function initReloadAjaxDate(inputInit, inputFinish, parameterInit, parameterFinish) {
        $(inputInit + ', ' + inputFinish).on('change', function() {
            table.ajax
                .url('/anunciantes/search?' + parameterInit + '=' + $(inputInit).val() + '&' + parameterFinish + '=' + $(inputFinish).val())
                .load();
        } );               
    }

    function initDeleteAdvertiser() {
        console.log('inicio');

        $("#delete_advertiser").click(function(e) {   
            swal({
                title: '¿Estás seguro?',
                text: 'El anunciante será eliminado',
                type: "warning",
                confirmButtonText: "Eliminar",
                confirmButtonColor: "#ed5565",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
                html: true
            },
            function(isConfirm) {
                if (isConfirm) {     
                    $.ajax({
                        url: $("#delete_advertiser").attr('data-url'),
                        type: 'DELETE',
                        success: function(data) {
                            if(data.success) {
                                swal({
                                    "title": "Anunciante eliminado", 
                                    "type": "success",
                                    closeOnConfirm: true,
                                });

                                table.search(' ').draw();
                                $('#userModal').modal('toggle');
                            }
                            else {
                                swal({
                                    "title": "Hubo un error", 
                                    "type": "warning",
                                    closeOnConfirm: true,
                                });
                            }
                        }
                    });
                }
                else {

                } 
            });
        });
    };

    return {
        init: function(urlSearch) {
            initTable(urlSearch);
            UserService.initInputsDateRange();
            initDeleteAdvertiser();
            //UserService.initSearchDateRanges(13,14,15);
            initModalEvent();
            initQuoteModalEvent();
            UserService.initActionNotifications(21);
            UserService.initChangeTag();
            //initReloadAjaxDate('#intention_at_start', '#intention_at_end', 'init', 'finish');
        },
        reload: function() {
            reload();
        },
        getSocialContact: function(contact) {
            return UserService.getSocialContact(contact);
        },
        drawModal: function(advertiser) {
            drawModal(advertiser);
            $("#userModal").modal();
        }
    };
}();