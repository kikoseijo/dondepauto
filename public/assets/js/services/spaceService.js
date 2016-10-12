/**
 * Created by Desarrollador 1 on 15/04/2016.
 */

var SpaceService = function() {

    var table;
    var urlSearch;

    function initTableProposal(urlSearch) {
        urlSearch = urlSearch;

        table = $('#spaces-datatable').DataTable({
            "order": [[1, "desc"]],
            "ajax": urlSearch,
            "pageLength": 50,
            "processing": true,
            "serverSide": true,
            "deferRender": true,
            "columns": [
                { "data": null, "name": "id", "orderable": false, "searchable": false},
                { "data": "publisher_company", "name": "publisher_company" },
                { "data": "name", "name": "name" }, // 2
                { "data": "sub_category_name_format_name", "name": "sub_category_name_format_name" }, // 3

                { "data": "pivot_minimal_price", "data": "pivot_minimal_price" }, // 4
                { "data": "pivot_markup_price", "name": "pivot_markup_price" },
                { "data": "pivot_discount", "name": "pivot_discount" }, // 6
                { "data": "pivot_commission_price", "name": "pivot_commission_price" }, // 7
                { "data": "public_price", "name": "public_price" }, // 8
                
                { "data": "category_id", "name": "category_id" }, // 9
                { "data": "sub_category_id", "name": "sub_category_id" },
                { "data": "format_id", "name": "format_id" },
                { "data": "publisher_id", "name": "publisher_id" },
                { "data": "city_id", "name": "city_id" },
                { "data": "tags", "name": "tags" },
                { "data": "description", "name": "description" }, // 15
                { "data": "address", "name": "address" },
                { "data": "impact_scene_id", "name": "impact_scene_id", "searchable": false }, // 17
                { "data": "publisher_email", "name": "publisher_email" },
                { "data": "active", "name": "active" } // 19
            ],
            "columnDefs": [
                {
                    "targets": [9,10,11,12,13,14,15,16,17,18,19],
                    "visible": false,
                    "searchable": true
                },
                {
                    className: "text-center text-small",
                    "targets": [0,4,5,6,7,8]
                },
                {
                    className: "text-small",
                    "targets": [1,2,3]
                }
            ],
            "language": {
                "lengthMenu": "Ver _MENU_ por página",
                "zeroRecords": "Lo siento, no se enontraron espacios",
                "info": "Página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay espacios",
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
                $('td:eq(0)', nRow).html(
                    "<button class='btn btn-xs btn-success spaceModal' data-space='" + JSON.stringify(aData) + "' title='Ver Espacio' data-toggle='modal' data-target='#spaceModal'><i class='fa fa-search-plus'></i></button>"
                );

                var commission = $("<div></div>")
                    .append(numeral(aData.pivot_commission_price).format('$ 0,0'))
                    .append($("<span style='font-size:10px;'></span>").addClass("text-success").text(' (' + numeral(aData.commission).format('0%') + ')'));

                $('td:eq(7)', nRow).html(commission);

                $('td:eq(4)', nRow).html(
                    numeral(aData.pivot_minimal_price).format('$ 0,0')
                );

                var pivot_markup = $("<span style='font-size:10px;'></span>")
                    .text(' (' + numeral(aData.pivot_markup).format('0%') + ')')
                    .attr('data-toggle', 'tooltip')
                    .attr('data-placement', 'top');

                if(aData.discount == 0) {
                    pivot_markup.attr('title', 'Markup DP+').addClass('text-info'); 
                }
                else {
                    pivot_markup.attr('title', 'Descuento Anunciante').addClass('text-success');   
                }

                if(aData.pivot_with_markup == 1) {
                    withMarkup = "DP";
                }
                else {
                    withMarkup = "Medio";
                }

                var markup = $("<div></div>")
                    .append(numeral(aData.pivot_markup_price).format('$ 0,0'))
                    .append(pivot_markup)
                    .append(" - " + withMarkup);

                $('td:eq(5)', nRow).html(markup);

                var pivot_public_price = $("<strong></strong>").addClass("text-info").text(numeral(aData.pivot_public_price).format('$ 0,0'));

                $('td:eq(8)', nRow).html(pivot_public_price);

                var discount = $("<div></div>")
                    .append(numeral(aData.pivot_discount_price).format('$ 0,0'))
                    .append($("<span style='font-size:10px;'></span>").addClass("text-success").text(' (' + numeral(aData.pivot_discount).format('0%') + ')'));

                $('td:eq(6)', nRow).html(discount);

                if(aData.active == 0) {
                    $(nRow).addClass('warning');    
                }
                
            },
            "drawCallback": function(settings, json) {
                $("#countDatatable").html(settings.fnRecordsDisplay());
                $('[data-toggle="tooltip"]').tooltip();
                var searchSpace = $("#search-space").data("search");
                if(searchSpace){
                    $(".spaceModal").click();
                    $("#search-space").data("search", null);
                }
            }
        });

        UserService.initDatatable(table);

        $("#spaces-datatable_filter input").unbind();

        $("#spaces-datatable_filter input").bind('keyup', function(e) {
            if(e.keyCode == 13) {
                table.search(this.value).draw();   
            }
        }); 
    }

    function initTable(urlSearch) {
        urlSearch = urlSearch;

        table = $('#spaces-datatable').DataTable({
            "order": [[1, "desc"]],
            "ajax": urlSearch,
            "pageLength": 50,
            "processing": true,
            "serverSide": true,
            "deferRender": true,
            "columns": [
                { "data": null, "name": "id", "orderable": false, "searchable": false},
                { "data": "publisher_company", "name": "publisher_company" },
                { "data": "name", "name": "name" },
                { "data": "category_name", "name": "category_name" },
                { "data": "sub_category_name", "name": "sub_category_name" },
                { "data": "format_name", "name": "format_name" }, // 5

                { "data": "publisher_commission_rate", "name": "publisher_commission_rate" }, // 6
                { "data": "minimal_price", "data": "minimal_price" },
                { "data": "percentage_markdown", "name": "percentage_markdown" },
                { "data": "markup_price", "name": "markup_price" },
                { "data": "public_price", "name": "public_price" }, // 10

                { "data": "category_id", "name": "category_id" }, // 11
                { "data": "sub_category_id", "name": "sub_category_id" }, // 12
                { "data": "format_id", "name": "format_id" }, // 13
                { "data": "publisher_id", "name": "publisher_id" }, // 14
                { "data": null, "name": "city_id" }, // 15
                { "data": "tags", "name": "tags" },
                { "data": "description", "name": "description" },
                { "data": "address", "name": "address" },
                { "data": null, "name": "impact_scene_id", "searchable": false }, // 19
                { "data": "publisher_email", "name": "publisher_email" },
                { "data": "active", "name": "active" } // 21
            ],
            "columnDefs": [
                {
                    "targets": [16,17,18,20,21],
                    "visible": false,
                    "searchable": true
                },
                {
                    "targets": [11, 12, 13, 14, 15, 19, 21],
                    "visible": false,
                    "searchable": false
                },
                {
                    className: "text-center text-small",
                    "targets": [0,6,7,8,9,10]
                },
                {
                    className: "text-small",
                    "targets": [1,2,3,4,5]
                }
            ],
            "language": {
                "lengthMenu": "Ver _MENU_ por página",
                "zeroRecords": "Lo siento, no se enontraron espacios",
                "info": "Página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay espacios",
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
                $('td:eq(0)', nRow).html(
                    "<button class='btn btn-xs btn-success spaceModal' data-space='" + JSON.stringify(aData) + "' title='Ver Espacio' data-toggle='modal' data-target='#spaceModal'><i class='fa fa-search-plus'></i></button>"
                );

                $('td:eq(2)', nRow).attr('style', "font-weight: bold; color: #4949a0; min-width: 220px;");

                $('td:eq(6)', nRow).html(
                    numeral(aData.commission).format('0%')
                );

                $('td:eq(7)', nRow).html(
                    numeral(aData.minimal_price).format('$ 0,0[.]00')
                );

                var percentage_markdown = $("<span></span>")
                    .text(numeral(aData.percentage_markdown).format('0%'))
                    .attr('data-toggle', 'tooltip')
                    .attr('data-placement', 'top')

                if(aData.discount == 0) {
                    percentage_markdown.attr('title', 'Markup DP+').addClass('text-info'); 
                }
                else {
                    percentage_markdown.attr('title', 'Descuento Anunciante').addClass('text-success');   
                }

                $('td:eq(8)', nRow).html(percentage_markdown);

                $('td:eq(9)', nRow).html(
                    numeral(aData.markup_price).format('$ 0,0[.]00')
                );

                $('td:eq(10)', nRow).html(
                    numeral(aData.public_price).format('$ 0,0[.]00')
                );

                if(aData.active == 0) {
                    $(nRow).addClass('warning');    
                }
                
            },
            "drawCallback": function(settings, json) {
                $("#countDatatable").html(settings.fnRecordsDisplay());
                $('[data-toggle="tooltip"]').tooltip();
                var searchSpace = $("#search-space").data("search");
                if(searchSpace){
                    $(".spaceModal").click();
                    $("#search-space").data("search", null);
                }
            }
        });

        UserService.initDatatable(table);

        $("#spaces-datatable_filter input").unbind();

        $("#spaces-datatable_filter input").bind('keyup', function(e) {
            if(e.keyCode == 13) {
                table.search(this.value).draw();   
            }
        }); 
    };

    function initFilters() {
        initChangeSelect("#sub_categories",12);
        initChangeSelect("#formats",13);
        initChangeSelect("#cities",15);
        initChangeSelect("#publishers",14);
        initChangeSelect("#scenes",19);

        UserService.initSimpleSearchSelect("#active_state", 21);
    };

    function initModalEvent(showStates) {
        $(document).on("click", ".spaceModal", function () {
            var space = $(this).data('space');
            drawModal("spaceModal", space, "espacios", showStates);
        });

        // Add slimscroll to element
        $('.scroll_content').slimscroll({
            height: '100px'
        });
        $('.scroll_content_image').slimscroll({
            height: '160px'
        });
    };

    function initChangeSelect(input, column) {
        $(input).on('change', function () {
            
            var parameters = {
                'category': $("#category").val(),
                'sub_category': $("#sub_categories").val(),
                'format': $("#formats").val(),
                'city': $("#cities").val(),
                'publisher': $("#publishers").val(),
                'scene': $("#scenes").val()
            };

            value = $(this).val();

            $.get("/espacios/ajax", parameters, function( data ) {
                if(data.success) {
                    SpaceService.changeSelects(data.inputs, column, value);
                }
            });
        });
    }

    function changeSelects(inputs, column, searchValue) {
        var columns = [];

        if(inputs.sub_categories) {
            $('#sub_categories option:gt(0)').remove();
            $.each(inputs.sub_categories, function(value,text) {
                $('#sub_categories').append(
                    $("<option></option>").attr("value", value).text(text)
                );
            }); 
            columns.push(12);
        }

        if(inputs.publishers) {
            $('#publishers option:gt(0)').remove();
            $.each(inputs.publishers, function(value,text) {
                $('#publishers').append(
                    $("<option></option>").attr("value", value).text(text)
                );
            });  
            columns.push(14);
        }

        if(inputs.scenes) {
            $('#scenes option:gt(0)').remove();
            $.each(inputs.scenes, function(value,text) {
                $('#scenes').append(
                    $("<option></option>").attr("value", value).text(text)
                );
            });  
            columns.push(19);
        }

        if(inputs.cities) {                
            $('#cities option:gt(0)').remove();
            $.each(inputs.cities, function(value,text) {
                $('#cities').append(
                    $("<option></option>").attr("value", value).text(text)
                );
            }); 
            columns.push(15);
        }

        if(inputs.formats) {
            $('#formats').removeAttr("disabled");
            $('#formats option:gt(0)').remove();
            $.each(inputs.formats, function(value,text) {
                $('#formats').append(
                    $("<option></option>").attr("value", value).text(text)
                );
            }); 
            columns.push(13);
        }
        else if(! $("#sub_categories").val()) { 
            $('#formats').attr('disabled','disabled');
            $('#formats').val('');
            columns.push(13);
        }

        console.log(column);
        console.log(searchValue);

        table.columns(columns)
            .search('')
            .column(column)
            .search(searchValue);
        
        table.draw();
    };

    function drawModal(inputId, space, urlName, showStates) {

        if(showStates) {
            var modal = $("#spaceModal");
            var spiner = modal.find("#sk-spinner-modal");

            spiner.show();
            $("#prueba").html("");

            $.get('/medios/' + space.publisher_id + '/states', {}, function( data ) {
                spiner.hide();
                $("#prueba").html(UserService.getHtmlTableStates(data.states, 230));
                $('[data-toggle="tooltip"]').tooltip();
            });    
        }
        

        $('#' + inputId + ' #modalEdit').attr('href', '/' + urlName + '/' + space.id + '/edit');
        $('#' + inputId + ' #modalPublisher').attr('href', '/medios/' + space.publisher_id)
            .attr('title', 'Ver Medio - ' + space.publisher_name);

        $('#' + inputId + ' .actionSpaceModal')
            .attr('data-space-id', space.id)
            .attr('data-space-name', space.name)
            .attr('data-publisher-company', space.publisher_company);

        $('#' + inputId + ' #modalSuggestSpace')
            .attr('data-max-discount', space.percentage_markdown * 100);
            
        /** Space Data **/
        $('#delete_space').data("spaceid", space.id);
        $('#delete_space').attr("data-url", '/medios/' + space.publisher_id + '/espacios/' + space.id);

        $('#' + inputId +' #space_name').text(space.name);
        $('#' + inputId +' #space_crated_at_date').text(space.created_at_date);
        
        $('#' + inputId + ' a#publisher_company').attr('href', '/medios/' + space.publisher_id).text(space.publisher_company);

        /** Publisher Personal Data **/
        $('#' + inputId + ' #publisher_name').text(space.publisher_name);
        $('#' + inputId + ' #publisher_email a').attr('href', 'mailto:' + space.publisher_email).text(space.publisher_email);
        $('#' + inputId + ' a#publisher_phone').attr('href', 'tel:' + space.publisher_phone).text(space.publisher_phone);
        $('#' + inputId + ' a#publisher_cel').attr('href', 'tel:' + space.publisher_cel).text(space.publisher_cel);

        /** Data Detail **/
        $('#' + inputId + ' #created_at').text(space.created_at_humans);
        $('#' + inputId + ' #publisher_economic_activity').text(space.publisher_economic_activity_name);
        $('#' + inputId + ' #publisher_address').text(space.publisher_address);
        $('#' + inputId + ' #publisher_company_nit').text(space.publisher_company_nit);
        $('#' + inputId + ' #publisher_company_role').text(space.publisher_company_role);
        $('#' + inputId + ' #publisher_company_area').text(space.publisher_company_area);

        /** Agreement **/
        $('#' + inputId + ' #publisher_signed_agreement').text('(' + space.publisher_signed_agreement_lang + ')');
        $('#' + inputId + ' #publisher_commission_rate').text(space.publisher_commission_rate);
        $('#' + inputId + ' #publisher_signed_at').text(space.publisher_signed_at_datatable);
        $('#' + inputId + ' #publisher_discount').text(space.publisher_discount);
        $('#' + inputId + ' #publisher_retention').text(space.publisher_retention);

        /** Space Segment **/
        $('#' + inputId + ' #category_name').text(space.category_name);
        $('#' + inputId + ' #sub_category_name').text(space.sub_category_name);
        $('#' + inputId + ' #format_name').text(space.format_name);
        

        $('#' + inputId + ' #city_name').text("");
        if(space.cities) {
            $.each(space.cities, function( index, city ) {
                $('#' + inputId + ' #city_name')
                    .append(city.nombre_ciudad_LI)
                    .append(" - ");
            });
        }

        $('#' + inputId + ' #impact_scene_name').text("");
        if(space.impact_scenes) {
            $.each(space.impact_scenes, function( index, scene ) {
                $('#' + inputId + ' #impact_scene_name')
                    .append(scene.nombre_tipo_lugar_LI)
                    .append(" - ");
            });
        }

        $('#' + inputId + ' #address').text(space.address);

        /** Prices **/   
        $('#' + inputId + ' #minimal_price').text(numeral(space.minimal_price).format('$ 0,0[.]00'));
        $('#' + inputId + ' #markup').text(numeral(space.percentage_markdown).format('0%'));
        $('#' + inputId + ' #markup_price').text(numeral(space.markup_price).format('$ 0,0[.]00'));
        $('#' + inputId + ' #public_price').text(numeral(space.public_price).format('$ 0,0[.]00'));
        $('#' + inputId + ' #period').text(space.period);
        $('#' + inputId + ' #impacts').text(space.impacts);

        /** Description **/
        $('#' + inputId + ' #space-description').html(space.description);

        /** Images **/
        var divImages = $('#' + inputId + ' #space-images');
        divImages.html('');

        if(space.images) {
            $.each(space.images, function( index, image ) {

                var img = $("<img style='width:100px; margin:5px;'></img>")
                    .attr('src', image.thumb);

                var a = $('<a data-gallery=""></a>')
                    .attr('href', image.url)
                    .append(img);

                divImages.append(a);
            });    
        }

        /** Comments **/
        $('#' + inputId +' #comments').text(space.comments);

        /** State **/
        $('#' + inputId +' #state')
            .removeClass()
            .addClass('btn btn-circle btn-' + space.state_class)
            .attr('data-original-title', space.state);

        $('#' + inputId +' #state i')
            .removeClass()
            .addClass('fa ' + space.state_icon);

        /** Active **/
        if(space.active == 1) {
            var input = $("<input checked></input>");
        }
        else {
            var input = $("<input></input>");
        }

        input.attr("type", "checkbox")
            .addClass("js-switch js-switch-click")
            .attr("data-url", "/medios/" + space.publisher_id + "/espacios/" + space.id + "/enable");

        $('#spaceModal #space_sw_active').html("").append(input);

        var elem = document.querySelector(".js-switch");

        if(elem) {
            switchery = new Switchery(elem, { 
                color: '#1AB394',
                size: 'small'
            });   

            initChangeAgreement(); 
        }

        
    };

    function getFilterSearch()
    {
        return $(".dataTables_filter input").val();
    };

    function reload()
    {
        table.search(getFilterSearch()).draw();
    };

    function initChangeAgreement() {
        var manual = false;
        var changeCheckbox = document.querySelector('.js-switch-click');

        changeCheckbox.onchange = function(e) {   
            if(! manual) {

                if(changeCheckbox.checked) {
                    swal({
                        title: '¿Estás seguro?',
                        type: 'warning',
                        text: 'El espacio será activado',
                        confirmButtonText: "Confirmar",
                        confirmButtonColor: "#FFAC1A",
                        cancelButtonText: "Cancelar",
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                    }).then(function() {
                        
                        var parameters = {"active": "1"};
                        
                        $.post($("#space_sw_active input").attr('data-url'), parameters, function( data ) {
                            if(data.success) {
                                swal("Espacio activado", "", "success");
                                reload();
                            }
                            else{
                                manual = true;
                                changeCheckbox.click();
                                manual = false;
                                swal("Hubo un error", "", "warning");
                            }
                        });
                    }, function(dismiss) {
                      if (dismiss === 'cancel') {
                            manual = true;
                            changeCheckbox.click();
                            manual = false;
                      }
                    });    
                }
                else {
                    swal({
                        title: '¿Estás seguro?',
                        input: 'select',
                        inputOptions: {
                            'none': 'No avisar al Medio Publicitario',
                            'incomplete': 'Avisar sobre oferta incompleta',
                            'terms': 'Avisar sobre incumplimiento términos'
                        },
                        inputValue: 'none',
                        inputPlaceholder: 'Seleccione una opción',
                        text: 'El espacio será inactivado',
                        confirmButtonText: "Confirmar",
                        confirmButtonColor: "#FFAC1A",
                        cancelButtonText: "Cancelar",
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                    }).then(function(result) {
                        
                        var parameters = {"active": "0", "option": result};
                        
                        $.post($("#space_sw_active input").attr('data-url'), parameters, function( data ) {
                            if(data.success) {
                                swal("Espacio inactivo", "", "success");
                                reload();
                            }
                            else{
                                manual = true;
                                changeCheckbox.click();
                                manual = false;
                                swal("Hubo un error", "", "warning");
                            }
                        });
                    }, function(dismiss) {
                      if (dismiss === 'cancel') {
                            manual = true;
                            changeCheckbox.click();
                            manual = false;
                      }
                    });    
                }

                
            } 
        };
    }

    function initDeleteSpace() {

        $("#delete_space").click(function(e) {   
            swal({
                title: '¿Estás seguro?',
                text: 'El espacio será eliminado',
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
                        url: $("#delete_space").attr('data-url'),
                        type: 'DELETE',
                        success: function(data) {
                            if(data.success) {
                                swal({
                                    "title": "Espacio eliminado", 
                                    "type": "success",
                                    closeOnConfirm: true,
                                });

                                reload();
                                $('.modal').modal('toggle');
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

    function initProposalModal() 
    {
        $("#modalProposalSpace").click(function(){
            var space_id = $(this).attr('data-space-id');
            var publisher_company = $(this).attr('data-publisher-company');
            var space_name = $(this).attr('data-space-name');

            $(".proposalSpaceModal #proposalSpacePublisher").text(publisher_company);
            $(".proposalSpaceModal #proposalSpaceName").text(space_name);
            $(".proposalSpaceModal").attr('data-url', "/propuestas/agregar/" + space_id);

            $(".proposalSpaceModal").modal();
        });

        $.validator.methods.selectproposals = function(value, element) {
            return ($(".form-select-proposals ul.chosen-choices").find("li.search-choice").length > 0);
        }

        $("#proposal_form").validate({
            rules: {
                dummy: {
                    selectproposals: true
                }
            },
            messages: {
                dummy: {
                    selectproposals: 'Debe seleccionar al menos una propuesta'
                }
            }
        });
    }

    function initPostProposal() 
    {
        var modal = $(".proposalSpaceModal");
        var button = modal.find("#form-proposal-space");
        var spiner = modal.find("#sk-spinner-modal");

        button.click(function() {
            var url = modal.attr('data-url');
            var parameters = {
                'proposals':  modal.find("select").val(),
            }   

            if($("#sugesst_form").valid()) {
                spiner.show();
                button.prop("disabled", true);

                $.post(url, parameters, function( data ) {
                    if(data.success) {
                        spiner.hide();
                        //dataTable.search(getFilterSearch()).draw();
                        modal.modal('toggle');
                        button.prop("disabled", false);
                    }
                    else {
                        spiner.hide(); 
                        modal.modal('toggle');
                        button.prop("disabled", false);
                        
                        swal({
                            title: 'Hubo un error',
                            text: 'Error controlado',
                            type: "warning",
                        });
                    }
                }).fail(function(data) {
                    spiner.hide();
                    button.prop("disabled", false);

                    if(data.status == 422) {
                        swal({
                            title: 'Hubo un error',
                            text: data.responseText,
                            type: "warning",
                        });   
                    }
                    else {
                        modal.modal('toggle');
                        swal({
                            title: 'Hubo un error',
                            text: 'Código ' + data.status,
                            type: "warning",
                        });    
                    }
                    
                });
            }
        });
    }

    function initSuggestModal() 
    {
        $("#modalSuggestSpace").click(function(){
            var space_id = $(this).attr('data-space-id');
            var publisher_company = $(this).attr('data-publisher-company');
            var space_name = $(this).attr('data-space-name');
            var max_discount = $(this).attr('data-max-discount');

            $(".suggestSpaceModal #suggestSpacePublisher").text("Medio: " + publisher_company);
            $(".suggestSpaceModal #suggestSpaceName").text("Espacio: " + space_name);
            $(".suggestSpaceModal").attr('data-url', "/espacios/recomendar/" + space_id);
            $(".suggestSpaceModal #field_discount label").text("Descuento - (Máximo: " + max_discount + "%)");
            
            $(".suggestSpaceModal #field_discount input").attr({
                "max": max_discount,
                "data-msg-max" : "El descuento máximo es " + max_discount
            });

            $(".suggestSpaceModal").modal();
        });

        $.validator.methods.selectavertisers = function(value, element) {
            return ($(".form-select-advertisers ul.chosen-choices").find("li.search-choice").length > 0);
        }

        $("#sugesst_form").validate({
            rules: {
                dummy: {
                    selectavertisers: true
                }
            },
            messages: {
                dummy: {
                    selectavertisers: 'Debe seleccionar al menos un anunciante'
                }
            }
        });
    }

    function initPostSuggest() 
    {
        var modal = $(".suggestSpaceModal");
        var button = modal.find("#form-suggest-space");
        var spiner = modal.find("#sk-spinner-modal");

        button.click(function() {
            var url = modal.attr('data-url');
            var parameters = {
                'advertisers':  modal.find("select").val(),
                'discount':     modal.find("#discount").val()
            }   

            if($("#sugesst_form").valid()) {
                spiner.show();
                button.prop("disabled", true);

                $.post(url, parameters, function( data ) {
                    if(data.success) {
                        spiner.hide();
                        //dataTable.search(getFilterSearch()).draw();
                        modal.modal('toggle');
                        button.prop("disabled", false);
                        $('.advertisr-chosen-select').chosen("destroy");
                        $('.advertisr-chosen-select').val(0);
                        $('.advertisr-chosen-select').chosen({width: "100%"});
                    }
                    else {
                        spiner.hide(); 
                        modal.modal('toggle');
                        button.prop("disabled", false);
                        
                        swal({
                            title: 'Hubo un error',
                            text: 'Error controlado',
                            type: "warning",
                        });
                    }
                }).fail(function(data) {
                    spiner.hide();
                    button.prop("disabled", false);

                    if(data.status == 422) {
                        swal({
                            title: 'Hubo un error',
                            text: data.responseText,
                            type: "warning",
                        });   
                    }
                    else {
                        modal.modal('toggle');
                        swal({
                            title: 'Hubo un error',
                            text: 'Código ' + data.status,
                            type: "warning",
                        });    
                    }
                    
                });
            }

        });
    }

    return {
        init: function(urlSearch) {
            initTable(urlSearch);
            initFilters();
            initModalEvent(true);
            initDeleteSpace();
            initSuggestModal();
            initPostSuggest();
            initProposalModal();
            initPostProposal();
        },
        initProposal: function(urlSearch) {
            initTableProposal(urlSearch);
            initModalEvent(true);
        },
        initModalEvent: function (showStates) {
            initModalEvent(showStates);
        },
        initDatatable: function(urlSearch) {
            initTable(urlSearch); 
        },
        changeSelects: function(inputs, column, searchValue) {
            changeSelects(inputs, column, searchValue);
        },
        reload: function(){
            reload();
        },
        initPostSuggest: function(){
            initSuggestModal();
            initPostSuggest();
        },
        drawModal: function(inputId, space, urlName) {
            drawModal(inputId, space, urlName);
        }
    };
}();