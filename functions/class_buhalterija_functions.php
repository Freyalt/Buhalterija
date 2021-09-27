<?php
	class class_buhalterija_functions {
		public $post_errors, $post_true, $delete_true, $delete_false;

		function __construct() {
			if ($_POST) { $this->request_Method_Post(); }
		}
		private function request_Method_Post() {
			global $classDB;
			if (isset($_POST['dataNuo']) && isset($_POST['dataIki'])) {
				$this->write_Sold_Data_to_DB();
			} elseif (isset($_POST['imones_pavadinimas']) && !isset($_POST['id'])) {
				$this->write_nauja_imone_to_DB();
			} elseif (isset($_POST['imones_pavadinimas']) && isset($_POST['id'])) {
				$this->edit_Tiekejas_byID();
			} elseif (isset($_POST['tiekejas_delte_id'])) {
				$this->delete_Tiekejas();
			} elseif (isset($_POST['items_per_page'])) {
				$classDB->edit_Items_Per_Page_DB();
			} elseif (isset($_POST['trinti_zymetus_id'])) {
				$this->delete_true = $classDB->mass_Delete_Tiekejas();
			} elseif (isset($_POST['israsymo_data']) && empty($_POST['rsaskaitos_id'])) {
				$this->nauja_Tiekejo_Saskaita();
			} elseif (isset($_POST['trintiSaskaita']) && is_numeric($_POST['trintiSaskaita'])) {
				$this->trinti_saskaita();
			} elseif (isset($_POST['israsymo_data']) && !empty($_POST['rsaskaitos_id'])) {
				$this->edit_saskaita();
			}
		}

		private function edit_saskaita() {
			if (empty($_POST['israsymo_data'])) {
				$errors['israsymo_data'] = 'Būtina pasirinkti datą!';
			} 
			if (empty($_POST['israsymo_suma_be_pvm'])) {
				$errors['israsymo_suma_be_pvm'] = 'Būtina nurodyti sumą be PVM!';
			} elseif (!is_numeric($_POST['israsymo_suma_be_pvm'])) {
				$errors['israsymo_suma_be_pvm'] = 'Suma be PVM turi būti skaitmuo!';
			}
			if ($_POST['tiekejas'] == "tt") {
				$errors['tiekejas'] = 'Būtina nurodyti tiekėją!';
			}
			if (empty($_POST['saskaitos_nr'])) {
				$errors['saskaitos_nr'] = 'Būtina nurodyti sąskaitos numerį!';	
			}

			if (!is_numeric($_POST['israsymo_suma_su_PVM']) && !empty($_POST['israsymo_suma_su_PVM'])) {
				$errors['israsymo_suma_su_PVM'] = 'Suma su PVM turi būti skaitmuo!';
			} elseif (!empty($_POST['israsymo_suma_su_PVM']) && !empty($_POST['israsymo_suma_be_pvm']) && is_numeric($_POST['israsymo_suma_su_PVM']) && is_numeric($_POST['israsymo_suma_be_pvm']))  {
				if (((float)$_POST['israsymo_suma_su_PVM']) <= ((float)$_POST['israsymo_suma_be_pvm'])) {
					$errors['israsymo_suma_su_PVM'] = 'Suma su PVM turi būti didesnė už sumą be PVM!';	
				}
			}
			if (!empty($errors)) {
				$this->post_errors = $errors;
			} else {
				global $classDB;
				$edit =  $classDB->update_saskaita();
				if (!empty($edit)) {
					$this->delete_true = 'Sąskaita sėkmnigai redaguota';
				} else {
					$this->delete_false = 'Sąskaitos redaguoti nepavyko, duomenys nepakeist, arba kita sitemos klaida';
				}
			}
		}

		private function trinti_saskaita() {
			$where = array('id' => $_POST['trintiSaskaita']);
			$format = '%d';
			global $classDB;
			$delete_true = $classDB->delete_from_DB('buhalterija_gautos_saskaitos', $where, $format);
			if (!empty($delete_true)) {
				$this->delete_true = 'Sąskaita sėkmnigai ištrinta';
			} else {
				$this->delete_false = 'Sąskaitos ištrinti nepavyko';
			}
		}
 
		private function nauja_Tiekejo_Saskaita() {
			if (empty($_POST['israsymo_data'])) {
				$errors['israsymo_data'] = 'Būtina pasirinkti datą!';
			} 
			if (empty($_POST['israsymo_suma_be_pvm'])) {
				$errors['israsymo_suma_be_pvm'] = 'Būtina nurodyti sumą be PVM!';
			} elseif (!is_numeric($_POST['israsymo_suma_be_pvm'])) {
				$errors['israsymo_suma_be_pvm'] = 'Suma be PVM turi būti skaitmuo!';
			}
			if ($_POST['tiekejas'] == "tt") {
				$errors['tiekejas'] = 'Būtina nurodyti tiekėją!';
			}
			if (empty($_POST['saskaitos_nr'])) {
				$errors['saskaitos_nr'] = 'Būtina nurodyti sąskaitos numerį!';	
			}

			if (!is_numeric($_POST['israsymo_suma_su_PVM']) && !empty($_POST['israsymo_suma_su_PVM'])) {
				$errors['israsymo_suma_su_PVM'] = 'Suma su PVM turi būti skaitmuo!';
			} elseif (!empty($_POST['israsymo_suma_su_PVM']) && !empty($_POST['israsymo_suma_be_pvm']) && is_numeric($_POST['israsymo_suma_su_PVM']) && is_numeric($_POST['israsymo_suma_be_pvm']))  {
				if (((float)$_POST['israsymo_suma_su_PVM']) <= ((float)$_POST['israsymo_suma_be_pvm'])) {
					$errors['israsymo_suma_su_PVM'] = 'Suma su PVM turi būti didesnė už sumą be PVM!';	
				}
			}
		
			if(!empty($errors)) {
				$this->post_errors = $errors;
			} else {
				global $wpdb;
				if ((float)$_POST['israsymo_suma_su_PVM'] > (float)$_POST['israsymo_suma_be_pvm']) {
					$pvm = (float)$_POST['israsymo_suma_su_PVM'] - (float)$_POST['israsymo_suma_be_pvm'];
				} else {
					$pvm = 0;
				}
				$wpdb->insert($wpdb->prefix.'buhalterija_gautos_saskaitos', array('data' => $_POST['israsymo_data'], 'sume_be_pvm' => $_POST['israsymo_suma_be_pvm'], 'suma_su_pvm' => $_POST['israsymo_suma_su_PVM'], 'pvm' => $pvm, 'saskaitos_nr' => $_POST['saskaitos_nr'],  'tiekejas' => $_POST['tiekejas'], 'apmokejimas' => $_POST['apmokejimas']), array('%s', '%s', '%s', '%s', '%s', '%s', '%s'));
			}
						
		}

		public function construct_table_gautos_saskaitos() {
			global $classDB;
			$array = $classDB->get_Results('buhalterija_gautos_saskaitos ORDER BY id DESC');
			if (!empty($array)) {
				$i = 0;
				foreach ($array as $key => $value) {
					$arrayTiekejas = $classDB->get_Results_Where('buhalterija_tiekejai', 'imones_pavadinimas', 'id', (int)$value->tiekejas);
					?>
					<tr class="saskaitosSp" name="<?php echo $value->id; ?>" style="cursor: pointer;">
				        <th scope="row" style="display: table-cell; vertical-align: middle;"><div><input class="form-check-input input cb" type="checkbox" value="" name="<?php echo $value->id; ?>"></div></th>
				        <td id="israsymo_data-<?php echo $value->id; ?>" value="<?php echo $value->data; ?>"><?php echo $value->data; ?></td>
				        <td class="text-center" id="saskaitos_nr-<?php echo $value->id; ?>" value="<?php echo $value->saskaitos_nr; ?>"><?php echo $value->saskaitos_nr; ?></td>
				        <td class="text-center" id="suma_be_pvm-<?php echo $value->id; ?>" value="<?php echo $value->sume_be_pvm; ?>"><?php echo $value->sume_be_pvm; ?> €</td>
				        <td class="text-center" id="pvm-<?php echo $value->id; ?>" value="<?php echo $value->pvm; ?>"><?php echo $value->pvm; ?> €</td>
				        <td class="text-center" id="apmokejimas-<?php echo $value->id; ?>" value="<?php echo $value->apmokejimas ?>"><?php echo $this->construct_apmoketa($value->apmokejimas) ?></td>
				    </tr>
				    <tr>
				    	<td colspan="6" style="padding: 0px"> 
				    		<div class="w-100 row" id="saskaitosRd-<?php echo $value->id; ?>" style="padding: 0px;margin: 0px 0px 0px 0px; display: none;">
				    			<div class="col-sm-2 gSas text-right"><b>TIEKĖJAS:</b></div><div class="col-sm-10 gSas" id="imones_pavadinimas-<?php echo $value->id; ?>" value="<?php echo $value->tiekejas; ?>"><?php echo stripcslashes($arrayTiekejas[0]->imones_pavadinimas); ?></div>
				    			<div class="col-sm-6 gSas2 text-right">
				    				<button class="input redaguotiSaskaita btn btn-primary btn-sm" name="<?php echo $value->id; ?>"><i class="fa fa-newspaper-o"> Redaguoti</i></button>
				    			</div><div class="col-sm-6 gSas2">
				    				<form method="post" id="trintiSaskaita-<?php echo $value->id; ?>"><button type="submit" class="input trintiSaskaita btn btn-primary btn-sm btn-danger" numeris="<?php echo $value->saskaitos_nr; ?>" name="<?php echo $value->id; ?>" ><i class="fa fa-trash"> Trinti</i></button> <input type="text" name="trintiSaskaita" value="<?php echo $value->id; ?>" style="display: none;"></form>
				    			</div>
				    		</div>
				    	</td>
				    </tr>
				    <?php
				}
			}
		}

		private function construct_apmoketa($value) {
			if ($value == 1) {
				echo '<span class="badge badge-success">Taip</span>';
			} else {
				echo '<span class="badge badge-danger">Ne</span>';
			}
		}

		public function form_Items_Per_Page() {
			global $buhalterija_settings;
			$array = array(10, 20, 30, 50, 100);
			$return = '';
			if (!empty($buhalterija_settings->items_per_page)) {
				foreach ($array as $value) {
					if ($buhalterija_settings->items_per_page == $value) {
						$return .= '<option selected>'.$value.'</option>';
					} else {
						$return .= '<option value="'.$value.'">'.$value.'</option>';
					}
				}
				
			} else {
				$return =  '<option  selected>10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="50">50</option>
							<option value="100">50</option>';	
			}
			return $return;				
		}

		public function get_Imoniu_Sarasas() {
			global $classDB;
			if (isset($_POST['paieska_imones_pavadinimas']) || isset($_POST['paieska_pvm_kodas']) || isset($_POST['paieska_imones_kodas'])) {
				if (!empty($_POST['paieska_imones_pavadinimas'])) {
					$whereArray['imones_pavadinimas'] = $classDB->test_input($_POST['paieska_imones_pavadinimas']);
				}
				if (!empty($_POST['paieska_pvm_kodas'])) {
					$whereArray['pvm_kodas'] = $classDB->test_input($_POST['paieska_pvm_kodas']);
				}
				if (!empty($_POST['paieska_imones_kodas'])) {
					$whereArray['imones_kodas'] = $classDB->test_input($_POST['paieska_imones_kodas']);
				}
				if (!empty($whereArray)) {
					return $classDB->get_Where_Array('*', 'buhalterija_tiekejai', $whereArray);
				} else {
					return $classDB->get_Results("buhalterija_tiekejai");
				}
				
			} else {
				return $classDB->get_Results_Pagination("buhalterija_tiekejai");
			}
		}

		private function delete_Tiekejas() {
			if (!empty($_POST['tiekejas_delte_id'])) {
				$where = array('id' => $_POST['tiekejas_delte_id']);
				$format = '%d';
				global $classDB;
				$this->delete_true = $classDB->delete_from_DB('buhalterija_tiekejai', $where, $format);
			}
		}

		private function edit_Tiekejas_byID() {
			if (empty($_POST['imones_pavadinimas'])) { $errors['imone'] = 'Privaloma įvesti įmonės pavadinimą'; }
			if (empty($_POST['imones_kodas'])) { $errors['kodas'] = 'Privaloma įvesti įmonės kodą'; }
			if (!empty($errors)) {
				$this->post_errors = $errors;
			} else {
				global $classDB;
				$this->post_true = $classDB->update_Tiekeja();
			}
		}

		private function write_nauja_imone_to_DB() {
			if (empty($_POST['imones_pavadinimas'])) { $errors['imone'] = 'Privaloma įvesti įmonės pavadinimą'; }
			if (empty($_POST['imones_kodas'])) { $errors['kodas'] = 'Privaloma įvesti įmonės kodą'; }
			if (!empty($errors)) {
				$this->post_errors = $errors;
			} else {
				global $classDB;
				$this->post_true = $classDB->insert_Tiekeja();
			}
		}

		function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}






		

		private function write_Sold_Data_to_DB() {
			if (DateTime::createFromFormat('Y-m-d', $_POST['dataNuo']) !== false && DateTime::createFromFormat('Y-m-d', $_POST['dataIki'])) {
				global $classDB;
				$classDB->db_Insert_Update_Nuskaitymas($_POST['dataIki']);
				$wcOrders = $classDB->get_Paid_Orders($_POST['dataNuo'], $_POST['dataIki']);
				$wcOrdersFiltered;
				foreach ($wcOrders as $key => $value) {
					$order = $value->ID;
					$buhalterijaOrder = Null;
					$buhalterijaOrder = $classDB->get_Results_Where($classDB->db_Saskaitos['name'], '*', 'uzsakymo_id', $order);
					$order = wc_get_order( $value->ID );
					$order_data = $order->get_data();

					if ( $buhalterijaOrder != Null ) {
						$orderin = 0;
						$refunded_id = 0;
						foreach ($buhalterijaOrder as $key => $valuee) {
							if ($valuee->pardavimo_data == $order_data['date_modified']->date('Y-m-d h:i:s')) {
								$orderin = 1;
							}
							if ($valuee->saskaitos_tipas == 'refunded') {
								$refunded_id = 1;
							}
						}
						if( $orderin == 0 && $order->get_status() == 'refunded' && $refunded_id == 0 ) {
							$return = $this->construct_Saskaita_Data($order, $order_data);
							$classDB->db_Insert_Saskaitos($value->ID, $return['data'], '', $return['tipas'], $return['kodas'], $return['pirkejas'], $return['pinigai']);
						} 

					} else {
						$return = $this->construct_Saskaita_Data($order, $order_data);
						$classDB->db_Insert_Saskaitos($value->ID, $return['data'], '', $return['tipas'], $return['kodas'], $return['pirkejas'], $return['pinigai']);
					}

				}

			}
		}

		private function construct_Saskaita_Data($order, $order_data) {
			$pirkejas = $order_data['billing'];
			$pinigai = $order->get_total();
			$tipas = $order->get_status();
			if ($tipas == 'refunded') {
				$kodas = 5091;
			} elseif ($tipas == 'completed') {
				$kodas = 500;	
			}
			$data = $order_data['date_modified']->date('Y-m-d h:i:s');
			$pristatymas = $order_data['shipping_total'];
			$pirkejas = $this->construct_Pirkejas($pirkejas);
			return array('pirkejas' => $pirkejas, 'pinigai' => $pinigai, 'kodas' => $kodas, 'tipas' => $tipas, 'data' => $data, 'pristatymas' => $pristatymas);
		}

		public function get_Pirkejo_Data($order, $order_data) {
			if ($order->get_parent_id()) {
					$parent_order = wc_get_order($order->get_parent_id());
					$parent_order_data = $parent_order->get_data();
					$pirkejas = $parent_order_data['billing'];
					return $this->construct_Pirkejas($pirkejas);
			} else {
				$pirkejas = $order_data['billing'];
				return $this->construct_Pirkejas($pirkejas);
			}
		}

		private function construct_Pirkejas($pirkejas) {
			foreach ($pirkejas as $key => $value) {
				if ($key == 'first_name') {
					$p = $value;
				} elseif ($key == 'last_name') {
					$p = "$p $value";
				} elseif ($value) {
					$p = "$p, $value";
				}
			}
			return $p;
		}

		public function get_Last_Date_Of_Inserted_Orders() {
			global $classDB;
			$data = $classDB->get_Last_Insert_DateID();
			if (!$data) {
				$data = '0000-00-00';
			} else {
				$data = $data[0]->last_read_date;
			}
			return $data;
		}

		public function pre($data) {
			echo '<pre>';
				print_r($data);
			echo '</pre>';
		}

		public function display() {
			if(isset($_POST['israsymo_data']) && !empty($this->post_errors)) { 
				echo 'block'; 
			} else { 
				echo 'none';
			}
		}

		public function construct_tiekejo_select() {
			global $classDB;
			$arrayTiekejai = $classDB->get_Results('buhalterija_tiekejai');
			if (!empty($arrayTiekejai)) {
				foreach ($arrayTiekejai as $key => $value) {
					if (isset($_POST['tiekejas']) && $_POST['tiekejas'] != 'tt') {
						if ($value->id == $_POST['tiekejas']) {
							echo '<option selected value="'.$value->id.'">'.stripslashes($value->imones_pavadinimas).'</option>';
						} else {
							echo '<option value="'.$value->id.'">'.stripslashes($value->imones_pavadinimas).'</option>';
						}
					} else {
						echo '<option value="'.$value->id.'">'.stripslashes($value->imones_pavadinimas).'</option>';
					}
				}
			}
		}

		public function error_form($error) {
			if (!empty($this->post_errors[$error])) { 
				echo '<label class="ered">'.$this->post_errors[$error].'</label>'; 
			}
		}

	}
?>