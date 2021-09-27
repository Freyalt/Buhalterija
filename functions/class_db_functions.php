<?php
	class class_db_functions {
		public $db_Nustatymai = array('name' => 'buhalterija_nustatymai', 'table' => array('id', 'pardavimu_nuskaitymas'));
		public $db_Saskaitos = array('name' => 'buhalterija_saskaitos', 'table' => array('id', 'uzsakymo_id', 'pardavimo_data', 'saskaitos_nr', 'saskaitos_tipas', 'saskaitos_kodas', 'pirkejas', 'pinigai'));
		public $db_Nuskaityta = array('name' => 'buhalterija_r_d', 'table' => array('id', 'last_read_date'));

		public function mass_Delete_Tiekejas() {
			global $wpdb;
			global $classDo;
			$ids = $this->test_input($_POST['trinti_zymetus_id']);
			return $wpdb->query( "DELETE FROM $wpdb->prefix".'buhalterija_tiekejai'." WHERE ID IN($ids)" );
            
		}

		public function edit_Items_Per_Page_DB() {
			global $wpdb;
			$wpdb->update($wpdb->prefix.'buhalterija_global_settings', array('items_per_page' => $_POST['items_per_page']), array('id'=>1), array('%d'));
		}

		public function get_Results($table) {
			global $wpdb;
			return $wpdb->get_results("SELECT * FROM $wpdb->prefix$table");
		}

		public function get_Results_Pagination($table) {
			global $wpdb, $buhalterija_settings;

			$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
			$limit = $buhalterija_settings->items_per_page;
			$offset = ( $pagenum - 1 ) * $limit;
			$total = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->prefix$table");
			$num_of_pages = ceil( $total / $limit );

			$query_Data = $wpdb->get_results("SELECT * FROM $wpdb->prefix$table ORDER BY id DESC LIMIT $offset, $limit", OBJECT );
			$rowcount = $wpdb->num_rows; 

			$page_links = paginate_links( array(
	            'base' => add_query_arg( 'pagenum', '%#%' ),
	            'format' => '',
	            'prev_text' => __( '&laquo;', 'text-domain' ),
	            'next_text' => __( '&raquo;', 'text-domain' ),
	            'total' => $num_of_pages,
	            'mid_size' => 6,
	            'type' => 'array',
	            'current' => $pagenum
	        ));
			return array('query_Data' => $query_Data, 'page_links' => $page_links);

		}

		public function get_Where_Array($selectArray, $table, $whereArray) {
			if (is_array($selectArray)) {
				foreach ($selectArray as $key => $value) {
					if ($key == 0) {
						$select = $value;
					} else {
						$select .= ', '.$value;
					}
				}
			} else {
				$select = '*';
			}
			$i = 0;
			if (is_array($whereArray)) {
				foreach ($whereArray as $key => $value) {
					if ($i == 0) {
						$where = $key.' LIKE "%'.$value.'%"';
					} else {
						$where .= ', '.$key.' LIKE "%'.$value.'%"';
					}
					$i++;
				}
			}
			global $wpdb;
			return $wpdb->get_results('SELECT '.$select.' FROM '.$wpdb->prefix.$table.' WHERE '.$where, OBJECT);
		}

		public function delete_from_DB($table, $where, $format) {
			global $wpdb;
			return $wpdb->delete($wpdb->prefix.$table, $where, $format);
		}

		public function update_saskaita() {
			$pvm = (float)$_POST['israsymo_suma_su_PVM'] - (float)$_POST['israsymo_suma_be_pvm'];
			$array1 = array(
					'data' => $_POST['israsymo_data'],
				    'sume_be_pvm' => $_POST['israsymo_suma_be_pvm'],
				    'suma_su_pvm' => $_POST['israsymo_suma_su_PVM'],
				    'pvm' => $pvm,
				    'saskaitos_nr' => $_POST['saskaitos_nr'],
				    'apmokejimas' => $_POST['apmokejimas'],
				    'tiekejas' => $_POST['tiekejas']);
			$array2 = array('id' => $_POST['rsaskaitos_id']);
			$array3 = array('%s', '%s', '%s', '%s', '%s', '%s');
			$array4 = array('%d');
			global $wpdb;
			return $wpdb->update($wpdb->prefix.'buhalterija_gautos_saskaitos', $array1 , $array2, $array3, $array4);
		}

		public function update_Tiekeja() {
			$array1 = array(
					'imones_pavadinimas' => $_POST['imones_pavadinimas'],
				    'salis' => $_POST['salis'],
				    'miestas' => $_POST['miestas'],
				    'gatve' => $_POST['gatve'],
				    'namo_nr' => $_POST['namo_nr'],
				    'pasto_kodas' => $_POST['pasto_kodas'],
				    'telefonas' => $_POST['telefonas'],
				    'elpastas' => $_POST['elpastas'],
				    'imones_kodas' => $_POST['imones_kodas'],
				    'pvm_kodas' => $_POST['pvm_kodas'],
				    'banko_kodas' => $_POST['banko_kodas'],
				    'banko_saskaita' => $_POST['banko_saskaita']);
			$array2 = array('id' => $_POST['id']);
			$array3 = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');
			$array4 = array('%d');
			global $wpdb;
			$wpdb->update($wpdb->prefix.'buhalterija_tiekejai', $array1 , $array2, $array3, $array4);
			return 1;
		}

		public function insert_Tiekeja() {
			global $wpdb;
			$wpdb->insert($wpdb->prefix.'buhalterija_tiekejai', array(
						    'imones_pavadinimas' => $_POST['imones_pavadinimas'],
						    'salis' => $_POST['salis'],
						    'miestas' => $_POST['miestas'],
						    'gatve' => $_POST['gatve'],
						    'namo_nr' => $_POST['namo_nr'],
						    'pasto_kodas' => $_POST['pasto_kodas'],
						    'telefonas' => $_POST['telefonas'],
						    'elpastas' => $_POST['elpastas'],
						    'imones_kodas' => $_POST['imones_kodas'],
						    'pvm_kodas' => $_POST['pvm_kodas'],
						    'banko_kodas' => $_POST['banko_kodas'],
						    'banko_saskaita' => $_POST['banko_saskaita']

			));
			return 1;
		}

		public function get_Last_Orders($limit) {
			$query = new WC_Order_Query( array( 
				'status' => array('wc-completed', 'wc-refunded'), 
				'limit' => $limit, 
				'orderby' => 'date', 
				'order' => 'DESC', 
				'return' => 'ids') );
			return $query->get_orders();
		}


		public function get_Results_Where($from, $getFieldNameOrStar, $byFieldName, $byData) {
			global $wpdb;
			$sql = "SELECT {$getFieldNameOrStar} FROM {$wpdb->prefix}{$from} WHERE {$byFieldName} = %d";
			$sql = $wpdb->prepare( $sql, $byData );
			$results = $wpdb->get_results( $sql );
			return $results;
		}

		public function get_Last_Insert_DateID() {
			global $wpdb;
			return $wpdb->get_results( 'SELECT '. $this->db_Nuskaityta['table'][1] .' FROM ' . $wpdb->prefix . $this->db_Nuskaityta['name'] . ' ORDER BY id DESC LIMIT 1');
		}

		public function get_Paid_Orders($dateStart, $dateEnd) {
			global $wpdb;
			$post_status = implode("','", array('wc-completed', 'wc-refunded') );
			$sql = "SELECT * FROM $wpdb->posts WHERE post_type = 'shop_order' AND post_status IN ('{$post_status}') AND post_date BETWEEN %s AND %s ";
			$sql = $wpdb->prepare($sql, $dateStart, $dateEnd);
			return $wpdb->get_results($sql);
		}

		public function db_Insert_Update_Nuskaitymas($last_read_date) {
	        global $wpdb; 
	        $sql = "INSERT INTO {$wpdb->prefix}{$this->db_Nuskaityta['name']} (id, {$this->db_Nuskaityta['table']['1']}) VALUES (%d, %s) ON DUPLICATE KEY UPDATE {$this->db_Nuskaityta['table']['1']} = %s";
			$sql = $wpdb->prepare($sql, 1, $last_read_date, $last_read_date);
			$wpdb->query($sql);
      	}	

		public function db_Insert_Saskaitos($uzsakymo_id, $data, $nr, $tipas, $kodas, $pirkejas, $pinigai) {
	        global $wpdb; 
	        $data = array(
	      			$this->db_Saskaitos['table']['1'] => $uzsakymo_id,
	      			$this->db_Saskaitos['table']['2'] => $data,
	      			$this->db_Saskaitos['table']['3'] => $nr,
	      			$this->db_Saskaitos['table']['4'] => $tipas,
	      			$this->db_Saskaitos['table']['5'] => $kodas,
	      			$this->db_Saskaitos['table']['6'] => $pirkejas,
	      			$this->db_Saskaitos['table']['7'] => $pinigai
	      	);
	        $wpdb->insert($wpdb->prefix.$this->db_Saskaitos['name'], $data);
      	}

      	function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
    }
?>