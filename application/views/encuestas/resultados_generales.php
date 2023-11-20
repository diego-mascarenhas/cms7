<style>
#DataTables_Table_0_filter, #DataTables_Table_1_filter { float:right;margin-bottom:18px;}
#DataTables_Table_0_filter { display:none;}
#DataTables_Table_0_info {float:left; width:100%;}
#DataTables_Table_0_paginate { text-align:center;}
</style>

     <link href="<?php echo base_url('assets/css/plugins/dataTables/datatables.min.css'); ?>" rel="stylesheet" type="text/css">
           <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-8 col-sm-8 col-xs-8">
                    <h2>Encuestas</h2>
                    <ol class="breadcrumb">
                        <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
                        </li>
                        <li>
	                        <a href="<?php echo base_url('/encuestas'); ?>">Eventos para Encuestas</a>
                        </li>
                        <li class="active">
                            <strong>Resultados Generales</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                </div>
            </div>
        
	        <div class="wrapper wrapper-content">
	            <div class="row">
	                <div class="col-lg-12">
		                <div class="ibox float-e-margins">
	                    	<div class="ibox-title"><h5>Listado de preguntas para el evento <a href="<?php echo base_url('encuestas/modificar/'.$detalle['id']); ?>" title="Ir al evento"><?php echo $detalle['titulo']; ?></a></h5></div>
		                    <div class="ibox-content">
		                        <div class="table-responsive">
				                    <table class="table table-striped table-bordered table-hover dataTables-example" >
					                    <thead>
					                    <tr>
					                        <th width="60%">Pregunta</th>
					                        <th width="10%">Total de votos</th>
					                        <th width="30%">Respuestas</th>
					                    </tr>
					                    </thead>
					                    <tbody>
						                    
						                <?php if (isset($listado)) { ?>
											<?php foreach($listado as $lista) { ?>	
						                   		<tr class="gradeX">
													<td width="60%"><?php echo $lista['titulo']; ?></td>
													<td width="10%">
							                        <?php 
								                        $CI =& get_instance();
								                        $total = $this->evento_model->totalRespuestas($lista['id']);
														echo $total['total'];
													?></td>
													
													<td width="30%">
							                        <?php 
														$CI =& get_instance();
														$respuestas = $this->evento_model->resultadosRespuestas($lista['id']);
									                    if($respuestas)
									                    { 
													?>
													<p><?php 
														foreach($respuestas as $respuesta) 
														{ 
															$total['total'] = 490;
															if($respuesta['votos'] > 0) { $porcentaje = $respuesta['votos']/$total['total']*100; } else { $porcentaje = 0;} 
													?>	
														- <?php echo $respuesta['titulo'].', Votos: '.$respuesta['votos'].' ('.round($porcentaje, 2).'%)'; ?>
						                        	<?php } ?>
						                        	<?php } ?></p></td>
						                    	</tr>
											<?php } ?>	
										<?php } ?>
					                    </tbody>
				                    </table>
		                        </div>
							</div>
	
	                	</div>
	            	</div>
	            </div>
	        </div>
	
	
<!-- Tablas -->
<script src="<?php echo base_url('assets/js/plugins/dataTables/datatables.min.js'); ?>"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.1/css/buttons.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.print.min.js"></script>
<script>
$(document).ready(function(){
    $('.dataTables-example').DataTable({
	    "language": {
            "lengthMenu": "Mostrar _MENU_ resultados por p&aacute;gina",
            "zeroRecords": "No se encontraron resultados",
            "infoEmpty": "No se encontraron resultados",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "search": "Buscar:",
            "emptyTable": "No se encontraron resultados",
            "info": "Mostrando _START_ to _END_ de _TOTAL_ resultados",
            "infoEmpty": "Mostrando 0 to 0 of 0 resultados",
            "infoFiltered":   "(filtrados de _MAX_ total de resultados)",
		    "loadingRecords": "Cargando...",
		    "processing": "Procesando...",
		    "paginate": {
		        "first":      "Primera",
		        "last":       "&Uacute;ltima",
		        "next":       "Siguiente",
		        "previous":   "Anterior"
		    },
		    "aria": {
		        "sortAscending":  ": ordenar ascendente",
		        "sortDescending": ": ordenar descendente"
		    }
        },
        pageLength: 25,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
        	{
        		extend: 'csv',
        		text: 'CSV', 
        		title: 'Resultado de Encuesta CSV'
        	},
            {
            	extend: 'pdfHtml5',
        		text: 'PDF', 
            	title: 'Resultado de Encuesta PDF',
                orientation: 'portrait',
	            pageSize: 'legal',
				exportOptions:
				{
	            rows: { selected: true },
	            stripHtml: true,
	            format: {
	              body: function (data, column, row, node) {
	                var val = $(node).find('input').val();
/*
	                if (row === 4 || row === 5 || row === 6) {
	                  data = data.replace(/<\/?[^>]+>/gi, ' ').replace(/(\r\n|\n|\r)/gm, " ").replace(/\s/g, ' ').replace("<p>"," ").replace("</p>"," ");
	                  console.log(data);
	                }
*/	                if (row >= 0) {

	                  data = data.replace(/\;|\amp/g, ' ').replace("<p>","").replace("</p>","\n").replace("<br>","");
	                }
	                let rval = '';
	                if (val === undefined) {
	                  rval = data
	                }
	                else if (val == 'Yes' || val == 'No') {
	                  rval = val
	                }
	                else {
	                  rval = '$' + val
	                }
	                return rval;
	                //  return (val === undefined) ? data : '$' + val;
	              }
	            },
	          },
              customize: function (doc) {
	            doc.pageMargins = [10, 15, 10, 10];
	            doc.defaultStyle.fontSize = 8;
	            doc.styles.title.fontSize = 11;
	            //doc.styles.title.bold = true;
	            doc.styles.title.alignment = 'center';
	            doc.defaultStyle.alignment = 'left';	            
	            doc.styles.tableHeader.fontSize = 8;	
	            doc.styles.tableHeader.lineHeight = 2;	
	            doc.styles.tableHeader.bold = true;
	            // Remove spaces around page title
	            doc.content[0].text = doc.content[0].text.trim();
	            // Ancho de columnas
	            doc.content[1].table.widths = ['50%','10%','40%'];	            
	            
	            var rowCount = doc.content[1].table.body.length;
	            for (var i = 1; i < rowCount; i++) {
	              doc.content[1].table.body[i][1].alignment = 'center';
	              doc.content[1].table.body[i][1].bold = true;
	              doc.content[1].table.body[i][0].lineHeight = 1;
	              doc.content[1].table.body[i][1].lineHeight = 1;
	              doc.content[1].table.body[i][2].lineHeight = 1.4;
	              doc.content[1].table.body[i][2].marginTop = -10;
	              doc.content[1].table.body[i][2].marginBottom = 0;
	            }
	            // Styling the table: create style object
	            var objLayout = {};
	            // Horizontal line thickness
	            objLayout['hLineWidth'] = function (i) { return .5; };
	            // Vertikal line thickness
	            objLayout['vLineWidth'] = function (i) { return .5; };
	            // Horizontal line color
	            objLayout['hLineColor'] = function (i) { return '#aaa'; };
	            // Vertical line color
	            objLayout['vLineColor'] = function (i) { return '#aaa'; };
	            // Left padding of the cell
	            objLayout['paddingLeft'] = function (i) { return 4; };
	            // Right padding of the cell
	            objLayout['paddingRight'] = function (i) { return 4; };
	            // Inject the object in the document
	            doc.content[1].layout = objLayout;
	          }
            },
            {extend: 'excelHtml5',
            title: 'Resultado de Encuesta EXCEL',
        	text: 'EXCEL', 
            customize: function(xlsx) {
                var sheet = xlsx.xl.worksheets['sheet2.xml'];
                	} 
                }        
           ]	
    });
});
</script>