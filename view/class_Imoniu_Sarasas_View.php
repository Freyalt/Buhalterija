<?php
	class class_Imonu_Sarasas_View {

		function __construct() {
    		$this->get_Form_View();
    		$this->get_Search_Form();
    		$this->get_Imoniu_Sarasas_View();	
		} 

		private function get_Search_Form() {
			global $classDo;
			?>
			<div class="w-100 ppaa">
				<form method="post">
					<div class="input-group">
  						<div class="input-group-prepend">
  							<span class="input-group-text" id="">Paieška: </span>
  						</div>
						<input type="text" aria-label="Small" aria-describedby="inputGroup-sizing-sm" class="form-control" name="paieska_imones_pavadinimas" placeholder="Įmonės Pavadinimas" value="<?php if (!empty($_POST['paieska_imones_pavadinimas'])) { echo $classDo->test_input($_POST['paieska_imones_pavadinimas']); } ?>">
						<input type="text" class="form-control" name="paieska_imones_kodas" placeholder="Įmonės Kodas" value="<?php if (!empty($_POST['paieska_imones_kodas'])) { echo $classDo->test_input($_POST['paieska_imones_kodas']); } ?>">
						<input type="text" class="form-control" name="paieska_pvm_kodas" placeholder="PVM Kodas" value="<?php if (!empty($_POST['paieska_pvm_kodas'])) { echo $classDo->test_input($_POST['paieska_pvm_kodas']); } ?>">
						<div class="input-group-append">
							<input type="submit" class="btn btn btn-primary" value="Ieškoti!">
						</div>
					</div>
				</form>
			</div>

			<?php
		}

		private function get_Imoniu_Sarasas_View() {
			global $classDB, $classDo;
			$queryArray = $classDo->get_Imoniu_Sarasas();
			if (empty($queryArray['query_Data'])) {
				$imoniu_sarasas = $queryArray;
				$pages = 0;
			} else {
				$imoniu_sarasas = $queryArray['query_Data'];
				$pages = $queryArray['page_links'];
			}
			?>
			<table class="table table-bordered">
			  <thead>
			    <tr>
			      <th scope="col" class="idtt text-center"></th>
			      <th scope="col">Įmonės Pavadinimas</th>
			      <th scope="col" class="ktt text-center">Įmonės Kodas</th>
			      <th scope="col" class="pvmtt text-center">PVM Kodas</th>
			      <th scope="col" class="tt text-center">Tvarkyti</th>
			    </tr>
			  </thead>
			  <tbody id="tiekejai_td">

				<?php 	
					if (!empty($imoniu_sarasas)) {
						foreach ($imoniu_sarasas as $key => $value) {
				  		  ?><tr class="clickTarget">
							    <th scope="row" style="display: table-cell; vertical-align: middle;">
							    	<div><input class="form-check-input input cb" type="checkbox" value="" name="<?php echo $value->id; ?>"></div>
							    </th>
							    <td id="imones_pavadinimas-<?php echo $value->id; ?>" value='<?php echo stripslashes($value->imones_pavadinimas); ?>'><?php echo stripslashes($value->imones_pavadinimas); ?></td>
							    <td id="imones_kodas-<?php echo $value->id; ?>" value='<?php echo stripslashes($value->imones_kodas); ?>'><?php echo stripslashes($value->imones_kodas); ?></td>
							    <td id="pvm_kodas-<?php echo $value->id; ?>" value='<?php echo stripslashes($value->pvm_kodas); ?>'><?php echo stripslashes($value->pvm_kodas); ?></td>
							    <td>
							    	<div class="w-100 row" style="margin-left: 0px!important">
							    		<input class="input redaguoti btn btn-primary btn-sm" type="button" value="Redeguoti" name="<?php echo $value->id; ?>" onClick="toTop()">
							    		<form method="post" id="delete-<?php echo $value->id; ?>">
							    			<input class="input trinti btn btn-primary btn-sm btn-danger" type="submit" value="     Trinti    " name="<?php echo $value->id; ?>"><input type="text" name="tiekejas_delte_id" value="<?php echo $value->id; ?>" class="hiddePirkejas">
							    		</form>
							    	</div>
							    </td>
						    </tr>
						    <tr class="hiddePirkejas">
						    	<td colspan="5" style="padding: 0px 0px 0px 0px;">
						    		<div class="w-100 row" style="margin-left: 0px!important">
								    	<div class="col-md-2 Antras borderT textRight">Banko Kodas: </div>
								    	<div id="banko_kodas-<?php echo $value->id; ?>" class="col-md-4 Antras borderT " value='<?php echo stripslashes($value->banko_kodas); ?>'><?php echo stripslashes($value->banko_kodas); ?></div>
								    	<div class="col-md-2 Antras borderT textRight">Banko Sąskaita: </div>
								    	<div id="banko_saskaita-<?php echo $value->id; ?>" class="col-md-4 Antras borderT" value='<?php echo stripslashes($value->banko_saskaita); ?>'><?php echo stripslashes($value->banko_saskaita); ?></div>
								    	<div class="col-md-2 Pirmas borderT textRight">Telefonas: </div>
								    	<div id="telefonas-<?php echo $value->id; ?>" class="col-md-4 Pirmas borderT" value='<?php echo stripslashes($value->telefonas); ?>'><?php echo stripslashes($value->telefonas); ?></div>
								    	<div class="col-md-2 Pirmas borderT textRight">El. Paštas: </div>
								    	<div id="elpastas-<?php echo $value->id; ?>" class="col-md-4 Pirmas borderT" value='<?php echo stripslashes($value->elpastas); ?>'><?php echo stripslashes($value->elpastas); ?></div>
								    	<div class="col-md-12 Antras borderT textCenter"><b>Adresas: </b>
								    		<span id="salis-<?php echo $value->id; ?>" value='<?php echo stripslashes($value->salis); ?>'><?php echo stripslashes($value->salis); ?></span>, 
								    		<span id="miestas-<?php echo $value->id; ?>" value='<?php echo stripslashes($value->miestas); ?>'><?php echo stripslashes($value->miestas); ?></span>, 
								    		<span id="gatve-<?php echo $value->id; ?>" value='<?php echo stripslashes($value->gatve); ?>'><?php echo stripslashes($value->gatve); ?></span>, 
								    		<span id="namo_nr-<?php echo $value->id; ?>" value='<?php echo stripslashes($value->namo_nr); ?>'><?php echo stripslashes($value->namo_nr); ?></span>, 
								    		<span id="pasto_kodas-<?php echo $value->id; ?>" value='<?php echo stripslashes($value->pasto_kodas); ?>'><?php echo stripslashes($value->pasto_kodas); ?></span>
								    	</div>
									</div>
								</td>
						    </tr>
					
						    
				<?php	}
				} else {
					?><div class="w-100 alert alert-danger" role="alert">Nerasta duomenų</div><?php
				}
				?>
			  </tbody>
			</table>
			<div class="w-100 row">
				<div class="col-md-4">
					<form style="float:left;" method="post" id="trrr" class="row">
						<input type="button" style="margin: 0px 5px 0px 15px;" id="pazymeti_visus_tiekejus" class="btn btn-primary btn-sm" name="trinti_irasus_tiekejai" value="Pažymėtus Visus">
						<input type="text" name="trinti_zymetus_id" id="tzid" value="" style="display: none;">
						<input type="submit" id="trr" class="btn btn-primary btn-sm btn-danger" value="Trinti Pažymėtus">
					</form>
					<form style="float:left;" method="post">
						<select class="form-select" name="items_per_page" style="margin: 0px 0px 0px 20px;" onchange="this.form.submit()">
							<?php echo $classDo->form_Items_Per_Page(); ?>
						</select>
					<form>
				</div>
				<div class="col-md-4 justify-content-center row">
					<?php
						if( is_array( $pages ) ) {
					        $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
					        echo '<nav aria-label="Page navigation example">
					        		<ul class="pagination">';
					        foreach ( $pages as $page ) {
					                echo '<li class="page-item">'.str_replace('page-numbers', 'page-link', $page)."</li>";
					        }
					        echo '</ul></nav>';
					    }
					?>
				</div>
				<div class="col-md-4"></div>
			</div>
			<?php
		}

		private function get_Form_View() {
			global $classDo;			 
			if (isset($_POST) && isset($classDo->post_errors['imone'])) { echo '<div class="alert alert-danger" role="alert">'.$classDo->post_errors['imone'].'</div>'; } 
			if (isset($_POST) && isset($classDo->post_errors['kodas'])) { echo '<div class="alert alert-danger" role="alert">'.$classDo->post_errors['kodas'].'</div>'; }
			if (isset($_POST) && $classDo->post_true == 1) { echo '<div class="alert alert-success" role="alert">Informacija sėkmingai išsaugota!</div>'; }
			if (isset($_POST['tiekejas_delte_id']) && $classDo->delete_true > 0) { echo '<div class="alert alert-success" role="alert">Tiekėjas ištrintas!</div>'; } 
			elseif (isset($_POST['tiekejas_delte_id']) && $classDo->delete_true == 0) { echo '<div class="alert alert-danger" role="alert">Ištrinti nepavyko!</div>'; } 
			if (isset($_POST) && $classDo->post_true == 1) { echo '<div class="alert alert-success" role="alert">Informacija sėkmingai išsaugota!</div>'; }
			if (isset($_POST['trinti_zymetus_id']) && $classDo->delete_true > 0) { echo '<div class="alert alert-success" role="alert">Tiekėjai ištrinti!</div>'; } 
			elseif (isset($_POST['trinti_zymetus_id']) && $classDo->delete_true == 0) { echo '<div class="alert alert-danger" role="alert">Ištrinti nepavyko!</div>'; } ?>

			<div class="col-md-12" id="topForma"></div> 
			<div class="col-md-12 itri w-100 marginTop20 open-forma" id="open-forma" style="cursor: pointer;">Įtraukti naują įmonę</div>
			<form method="post" class="dAntras nauja-imone-forma" style="display: <?php if (isset($_POST) && !empty($classDo->post_errors)) { echo 'block'; } else { echo 'none'; } ?>" id="naujos-imones-forma">
				<div class="w-100 form-row">
					<div class="form-group col-md-4">
						<input type="text" class="form-control" id="imones_pavadinimas" name="imones_pavadinimas" placeholder="Įmonės Pavadinimas" value="<?php if (isset($_POST) && !empty($classDo->post_errors)) { echo $classDo->test_input($_POST['imones_pavadinimas']); } ?>">
					</div>
					<div class="form-group col-md-4">
						<input type="text" class="form-control" id="imones_kodas" name="imones_kodas" placeholder="Įmonės Kodas" value="<?php if (isset($_POST) && !empty($classDo->post_errors)) { echo $classDo->test_input($_POST['imones_kodas']); } ?>">
					</div>
					<div class="form-group col-md-4">
						<input type="text" class="form-control" id="pvm_kodas" name="pvm_kodas" placeholder="PVM kodas"  value="<?php if (isset($_POST) && !empty($classDo->post_errors)) { echo $classDo->test_input($_POST['pvm_kodas']); } ?>">
					</div>
				</div>
				<div class="w-100">Banko Duomenys</div>
				<div class="w-100 form-row">
					<div class="form-group col-md-6">
						<input type="text" class="form-control" id="banko_kodas" name="banko_kodas" placeholder="Banko Kodas" value="<?php if (isset($_POST) && !empty($classDo->post_errors)) { echo $classDo->test_input($_POST['banko_kodas']); } ?>">
					</div>
					<div class="form-group col-md-6">
						<input type="text" class="form-control" id="banko_saskaita" name="banko_saskaita" placeholder="Banko Sąskaita" value="<?php if (isset($_POST) && !empty($classDo->post_errors)) { echo $classDo->test_input($_POST['banko_saskaita']); } ?>">
					</div>
				</div>
				<div class="w-100">Kontaktiniai duomenys</div>
				<div class="w-100 form-row">
					<div class="form-group col-md-6">
						<input type="text" class="form-control" id="telefonas" name="telefonas" placeholder="Telefonas" value="<?php if (isset($_POST) && isset($classDo->post_errors)) { echo $classDo->test_input($_POST['telefonas']); } ?>">
					</div>
					<div class="form-group col-md-6">
						<input type="text" class="form-control" type="email" id="elpastas" name="elpastas" placeholder="Eliektronis Paštas" value="<?php if (isset($_POST) && !empty($classDo->post_errors)) { echo $classDo->test_input($_POST['elpastas']); } ?>">
					</div>
				</div>
				<div class="w-100">Adresas</div>
				<div class="w-100 form-row">
					<div class="form-group col-md-6">
						<input type="text" class="form-control" id="salis" placeholder="Šalis" name="salis" value="<?php if (isset($_POST) && !empty($classDo->post_errors)) { echo $classDo->test_input($_POST['salis']); } ?>">
					</div>
					<div class="form-group col-md-6">
						<input type="text" class="form-control" id="miestas" placeholder="Miestas" name="miestas" value="<?php if (isset($_POST) && !empty($classDo->post_errors)) { echo $classDo->test_input($_POST['miestas']); } ?>">
					</div>
				</div>
				<div class="w-100 form-row">
					<div class="form-group col-md-4">
						<input type="text" class="form-control" id="gatve" name="gatve" placeholder="Gatve" value="<?php if (isset($_POST) && !empty($classDo->post_errors)) { echo $classDo->test_input($_POST['gatve']); } ?>">
					</div>
					<div class="form-group col-md-4">
						<input type="text" class="form-control" id="namo_nr" name="namo_nr" placeholder="Namo numeris / Butas"
						<?php if (isset($_POST) && !empty($classDo->post_errors)) { echo 'value="'.$classDo->test_input($_POST['namo_nr']).'"'; } ?> >
					</div>
					<div class="form-group col-md-4">
						<input type="text" class="form-control" id="pasto_kodas" name="pasto_kodas" placeholder="Pašto Kodas"
						<?php if (isset($_POST) && !empty($classDo->post_errors)) { echo 'value="'.$classDo->test_input($_POST['pasto_kodas']).'"'; } ?> >
					</div>
				</div>
				<div class="w-100">
					<input type="submit" class="btn btn-primary btn-sm" value="Išsaugoti" id="saugotiTiekeja"> <input class="btn btn-primary btn-sm btn-danger" type="button" value="Išvalyti Formą" id="valytiForma">
				</div>
			</form>

			<?php
		}



	}
?>