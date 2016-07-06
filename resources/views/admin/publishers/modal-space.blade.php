<div class="modal fade" id="spaceModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg list-space">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <div class="row">
                    <div class="col-xs-6">
                        <h2 class="modal-title h4" style="font-size: 15px;">
                            Espacio - <span id="space_name"></span> <br>
                            <strong>Medio:</strong> <a id="publisher_company" href="#" target="_blank"></a>
                        </h2>
                    </div>
                    <div class="col-xs-6 timeline" id="prueba">

                    </div>
                </div>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Imagenes
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12" id="space-images">
                                        
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Detalles del Espacio
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <p>
                                            <span class="h5"> Categoría </span> <br>
                                            <span class="h5"> Sub Categoría </span> <br>
                                            <span class="h5"> Formato </span> <br>
                                            <span class="h5"> Escenario </span> <br>
                                            <span class="h5"> Ciudad </span> <br>
                                            <span class="h5"> Dirección </span>
                                        </p>
                                    </div>
                                    <div class="col-xs-8">
                                        <p>
                                            <span class="h5" id="category_name">  </span> <br>
                                            <span class="h5" id="sub_category_name">  </span> <br>
                                            <span class="h5" id="format_name">  </span> <br>
                                            <span class="h5" id="impact_scene_name">  </span> <br>
                                            <span class="h5" id="city_name">  </span> <br>
                                            <span class="h5" id="address">  </span>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Precios del Espacio
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <p>
                                            <span class="h5"> Precio Mínimo</span> <br>
                                            <span class="h5"> Markup</span> <br>
                                            <span class="h5"> Precio Público</span>
                                        </p>
                                    </div>
                                    <div class="col-xs-7">
                                        <p>
                                            <span class="h5" id="minimal_price"> </span> <br>
                                            <span class="h5" id="markup"> </span> <br>
                                            <span class="h5" id="public_price"> </span>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Datos de contacto
                            </div>
                            <div class="panel-body">
                                <p>
                                    <span class="h5"> <span id="publisher_name">  </span> </span> <br>
                                    <span class="h5"> <span id="publisher_company_role">  </span> </span> <br>
                                    <span class="h5" id="publisher_email"> <i class="fa fa-envelope-o"></i> <a href=""> </a></span> <br>
                                        <span class="h5">   <i class="fa fa-phone"></i> <a href="tel:" id="publisher_phone">  </a> -
                                                            <i class="fa fa-mobile"></i> <a href="tel:" id="publisher_cel">  </a>
                                        </span>
                                </p>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Acuerdo con el Medio <strong id="publisher_signed_agreement"></strong>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-8">
                                        <p>
                                            <span class="h5"> Porcentaje de comisión </span> <br>
                                            <span class="h5"> Fecha firma de acuerdo </span> <br>
                                            <span class="h5"> Descuento pronto pago </span> <br>
                                            <span class="h5"> Retención en la fuente</span>
                                        </p>
                                    </div>
                                    <div class="col-xs-4">
                                        <p>
                                            <span class="h5" id="publisher_commission_rate">  </span> % <br>
                                            <span class="h5" id="publisher_signed_at">  </span> <br>
                                            <span class="h5" id="publisher_discount">  </span> % <br>
                                            <span class="h5" id="publisher_retention"> </span> %
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="modal-footer">
                <a href="#" class="btn btn-warning" id="modalEdit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
                <a href="#" class="btn btn-danger" id="" data-toggle="tooltip" data-placement="top" title="Desactivar anunciante"><i class="fa fa-trash"></i></a>
            </div>
        </div>
    </div>
</div>