<?php
	class class_Operaciju_Zurnalas_View {

		function __construct() {
			$this->add_Saskaita_Form_View();
			$this->view_Orders();
		}

		private function view_Orders() {
			global $classDB;
			$orders = $classDB->get_Last_Orders(10);
			if ($orders) {
				$this->construct_Saskaitu_View($orders);	
			}
		}

		private function construct_Saskaitu_View($orders) {
			global $classDo;
			?><div class="w-100 ppa">
				<div class="row">
					<div class="col-6"><div class="w-100"><b>IŠRAŠYTOS SĄSKAITOS</b></div><table class="table"><thead class="thead-light"><tr><th scope="col">ID</th><th scope="col">Būsena</th><th scope="col">Gauta Pajamų</th><th scope="col">Sąskaita</th></tr></thead><tbody><?php
						foreach ($orders as $key => $value) {
							$order = wc_get_order( $value );
							$order_data = $order->get_data();
							global  $woocommerce;
							?><tr class='pirkejoDuomenys'>
							    <th scope="row"><?php echo $value; ?></th>
							    <td><?php echo $order->get_status(); ?></td>
							    <td><?php echo $order->get_total().' '.get_woocommerce_currency_symbol();; ?></td>
							    <td><i class="fa fa-file-pdf-o" aria-hidden="true"></i></td>
							</tr>
								<tr class="hiddePirkejas">
							    <td colspan="4">
							    <div class="dPirmas"><b>Sukūrimo Data:</b> <?php echo $order_data['date_created']->date('Y-m-d h:i:s'); ?> </div>
							    <div class="dAntras"><b>Pristatymo kaina:</b> <?php echo $order_data['shipping_total'].' '.get_woocommerce_currency_symbol(); ?></div>
							    <div class="dPirmas"><b>Užsakovo duomenys:</b> <?php echo $classDo->get_Pirkejo_Data($order, $order_data); ?></div></td>
							</tr>
							 <?php	
						}
					?></tbody>
					</table>
					</div>
					<div class="col-6">
						<?php if (!empty($classDo->delete_true)) { echo '<div class="alert alert-success" role="alert">'.$classDo->delete_true.'!</div>'; } ?>
						<?php if (!empty($classDo->delete_false)) { echo '<div class="alert alert-danger" role="alert">'.$classDo->delete_false.'!</div>'; } ?>
						<div class="col-md-12 w-100 skttop" id="tdivform"><span class="float-left">Įtraukti naują sąskaitą</span><span class="float-right sktta">🢓</span></div>
						<?php $this->new_Form_Gauta_Saskaita(); ?>
						<div class="w-100 ttable"><b>GAUTOS SĄSKAITOS</b></div>
						<table class="table table-bordered">
						    <thead class="thead-light">
							    <tr>
							        <th scope="col">#</th>
							        <th scope="col" class="text-center" style="width: 110px;">GAUTA</th>
							        <th scope="col" class="text-center">SASKAITOS NR.</th>
							        <th scope="col" class="text-center">SUMA BE PVM</th>
							        <th scope="col" class="text-center">PVM</th>
							        <th scope="col" class="text-center">APMOKĖTA</th>
							    </tr>
						    </thead>
						    <tbody>
							    <?php $classDo->construct_table_gautos_saskaitos(); ?>
						    </tbody>
						</table>
					</div>

			 	</div>
			</div><?php
		}

		private function add_Saskaita_Form_View() {
			global $classDo;
			$data = $classDo->get_Last_Date_Of_Inserted_Orders();
				?> <div class="insertSaskaitas">
					<form  method="post">
						<label>Data nuo:</label>
						<input type="text" id="dataNuo" name="dataNuo" value="<?php echo $data; ?>">&nbsp;&nbsp;
						<label>Data iki:</label>
						<input type="text" id="dataIki" name="dataIki" value="<?php echo date('Y-m-d'); ?>">&nbsp;&nbsp;
						<input type="submit" value="Įtraukti sąskaitas pagal datą">
					</form>
				</div> <?php
		}

		private function new_Form_Gauta_Saskaita() {
			global $classDo;
			?>
			<div class="col-md-12 dss w-100">
				<form method="post" class="w-100 dss" id="nauja-saskaita" style="display: <?php $classDo->display(); ?>;">
					<div class="col-md-12 w-100 form-row" style="padding-top: 15px;"> 
						<div class="form-group col-md-12">
							<input type="text" class="form-control" id="israsymo_data" name="israsymo_data" autocomplete="off" placeholder="Sąskaitos Išrašymo Data" value="<?php if (isset($_POST) && !empty($classDo->post_errors)) { echo $classDo->test_input($_POST['israsymo_data']); } ?>">
							<?php $classDo->error_form('israsymo_data'); ?>
						</div>
						<div class="form-group col-md-6">
							<input type="text" class="form-control" id="israsymo_suma_be_pvm" name="israsymo_suma_be_pvm" placeholder="Pinigų Suma Be PVM"  value="<?php if (isset($_POST) && !empty($classDo->post_errors)) { echo $classDo->test_input($_POST['israsymo_suma_be_pvm']); } ?>">
							<?php $classDo->error_form('israsymo_suma_be_pvm'); ?>
						</div>
						<div class="form-group col-md-6">
							<input type="text" class="form-control" id="israsymo_suma_su_PVM" name="israsymo_suma_su_PVM" placeholder="Pinigų Suma Su PVM" value="<?php if (isset($_POST) && !empty($classDo->post_errors)) { echo $classDo->test_input($_POST['israsymo_suma_su_PVM']); } ?>">
							<?php $classDo->error_form('israsymo_suma_su_PVM'); ?>
						</div>
						<div class="form-group col-md-6">
							<input type="text" class="form-control" id="saskaitos_nr" name="saskaitos_nr" placeholder="Sąskaitos faktūros numeris"  value="<?php if (isset($_POST) && !empty($classDo->post_errors)) { echo $classDo->test_input($_POST['saskaitos_nr']); } ?>">
							<?php $classDo->error_form('saskaitos_nr'); ?>
						</div>
						<div class="form-group col-md-6">
							<select class="form-control" id="apmokejimas" name="apmokejimas">
						    	<option value="0">Neapmokėta</option>
						    	<option value="1">Apmokėta</option>
						    </select>
						</div>
						<div class="form-group col-md-12"> 
							<select class="selectpicker form-control" name="tiekejas" id="tiekejas" data-live-search="true">
								<option value="tt">Pasirinkti tiekėją</option>
								<?php $classDo->construct_tiekejo_select();	?>
							</select>
							<?php $classDo->error_form('tiekejas'); ?>
						</div>
						<div class="col-md-12 dee">
							<input type="submit" value="Išsaugoti" class="btn btn-primary btn-sm">
							<input class="btn btn-primary btn-sm btn-danger" type="button" value="Išvalyti Formą" id="valytiSaskaitat">
							<label style="display: none;" id="styleID"></label>
							<input type="text" name="rsaskaitos_id" value="" id="rsaskaitos_id" style="display: none;">
						</div>
					</div>
				</form>
			</div>
			<?php
		}

	}