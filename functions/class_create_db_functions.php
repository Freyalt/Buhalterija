<?php
	class class_create_db_functions {
		private $prefix;
		private $buhalterija_DB_Version = '1.0';
		private $charset_collate;

		function __construct() {
			global $wpdb;
			$this->prefix = $wpdb->prefix;
			$this->charset_collate = $wpdb->get_charset_collate();
			$this->buhalterija_Create_DB($this->buhalterija_R_D_DB());
			$this->buhalterija_Create_DB($this->buhalterija_Saskaitos_DB());
			$this->buhalterija_Create_DB($this->buhalterija_Global_Settings_DB());
			$this->buhalterija_Create_DB($this->buhalterija_Tiekejai_DB());
			$this->buhalterija_Create_DB($this->saskaitos_kodas());
			$this->buhalterija_Create_DB($this->israsytos_Gatos_Saskaitos());
			$this->insert_kodas();

		}

		private function israsytos_Gatos_Saskaitos() {
			return 'CREATE TABLE '.$this->prefix."buhalterija_gautos_saskaitos (
												  id mediumint(9) NOT NULL AUTO_INCREMENT,
												  data DATE DEFAULT '0000-00-00' NOT NULL,
												  sume_be_pvm CHAR(9),
												  suma_su_pvm CHAR(9),
												  pvm CHAR(9),
												  saskaitos_nr CHAR(50),
												  tiekejas CHAR(9),
												  apmokejimas CHAR(1),
												  PRIMARY KEY  (id)
												) $this->charset_collate;";
		}
		private function saskaitos_kodas() {
			return 'CREATE TABLE '.$this->prefix."buhalterija_kodas (
												  id mediumint(9) NOT NULL AUTO_INCREMENT,
												  kodas CHAR(8),
												  pavadinimas CHAR(150),
												  PRIMARY KEY  (id)
												) $this->charset_collate;";
		}
		private function buhalterija_Tiekejai_DB() {
			return 'CREATE TABLE '.$this->prefix."buhalterija_tiekejai (
												  id mediumint(9) NOT NULL AUTO_INCREMENT,
												  imones_pavadinimas CHAR(200),
												  salis CHAR(30),
												  miestas CHAR(30),
												  gatve CHAR(30),
												  namo_nr CHAR(30),
												  pasto_kodas CHAR(10),
												  imones_kodas CHAR(20),
												  pvm_kodas CHAR(20),
												  banko_kodas CHAR(10),
												  banko_saskaita CHAR(25),
												  telefonas CHAR(20),
												  elpastas CHAR(100),
												  PRIMARY KEY  (id)
												) $this->charset_collate;";
		}

		private function buhalterija_Global_Settings_DB() {
			return 'CREATE TABLE '.$this->prefix."buhalterija_global_settings (
												  id mediumint(9) NOT NULL AUTO_INCREMENT,
												  items_per_page int(3),
												  pardavimu_nuskaitymas CHAR(4),
												  PRIMARY KEY  (id)
												) $this->charset_collate;";
		}

		private function buhalterija_Saskaitos_DB() {
			return 'CREATE TABLE '.$this->prefix."buhalterija_saskaitos (
												  id mediumint(9) NOT NULL AUTO_INCREMENT,
												  uzsakymo_id mediumint(9),
												  pardavimo_data DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
												  saskaitos_nr CHAR(10),
												  saskaitos_tipas CHAR(10),
												  saskaitos_kodas CHAR(10),
												  pirkejas CHAR(200),
												  pinigai CHAR(10),
												  PRIMARY KEY  (id)
												) $this->charset_collate;";
		}

		private function buhalterija_R_D_DB() {
			return 'CREATE TABLE '.$this->prefix."buhalterija_r_d (
												  id mediumint(9) NOT NULL AUTO_INCREMENT,
												  last_read_date date DEFAULT '0000-00-00' NOT NULL,
												  PRIMARY KEY  (id)
												) $this->charset_collate;";
		}

		private function buhalterija_Create_DB($sql) { 
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			add_option('Buhalterija', $this->buhalterija_DB_Version);
		}
		private function insert_kodas() {
			global $wpdb;
			$results = $wpdb->get_results("SELECT (id) FROM {$wpdb->prefix}buhalterija_kodas ORDER BY id DESC LIMIT 1");
			if(empty($results)) {
				$array = $this->return_Saskaita_Code_data();
				$count = count($array);
				$count--;
				$kablis = ',';
				$insert = '';
				foreach ($array as $key => $value) {
					if($key == $count) {
						$kablis = '';
					}
					$insert .= "('".$value['kodas']."', '(".$value['kodas'].') '.$value['pavadinimas']."')".$kablis;
				}
				$wpdb->query("INSERT INTO {$wpdb->prefix}buhalterija_kodas (`kodas`, `pavadinimas`) VALUES $insert ");
			} 

		}

		private function return_Saskaita_Code_data() {
			$array[] = array('kodas' => '1', 'pavadinimas' => 'ILGALAIKIS TURTAS');
			$array[] = array('kodas' => '11', 'pavadinimas' => 'Nematerialusis turtas');
			$array[] = array('kodas' => '111', 'pavadinimas' => 'Pl??tros darbai');
			$array[] = array('kodas' => '1110', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '1118', 'pavadinimas' => 'Amortizacija (-)');
			$array[] = array('kodas' => '1119', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '112', 'pavadinimas' => 'Presti??as');
			$array[] = array('kodas' => '1120', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '1128', 'pavadinimas' => 'Amortizacija (-)');
			$array[] = array('kodas' => '1129', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '113', 'pavadinimas' => 'Patentai, licencijos');
			$array[] = array('kodas' => '1130', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '1138', 'pavadinimas' => 'Amortizacija (-)');
			$array[] = array('kodas' => '1139', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '114', 'pavadinimas' => 'Programin?? ??ranga');
			$array[] = array('kodas' => '1140', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '1141', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '1143', 'pavadinimas' => 'Programin??s ??rangos atnaujinimo kapitalizuotos i??laidos');
			$array[] = array('kodas' => '1148', 'pavadinimas' => 'Amortizacija (-)');
			$array[] = array('kodas' => '1149', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '115', 'pavadinimas' => 'Kitas nematerialusis turtas');
			$array[] = array('kodas' => '1150', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '1151', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '1158', 'pavadinimas' => 'Amortizacija (-)');
			$array[] = array('kodas' => '1159', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '12', 'pavadinimas' => 'Materialusis turtas');
			$array[] = array('kodas' => '120', 'pavadinimas' => '??em??');
			$array[] = array('kodas' => '1200', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '1201', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '1205', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '1209', 'pavadinimas' => 'Vert??s suma????jimas (-)121');
			$array[] = array('kodas' => '121', 'pavadinimas' => 'Pastatai ir statiniai');
			$array[] = array('kodas' => '1210', 'pavadinimas' => 'Gamybiniai pastatai');
			$array[] = array('kodas' => '12100', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '12101', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12102', 'pavadinimas' => 'Ruo??iami naudoti');
			$array[] = array('kodas' => '12103', 'pavadinimas' => 'Kapitalizuotos remonto, rekonstravimo i??laidos');
			$array[] = array('kodas' => '12105', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '12107', 'pavadinimas' => '??sigijimo savikainos nusid??v??jimas (-)');
			$array[] = array('kodas' => '12108', 'pavadinimas' => 'Perkainotos dalies nusid??v??jimas (-)');
			$array[] = array('kodas' => '12109', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '1211', 'pavadinimas' => 'Administraciniai pastatai');
			$array[] = array('kodas' => '12110', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '12111', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12112', 'pavadinimas' => 'Ruo??iami naudoti');
			$array[] = array('kodas' => '12113', 'pavadinimas' => 'Kapitalizuotos remonto, rekonstravimo i??laidos');
			$array[] = array('kodas' => '12115', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '12117', 'pavadinimas' => '??sigijimo savikainos nusid??v??jimas (-)');
			$array[] = array('kodas' => '12118', 'pavadinimas' => 'Perkainotos dalies nusid??v??jimas (-)');
			$array[] = array('kodas' => '12119', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '1212', 'pavadinimas' => 'Kiti pastatai');
			$array[] = array('kodas' => '12120', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '12121', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12122', 'pavadinimas' => 'Ruo??iami naudoti');
			$array[] = array('kodas' => '12123', 'pavadinimas' => 'Kapitalizuotos remonto, rekonstravimo i??laidos');
			$array[] = array('kodas' => '12125', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '12127', 'pavadinimas' => '??sigijimo savikainos nusid??v??jimas (-)');
			$array[] = array('kodas' => '12128', 'pavadinimas' => 'Perkainotos dalies nusid??v??jimas (-)');
			$array[] = array('kodas' => '12129', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '1213', 'pavadinimas' => 'Statiniai');
			$array[] = array('kodas' => '12130', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '12131', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12132', 'pavadinimas' => 'Ruo??iami naudoti');
			$array[] = array('kodas' => '12133', 'pavadinimas' => 'Kapitalizuotos remonto, rekonstravimo i??laidos');
			$array[] = array('kodas' => '12135', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '12137', 'pavadinimas' => '??sigijimo savikainos nusid??v??jimas (-)');
			$array[] = array('kodas' => '12138', 'pavadinimas' => 'Perkainotos dalies nusid??v??jimas (-)');
			$array[] = array('kodas' => '12139', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '122', 'pavadinimas' => 'Transporto priemon??s');
			$array[] = array('kodas' => '1220', 'pavadinimas' => 'Lengvieji automobiliai');
			$array[] = array('kodas' => '12200', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '12201', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12202', 'pavadinimas' => 'Ruo??iamos naudoti');
			$array[] = array('kodas' => '12203', 'pavadinimas' => 'Kapitalizuotos remonto i??laidos');
			$array[] = array('kodas' => '12205', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '12207', 'pavadinimas' => '??sigijimo savikainos nusid??v??jimas (-)');
			$array[] = array('kodas' => '12208', 'pavadinimas' => 'Perkainotos dalies nusid??v??jimas (-)');
			$array[] = array('kodas' => '12209', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '1221', 'pavadinimas' => 'Krovininiai automobiliai, priekabos, puspriekab??s ir autobusai');
			$array[] = array('kodas' => '12210', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '12211', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12212', 'pavadinimas' => 'Ruo??iamos naudoti');
			$array[] = array('kodas' => '12213', 'pavadinimas' => 'Kapitalizuotos remonto i??laidos');
			$array[] = array('kodas' => '12215', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '12217', 'pavadinimas' => '??sigijimo savikainos nusid??v??jimas (-)');
			$array[] = array('kodas' => '12218', 'pavadinimas' => 'Perkainotos dalies nusid??v??jimas (-)');
			$array[] = array('kodas' => '12219', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '1222', 'pavadinimas' => 'Kitos transporto priemon??s');
			$array[] = array('kodas' => '12220', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '12221', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12222', 'pavadinimas' => 'Ruo??iamos naudoti');
			$array[] = array('kodas' => '12223', 'pavadinimas' => 'Kapitalizuotos remonto i??laidos');
			$array[] = array('kodas' => '12225', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '12227', 'pavadinimas' => '??sigijimo savikainos nusid??v??jimas (-)');
			$array[] = array('kodas' => '12228', 'pavadinimas' => 'Perkainotos dalies nusid??v??jimas (-)');
			$array[] = array('kodas' => '12229', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '123', 'pavadinimas' => 'Kita ??ranga, prietaisai, ??rankiai ir ??rengimai');
			$array[] = array('kodas' => '1231', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '1232', 'pavadinimas' => 'Ruo??iami naudoti+');
			$array[] = array('kodas' => '1233', 'pavadinimas' => 'Kapitalizuotos remonto i??laidos');
			$array[] = array('kodas' => '1235', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '1237', 'pavadinimas' => '??sigijimo savikainos nusid??v??jimas (-)');
			$array[] = array('kodas' => '1238', 'pavadinimas' => 'Perkainotos dalies nusid??v??jimas (-)');
			$array[] = array('kodas' => '1239', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '124', 'pavadinimas' => 'Ma??inos ir ??rengimai');
			$array[] = array('kodas' => '1240', 'pavadinimas' => 'Ma??inos');
			$array[] = array('kodas' => '12400', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '12401', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12402', 'pavadinimas' => 'Ruo??iamos naudoti');
			$array[] = array('kodas' => '12403', 'pavadinimas' => 'Kapitalizuotos remonto i??laidos');
			$array[] = array('kodas' => '12405', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '12407', 'pavadinimas' => '??sigijimo savikainos nusid??v??jimas (-)');
			$array[] = array('kodas' => '12408', 'pavadinimas' => 'Perkainotos dalies nusid??v??jimas (-)');
			$array[] = array('kodas' => '12409', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '1241', 'pavadinimas' => '??rengimai');
			$array[] = array('kodas' => '12410', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '12411', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12412', 'pavadinimas' => 'Ruo??iami naudoti');
			$array[] = array('kodas' => '12413', 'pavadinimas' => 'Kapitalizuotos remonto i??laidos');
			$array[] = array('kodas' => '12415', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '12417', 'pavadinimas' => '??sigijimo savikainos nusid??v??jimas (-)');
			$array[] = array('kodas' => '12418', 'pavadinimas' => 'Perkainotos dalies nusid??v??jimas (-)');
			$array[] = array('kodas' => '12419', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '125', 'pavadinimas' => 'Nebaigta statyba');
			$array[] = array('kodas' => '1250', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '1251', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '1252', 'pavadinimas' => 'Ruo??iamos naudoti');
			$array[] = array('kodas' => '1259', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '126', 'pavadinimas' => 'Kitas materialusis turtas');
			$array[] = array('kodas' => '1260', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '1261', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '1262', 'pavadinimas' => 'Ruo??iamas naudoti');
			$array[] = array('kodas' => '1263', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '1264', 'pavadinimas' => 'Kapitalizuotos remonto i??laidos');
			$array[] = array('kodas' => '1265', 'pavadinimas' => 'I??sinuomoto turto kapitalizuotos remonto ir rekonstravimo i??laidos');
			$array[] = array('kodas' => '1267', 'pavadinimas' => '??sigijimo savikainos nusid??v??jimas (-)');
			$array[] = array('kodas' => '1268', 'pavadinimas' => 'Perkainotos dalies nusid??v??jimas (-)');
			$array[] = array('kodas' => '1269', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '127', 'pavadinimas' => 'Investicinis turtas');
			$array[] = array('kodas' => '1270', 'pavadinimas' => '??em??');
			$array[] = array('kodas' => '12700', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '12702', 'pavadinimas' => 'Ruo??iama naudoti');
			$array[] = array('kodas' => '12705', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '12709', 'pavadinimas' => 'Vert??s padid??jimas (suma????jimas)');
			$array[] = array('kodas' => '1271', 'pavadinimas' => 'Pastatai');
			$array[] = array('kodas' => '12710', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '12712', 'pavadinimas' => 'Ruo??iama naudoti');
			$array[] = array('kodas' => '12715', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '12717', 'pavadinimas' => '??sigijimo savikainos nusid??v??jimas');
			$array[] = array('kodas' => '12719', 'pavadinimas' => 'Vert??s padid??jimas (suma????jimas)');
			$array[] = array('kodas' => '16', 'pavadinimas' => 'Finansinis turtas');
			$array[] = array('kodas' => '160', 'pavadinimas' => 'Investicijos ?? dukterines ir asocijuotas ??mones');
			$array[] = array('kodas' => '1600', 'pavadinimas' => 'Investicijos ?? dukterines ??mones');
			$array[] = array('kodas' => '1601', 'pavadinimas' => 'Investicijos ?? asocijuotas ??mones');
			$array[] = array('kodas' => '1609', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '161', 'pavadinimas' => 'Paskolos asocijuotoms ir dukterin??ms ??mon??ms');
			$array[] = array('kodas' => '1610', 'pavadinimas' => 'Paskolos dukterin??ms ??mon??ms');
			$array[] = array('kodas' => '1611', 'pavadinimas' => 'Paskolos asocijuotoms ??mon??ms');
			$array[] = array('kodas' => '1618', 'pavadinimas' => 'Kontroliuotinos skolos');
			$array[] = array('kodas' => '1619', 'pavadinimas' => 'Abejotinos skolos (-)');
			$array[] = array('kodas' => '162', 'pavadinimas' => 'Po vieneri?? met?? gautinos sumos');
			$array[] = array('kodas' => '1621', 'pavadinimas' => 'Prekybos skolos');
			$array[] = array('kodas' => '1622', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '16221', 'pavadinimas' => 'Ateinan??i?? laikotarpi?? s??naudos, pripa??intinos patirtomis v??liau nei per kit?? ataskaitin?? laikotarp??');
			$array[] = array('kodas' => '1623', 'pavadinimas' => 'Lizingo (finansin??s nuomos) skolininkai');
			$array[] = array('kodas' => '1624', 'pavadinimas' => 'Po vieneri?? met?? gautinos sumos');
			$array[] = array('kodas' => '1625', 'pavadinimas' => 'Kitos po vieneri?? met?? gautinos sumos');
			$array[] = array('kodas' => '16251', 'pavadinimas' => 'Sukauptos pajamos, apmok??tinos v??liau nei per kit?? ataskaitin?? laikotarp??');
			$array[] = array('kodas' => '1628', 'pavadinimas' => 'Kontroliuotinos skolos');
			$array[] = array('kodas' => '1629', 'pavadinimas' => 'Abejotinos skolos (-)');
			$array[] = array('kodas' => '163', 'pavadinimas' => 'Kitas finansinis turtas');
			$array[] = array('kodas' => '1631', 'pavadinimas' => 'Kitos investicijos');
			$array[] = array('kodas' => '1632', 'pavadinimas' => 'Ilgalaik??s investicijos laikomos iki i??pirkimo');
			$array[] = array('kodas' => '16321', 'pavadinimas' => 'Investicijos ?? Vyriausyb??s vertybinius popierius');
			$array[] = array('kodas' => '163210', 'pavadinimas' => 'Nominali vert??');
			$array[] = array('kodas' => '163211', 'pavadinimas' => 'Diskontai (-)');
			$array[] = array('kodas' => '163212', 'pavadinimas' => 'Premijos');
			$array[] = array('kodas' => '16322', 'pavadinimas' => 'Investicijos ?? kit?? ??moni?? i??leistas obligacijas');
			$array[] = array('kodas' => '163220', 'pavadinimas' => 'Nominali vert??');
			$array[] = array('kodas' => '163221', 'pavadinimas' => 'Diskontai (-)');
			$array[] = array('kodas' => '163222', 'pavadinimas' => 'Premijos');
			$array[] = array('kodas' => '1633', 'pavadinimas' => 'Kitas ilgalaikis finansinis turtas');
			$array[] = array('kodas' => '1639', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '164', 'pavadinimas' => 'Atid??tojo pelno mokes??io turtas');
			$array[] = array('kodas' => '1641', 'pavadinimas' => 'Atid??tojo pelno mokes??io turtas');
			$array[] = array('kodas' => '1649', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '165', 'pavadinimas' => 'Kitas ilgalaikis turtas');
			$array[] = array('kodas' => '2', 'pavadinimas' => 'TRUMPALAIKIS TURTAS');
			$array[] = array('kodas' => '20', 'pavadinimas' => 'Atsargos, i??ankstiniai apmok??jimai ir nebaigtos vykdyti sutartys');
			$array[] = array('kodas' => '201', 'pavadinimas' => 'Atsargos');
			$array[] = array('kodas' => '2011', 'pavadinimas' => '??aliavos ir komplektavimo gaminiai');
			$array[] = array('kodas' => '20110', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '20119', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '2012', 'pavadinimas' => 'Nebaigta gamyba');
			$array[] = array('kodas' => '20120', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '20129', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '2013', 'pavadinimas' => 'Pagaminta produkcija');
			$array[] = array('kodas' => '20130', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '20139', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '2014', 'pavadinimas' => 'Pirktos prek??s, skirtos perparduoti');
			$array[] = array('kodas' => '20140', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '20149', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '2015', 'pavadinimas' => 'Atsargin??s dalys');
			$array[] = array('kodas' => '20150', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '20159', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '2016', 'pavadinimas' => 'Kitos atsargos');
			$array[] = array('kodas' => '20160', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '20169', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '2017', 'pavadinimas' => 'Atsargos kelyje');
			$array[] = array('kodas' => '20171', 'pavadinimas' => '??aliavos ir komplektavimo gaminiai');
			$array[] = array('kodas' => '201710', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '201719', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '20172', 'pavadinimas' => 'Nebaigta gamyba');
			$array[] = array('kodas' => '201720', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '201729', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '20173', 'pavadinimas' => 'Pagaminta produkcija');
			$array[] = array('kodas' => '201730', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '201739', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '20174', 'pavadinimas' => 'Pirktos prek??s, skirtos perparduoti');
			$array[] = array('kodas' => '201740', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '201749', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '20175', 'pavadinimas' => 'Atsargin??s dalys');
			$array[] = array('kodas' => '201750', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '201759', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '20176', 'pavadinimas' => 'Kitos atsargos');
			$array[] = array('kodas' => '201760', 'pavadinimas' => '??sigijimo savikaina');
			$array[] = array('kodas' => '201769', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '202', 'pavadinimas' => 'I??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '2021', 'pavadinimas' => 'I??ankstiniai apmok??jimai tiek??jams');
			$array[] = array('kodas' => '2023', 'pavadinimas' => 'I??ankstiniai apmok??jimai biud??etui, i??skyrus pelno mokest??');
			$array[] = array('kodas' => '2024', 'pavadinimas' => 'B??sim??j?? laikotarpi?? s??naudos');
			$array[] = array('kodas' => '2029', 'pavadinimas' => 'Abejotinos skolos (-)');
			$array[] = array('kodas' => '203', 'pavadinimas' => 'Nebaigtos vykdyti sutartys');
			$array[] = array('kodas' => '2031', 'pavadinimas' => 'Savikaina');
			$array[] = array('kodas' => '2032', 'pavadinimas' => 'Pripa??intas pelnas');
			$array[] = array('kodas' => '2033', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '24', 'pavadinimas' => 'Per vienerius metus gautinos sumos');
			$array[] = array('kodas' => '241', 'pavadinimas' => 'Pirk??j?? ??siskolinimas');
			$array[] = array('kodas' => '2411', 'pavadinimas' => 'Pirk??jai');
			$array[] = array('kodas' => '2412', 'pavadinimas' => 'Pirk??j?? skola u?? i??simok??tinai parduotas prekes');
			$array[] = array('kodas' => '2413', 'pavadinimas' => 'Gauti prekybiniai vekseliai');
			$array[] = array('kodas' => '2418', 'pavadinimas' => 'Kontroliuotinos skolos');
			$array[] = array('kodas' => '2419', 'pavadinimas' => 'Abejotinos skolos (-)');
			$array[] = array('kodas' => '242', 'pavadinimas' => 'Dukterini?? ir asocijuot?? ??moni?? skolos');
			$array[] = array('kodas' => '2421', 'pavadinimas' => 'Dukterini?? ??moni?? skolos');
			$array[] = array('kodas' => '2422', 'pavadinimas' => 'Asocijuot?? ??moni?? skolos');
			$array[] = array('kodas' => '2428', 'pavadinimas' => 'Kontroliuotinos skolos');
			$array[] = array('kodas' => '2429', 'pavadinimas' => 'Abejotinos skolos (-)');
			$array[] = array('kodas' => '243', 'pavadinimas' => 'Kitos gautinos sumos');
			$array[] = array('kodas' => '2431', 'pavadinimas' => 'Gautinas PVM');
			$array[] = array('kodas' => '24311', 'pavadinimas' => 'Pirkimo PVM');
			$array[] = array('kodas' => '24312', 'pavadinimas' => 'Pirkimo PVM atskaita');
			$array[] = array('kodas' => '24313', 'pavadinimas' => 'Pirkimo PVM atskaitos tikslinimas (-)');
			$array[] = array('kodas' => '24314', 'pavadinimas' => 'Neatskaitomas pirkimo PVM');
			$array[] = array('kodas' => '2432', 'pavadinimas' => 'Biud??eto skola ??monei');
			$array[] = array('kodas' => '2433', 'pavadinimas' => 'Sodros skola ??monei');
			$array[] = array('kodas' => '2434', 'pavadinimas' => 'Kitos gautinos skolos');
			$array[] = array('kodas' => '2435', 'pavadinimas' => 'Atsakingi asmenys');
			$array[] = array('kodas' => '2436', 'pavadinimas' => 'Sukauptos gautinos pajamos');
			$array[] = array('kodas' => '2437', 'pavadinimas' => 'Suteiktos pinigin??s garantijos');
			$array[] = array('kodas' => '2438', 'pavadinimas' => 'Darbo u??mokes??io avansas');
			$array[] = array('kodas' => '2439', 'pavadinimas' => 'Abejotinos skolos (-)');
			$array[] = array('kodas' => '26', 'pavadinimas' => 'Kitas trumpalaikis turtas');
			$array[] = array('kodas' => '261', 'pavadinimas' => 'Trumpalaik??s investicijos');
			$array[] = array('kodas' => '2611', 'pavadinimas' => 'Kit?? ??moni?? akcijos');
			$array[] = array('kodas' => '2612', 'pavadinimas' => 'Obligacijos');
			$array[] = array('kodas' => '2613', 'pavadinimas' => 'Kiti vertybiniai popieriai');
			$array[] = array('kodas' => '2615', 'pavadinimas' => 'I??ankstiniai apmok??jimai u?? trumpalaikes investicijas');
			$array[] = array('kodas' => '2619', 'pavadinimas' => 'Vert??s suma????jimas (-)');
			$array[] = array('kodas' => '262', 'pavadinimas' => 'Terminuoti ind??liai');
			$array[] = array('kodas' => '263', 'pavadinimas' => 'Kitas trumpalaikis turtas');
			$array[] = array('kodas' => '2631', 'pavadinimas' => 'Kitas trumpalaikis turtas');
			$array[] = array('kodas' => '2632', 'pavadinimas' => 'I?? anksto sumok??tas pelno mokestis');
			$array[] = array('kodas' => '27', 'pavadinimas' => 'Pinigai ir pinig?? ekvivalentai');
			$array[] = array('kodas' => '270', 'pavadinimas' => 'Pinig?? ekvivalentai');
			$array[] = array('kodas' => '271', 'pavadinimas' => 'S??skaitos bankuose');
			$array[] = array('kodas' => '2711', 'pavadinimas' => 'Litai');
			$array[] = array('kodas' => '2712', 'pavadinimas' => 'JAV doleriai');
			$array[] = array('kodas' => '2713', 'pavadinimas' => '??veicarijos frankai');
			$array[] = array('kodas' => '2714', 'pavadinimas' => 'Rusijos rubliai');
			$array[] = array('kodas' => '2715', 'pavadinimas' => 'Eurai');
			$array[] = array('kodas' => '2716', 'pavadinimas' => 'Kita valiuta (pagal r??????)');
			$array[] = array('kodas' => '272', 'pavadinimas' => 'Kasa');
			$array[] = array('kodas' => '2721', 'pavadinimas' => 'Litai');
			$array[] = array('kodas' => '2722', 'pavadinimas' => 'JAV doleriai');
			$array[] = array('kodas' => '2723', 'pavadinimas' => '??veicarijos frankai');
			$array[] = array('kodas' => '2724', 'pavadinimas' => 'Rusijos rubliai');
			$array[] = array('kodas' => '2725', 'pavadinimas' => 'Eurai');
			$array[] = array('kodas' => '2726', 'pavadinimas' => 'Kita valiuta (pagal r??????)');
			$array[] = array('kodas' => '273', 'pavadinimas' => '');
			$array[] = array('kodas' => '2731', 'pavadinimas' => 'Litai');
			$array[] = array('kodas' => '2732', 'pavadinimas' => 'JAV doleriai');
			$array[] = array('kodas' => '2735', 'pavadinimas' => 'Eurai');
			$array[] = array('kodas' => '2736', 'pavadinimas' => 'Kita valiuta (pagal r??????)');
			$array[] = array('kodas' => '279', 'pavadinimas' => '????aldytos s??skaitos (-)');
			$array[] = array('kodas' => '3', 'pavadinimas' => 'NUOSAVAS KAPITALAS');
			$array[] = array('kodas' => '30', 'pavadinimas' => 'Kapitalas ');
			$array[] = array('kodas' => '301', 'pavadinimas' => '??statinis pasira??ytasis kapitalas');
			$array[] = array('kodas' => '3011', 'pavadinimas' => 'Paprastosios akcijos');
			$array[] = array('kodas' => '3012', 'pavadinimas' => 'Privilegijuotosios akcijos');
			$array[] = array('kodas' => '3013', 'pavadinimas' => 'Darbuotoj?? akcijos');
			$array[] = array('kodas' => '302', 'pavadinimas' => 'Pasira??ytasis neapmok??tas pelnas');
			$array[] = array('kodas' => '303', 'pavadinimas' => 'Akcij?? priedai');
			$array[] = array('kodas' => '304', 'pavadinimas' => 'Savos akcijos (-)');
			$array[] = array('kodas' => '32', 'pavadinimas' => 'Perkainojimo rezervas (rezultatai)');
			$array[] = array('kodas' => '321', 'pavadinimas' => 'Ilgalaikio materialiojo turto perkainojimo rezultatas');
			$array[] = array('kodas' => '322', 'pavadinimas' => 'Ilgalaikio finansinio turto perkainojimo rezultatas');
			$array[] = array('kodas' => '331', 'pavadinimas' => 'Privalomasis ');
			$array[] = array('kodas' => '332', 'pavadinimas' => 'Savom akcijoms ??sigyti');
			$array[] = array('kodas' => '333', 'pavadinimas' => 'Kiti rezervai');
			$array[] = array('kodas' => '34', 'pavadinimas' => 'Nepaskirstytasis pelnas (nuostoliai)');
			$array[] = array('kodas' => '341', 'pavadinimas' => 'Ataskaitini?? met?? nepaskirstytasis pelnas (nuostoliai)');
			$array[] = array('kodas' => '3411', 'pavadinimas' => 'Pelno (nuostoli??) ataskaitoje pripa??intas ataskaitini?? met?? pelnas (nuostoliai)');
			$array[] = array('kodas' => '3412', 'pavadinimas' => 'Pelno (nuostoli??) ataskaitoje nepripa??intas ataskaitini?? met?? pelnas (nuostoliai)');
			$array[] = array('kodas' => '34121', 'pavadinimas' => 'Pelnas (nuostoliai) i?? sav?? akcij?? perleidimo');
			$array[] = array('kodas' => '34122', 'pavadinimas' => 'Pelnas (nuostoliai) i?? sav?? akcij?? anuliavimo');
			$array[] = array('kodas' => '342', 'pavadinimas' => 'Ankstesni?? met?? nepaskirstytasis pelnas (nuostoliai)');
			$array[] = array('kodas' => '3421', 'pavadinimas' => 'Pelno (nuostoli??) ataskaitoje pripa??intas ankstesni?? met?? pelnas (nuostoliai)');
			$array[] = array('kodas' => '3422', 'pavadinimas' => 'Pelno (nuostoli??) apskaitoje pripa??intas ankstesni??j?? met?? pelnas (nuostoliai)');
			$array[] = array('kodas' => '3423', 'pavadinimas' => 'Ankstesni??j?? met?? apskaitos politikos keitimo pelnas (nuostoliai)');
			$array[] = array('kodas' => '34231', 'pavadinimas' => 'Lyginamojo laikotarpio apskaitos politikos keitimo pelnas (nuostoliai)');
			$array[] = array('kodas' => '34232', 'pavadinimas' => 'Ankstesni?? u?? lyginam??j?? laikotarpi?? apskaitos politikos keitimo pelnas (nuostoliai)');
			$array[] = array('kodas' => '3424', 'pavadinimas' => 'Ankstesni??j?? met?? esmini?? klaid?? taisymo pelnas (nuostoliai)');
			$array[] = array('kodas' => '34241', 'pavadinimas' => 'Lyginamojo laikotarpio  esmini?? klaid?? taisymo pelnas (nuostoliai)');
			$array[] = array('kodas' => '34242', 'pavadinimas' => 'Ankstesni??j?? u?? lyginam??j?? laikotarpi?? esmini?? klaid?? taisymo pelnas (nuostoliai)');
			$array[] = array('kodas' => '3425', 'pavadinimas' => 'Po paskirstymo lik??s nepaskirstytas pelnas (nuostoliai)');
			$array[] = array('kodas' => '35', 'pavadinimas' => 'Dotacijos ir subsidijos');
			$array[] = array('kodas' => '3501', 'pavadinimas' => 'Gautinos dotacijos ir subsidijos');
			$array[] = array('kodas' => '35010', 'pavadinimas' => 'Su turtu susijusios gautinos dotacijos ir subsidijos');
			$array[] = array('kodas' => '35011', 'pavadinimas' => 'Su pajamomis susijusios gautinos dotacijos ir subsidijos');
			$array[] = array('kodas' => '3511', 'pavadinimas' => 'Gautos dotacijos ir subsidijos');
			$array[] = array('kodas' => '35110', 'pavadinimas' => 'Su turtu susijusios gautos dotacijos ir subsidijos');
			$array[] = array('kodas' => '35111', 'pavadinimas' => 'Su pajamomis susijusios gautos dotacijos ir subsidijos');
			$array[] = array('kodas' => '390', 'pavadinimas' => 'Suvestin?? s??skaita');
			$array[] = array('kodas' => '3900', 'pavadinimas' => 'Gamybos suvestin??');
			$array[] = array('kodas' => '3901', 'pavadinimas' => 'Pajam?? ir s??naud?? suvestin??');
			$array[] = array('kodas' => '3902', 'pavadinimas' => 'Turto ir nuosavyb??s suvestin??');
			$array[] = array('kodas' => '3908', 'pavadinimas' => 'Laukimo s??skaita');
			$array[] = array('kodas' => '4', 'pavadinimas' => 'MOK??TINOS SUMOS IR ??SIPAREIGOJIMAI');
			$array[] = array('kodas' => '40', 'pavadinimas' => 'Po vieneri?? met?? mok??tinos sumos ir ilgalaikiai ??sipareigojimai');
			$array[] = array('kodas' => '401', 'pavadinimas' => 'Finansiniai ??sipareigojimai');
			$array[] = array('kodas' => '4011', 'pavadinimas' => 'Lizingo (finansin??s nuomos) ar pana????s ??sipareigojimai');
			$array[] = array('kodas' => '4012', 'pavadinimas' => 'Kredito ??staigos');
			$array[] = array('kodas' => '4013', 'pavadinimas' => 'Kitos finansin??s skolos');
			$array[] = array('kodas' => '40130', 'pavadinimas' => '??sipareigojimai dukterin??ms ??mon??ms');
			$array[] = array('kodas' => '40131', 'pavadinimas' => '??sipareigojimai asocijuotoms ??mon??ms');
			$array[] = array('kodas' => '40132', 'pavadinimas' => 'Finansiniai ??sipareigojimai kitiems kreditoriams');
			$array[] = array('kodas' => '401321', 'pavadinimas' => 'Finansiniai ??sipareigojimai kitiems kreditoriams ');
			$array[] = array('kodas' => '401322', 'pavadinimas' => 'Finansini?? ??sipareigojim?? kitiems kreditoriams diskontai (-)');
			$array[] = array('kodas' => '401323', 'pavadinimas' => 'Finansini?? ??sipareigojim?? kitiems kreditoriams premijos');
			$array[] = array('kodas' => '402', 'pavadinimas' => '??sipareigojimai tiek??jams ');
			$array[] = array('kodas' => '403', 'pavadinimas' => 'Gauti i??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '4031', 'pavadinimas' => 'Ateinan??i?? laikotarpi?? pajamos, pripa??intos u??dirbtomis v??liau nei per ateinan??ius metus');
			$array[] = array('kodas' => '4032', 'pavadinimas' => 'Gauti i??ankstiniai apmok??jimai u?? v??liau nei per ateinan??ius metus pateiktas prekes');
			$array[] = array('kodas' => '4033', 'pavadinimas' => 'Gauti i??ankstiniai apmok??jimai u?? v??liau nei per ateinan??ius metus suteiktas paslaugas');
			$array[] = array('kodas' => '404', 'pavadinimas' => 'Atid??jimai');
			$array[] = array('kodas' => '4041', 'pavadinimas' => '??sipareigojim?? ir reikalavim?? padengimo atid??jimai');
			$array[] = array('kodas' => '4042', 'pavadinimas' => 'Pensij?? ir pana??i?? ??sipareigojim?? atid??jimai');
			$array[] = array('kodas' => '4043', 'pavadinimas' => 'Kiti atid??jimai');
			$array[] = array('kodas' => '405', 'pavadinimas' => 'Atid??tieji mokes??iai');
			$array[] = array('kodas' => '4051', 'pavadinimas' => 'Atid??tojo pelno mokes??io ??sipareigojimai');
			$array[] = array('kodas' => '406', 'pavadinimas' => 'Kitos mok??tinos sumos ir ilgalaikiai ??sipareigojimai');
			$array[] = array('kodas' => '4061', 'pavadinimas' => '??sipareigojimai pagal mokestin??s paskolos sutartis');
			$array[] = array('kodas' => '4062', 'pavadinimas' => 'Kiti ??sipareigojimai');
			$array[] = array('kodas' => '4063', 'pavadinimas' => 'Sukauptos s??naudos, apmok??tinos v??liau nei per ateinan??ius metus');
			$array[] = array('kodas' => '44', 'pavadinimas' => 'Per vienerius metus mok??tinos sumos ir trumpalaikiai ??sipareigojimai');
			$array[] = array('kodas' => '441', 'pavadinimas' => 'Ilgalaiki?? ??sipareigojim?? einam??j?? met?? dalis');
			$array[] = array('kodas' => '4411', 'pavadinimas' => 'Lizingo (finansin??s nuomos) ir kiti pana????s ??sipareigojimai');
			$array[] = array('kodas' => '44111', 'pavadinimas' => 'Lizingo (finansin??s nuomos) ir kiti pana????s ??sipareigojimai');
			$array[] = array('kodas' => '44112', 'pavadinimas' => 'Lizingo (finansin??s nuomos) ir kiti pana????s ??sipareigojimai pagal gautas s??skaitas');
			$array[] = array('kodas' => '4412', 'pavadinimas' => 'Prekybiniai ??sipareigojimai');
			$array[] = array('kodas' => '4413', 'pavadinimas' => 'Kiti ??sipareigojimai');
			$array[] = array('kodas' => '442', 'pavadinimas' => 'Finansiniai ??sipareigojimai');
			$array[] = array('kodas' => '4421', 'pavadinimas' => 'Kredito ??staigoms');
			$array[] = array('kodas' => '44210', 'pavadinimas' => 'I?? kredito ??staig?? gautos paskolos');
			$array[] = array('kodas' => '44211', 'pavadinimas' => 'I??duoti finansiniai vekseliai');
			$array[] = array('kodas' => '4422', 'pavadinimas' => 'Kiti finansiniai ??sipareigojimai');
			$array[] = array('kodas' => '44220', 'pavadinimas' => '??sipareigojimai dukterin??ms ??mon??ms');
			$array[] = array('kodas' => '44221', 'pavadinimas' => '??sipareigojimai asocijuotoms ??mon??ms');
			$array[] = array('kodas' => '443', 'pavadinimas' => '??sipareigojimai tiek??jams');
			$array[] = array('kodas' => '4430', 'pavadinimas' => '??sipareigojimai preki?? ir paslaug?? tiek??jams');
			$array[] = array('kodas' => '4431', 'pavadinimas' => 'I??duoti prekybiniai vekseliai');
			$array[] = array('kodas' => '4432', 'pavadinimas' => '??sipareigojimai pagal gautinas s??skaitas');
			$array[] = array('kodas' => '4433', 'pavadinimas' => 'Nefakt??ruoti pirkimai');
			$array[] = array('kodas' => '4438', 'pavadinimas' => 'Pirkimo diskontai (-)');
			$array[] = array('kodas' => '444', 'pavadinimas' => 'Gauti i??ankstiniai apmok??jimai');
			$array[] = array('kodas' => '4441', 'pavadinimas' => 'Ateinan??i?? laikotarpi?? pajamos, pripa??intinos u??dirbtomis per ateinan??ius metus');
			$array[] = array('kodas' => '4442', 'pavadinimas' => 'Gauti i??ankstiniai apmok??jimai u?? per ateinan??ius metus pateiktas prekes');
			$array[] = array('kodas' => '4443', 'pavadinimas' => 'Gauti i??ankstiniai apmok??jimai u?? per ateinan??ius metus suteiktas paslaugas');
			$array[] = array('kodas' => '445', 'pavadinimas' => 'Pelno mokes??io ??sipareigojimai');
			$array[] = array('kodas' => '446', 'pavadinimas' => 'Su darbo santykiai susij?? ??sipareigojimai');
			$array[] = array('kodas' => '4461', 'pavadinimas' => 'Mok??tinas darbo u??mokestis');
			$array[] = array('kodas' => '4462', 'pavadinimas' => 'Mok??tinas gyventoj?? pajam?? mokestis');
			$array[] = array('kodas' => '4463', 'pavadinimas' => 'Mok??tinos socialinio draudimo ??mokos');
			$array[] = array('kodas' => '4464', 'pavadinimas' => 'Mok??tinos Garantinio fondo ??mokos');
			$array[] = array('kodas' => '4465', 'pavadinimas' => 'Kitos i??mokos darbuotojams');
			$array[] = array('kodas' => '4466', 'pavadinimas' => 'Atostogini?? kaupimai');
			$array[] = array('kodas' => '447', 'pavadinimas' => 'Atid??jimai');
			$array[] = array('kodas' => '4471', 'pavadinimas' => '??sipareigojim?? ir reikalavim?? padengimo atid??jimai');
			$array[] = array('kodas' => '4473', 'pavadinimas' => 'Kiti atid??jimai');
			$array[] = array('kodas' => '448', 'pavadinimas' => 'Kitos mok??tinos sumos ir trumpalaikiai ??sipareigojimai');
			$array[] = array('kodas' => '4481', 'pavadinimas' => 'Mok??tini dividendai ir kiti ??sipareigojimai akcininkams');
			$array[] = array('kodas' => '4482', 'pavadinimas' => 'Mok??tinos tantjemos');
			$array[] = array('kodas' => '4483', 'pavadinimas' => 'Sukauptos s??naudos, apmok??jimai per ateinan??ius metus');
			$array[] = array('kodas' => '4484', 'pavadinimas' => 'Mok??tinas PVM ');
			$array[] = array('kodas' => '44841', 'pavadinimas' => 'Mok??tinas pardavimo PVM');
			$array[] = array('kodas' => '44843', 'pavadinimas' => 'Pardavimo PVM koregavimai (-)');
			$array[] = array('kodas' => '4485', 'pavadinimas' => 'Kiti mok??ti mokes??iai biud??etui');
			$array[] = array('kodas' => '4488', 'pavadinimas' => 'Kitos mok??tinos sumos');
			$array[] = array('kodas' => '44880', 'pavadinimas' => '??sipareigojimai d??l ne??asmenintai gaut?? pinig??');
			$array[] = array('kodas' => '44881', 'pavadinimas' => 'Gautos pinigin??s garantijos');
			$array[] = array('kodas' => '44882', 'pavadinimas' => '??sipareigojimai pagal nebaigtas vykdyti sutartis');
			$array[] = array('kodas' => '44886', 'pavadinimas' => 'Kitos mok??tinos sumos');
			$array[] = array('kodas' => '5', 'pavadinimas' => 'PAJAMOS');
			$array[] = array('kodas' => '50', 'pavadinimas' => 'Pardavimo pajamos');
			$array[] = array('kodas' => '500', 'pavadinimas' => 'Pardavimo pajamos');
			$array[] = array('kodas' => '5001', 'pavadinimas' => 'Pardavimo pajamos');
			$array[] = array('kodas' => '5002', 'pavadinimas' => 'Pardavimai i??simok??tinai');
			$array[] = array('kodas' => '5003', 'pavadinimas' => 'Pripa??intas pelnas i?? pardavim?? i??simok??tinai');
			$array[] = array('kodas' => '5004', 'pavadinimas' => 'Preki?? main?? pajamos');
			$array[] = array('kodas' => '5005', 'pavadinimas' => 'Suteikt?? paslaug?? pajamos');
			$array[] = array('kodas' => '5006', 'pavadinimas' => 'Paslaug?? main?? pajamos');
			$array[] = array('kodas' => '509', 'pavadinimas' => 'Nuolaidos, gr????inimai');
			$array[] = array('kodas' => '5090', 'pavadinimas' => 'Pardavimo nuolaidos (-)');
			$array[] = array('kodas' => '5091', 'pavadinimas' => 'Parduot?? preki?? gr????inimai (-)');
			$array[] = array('kodas' => '5092', 'pavadinimas' => 'Parduot?? preki?? nukainojimai (-)');
			$array[] = array('kodas' => '5093', 'pavadinimas' => 'Pardavim?? diskontai (-)');
			$array[] = array('kodas' => '5094', 'pavadinimas' => 'Nepanaudoti pardavim?? diskontai');
			$array[] = array('kodas' => '52', 'pavadinimas' => 'Kitos veiklos pajamos');
			$array[] = array('kodas' => '5211', 'pavadinimas' => 'Pelnas d??l ilgalaikio turto perleidimo');
			$array[] = array('kodas' => '5212', 'pavadinimas' => 'Tipinei veiklai nepriskirt?? pardavim?? ir paslaug?? pajamos');
			$array[] = array('kodas' => '52120', 'pavadinimas' => 'Turto pardavimo pagal lizingo sutartis pajamos');
			$array[] = array('kodas' => '52121', 'pavadinimas' => 'Reklamos pardavimo pajamos');
			$array[] = array('kodas' => '52122', 'pavadinimas' => 'Nuomos pajamos');
			$array[] = array('kodas' => '52123', 'pavadinimas' => 'Komisini?? pajamos');
			$array[] = array('kodas' => '52124', 'pavadinimas' => 'Tipinei veiklai nepriskirt?? main?? pajamos');
			$array[] = array('kodas' => '5213', 'pavadinimas' => 'Nebaigt?? vykdyti sutar??i?? pajamos');
			$array[] = array('kodas' => '5214', 'pavadinimas' => 'Negauta kitos veiklos pajamas kompensuojan??i?? dotacij?? ir subsidij?? amortizuota dalis');
			$array[] = array('kodas' => '5228', 'pavadinimas' => 'Kitos netipin??s veiklos pajamos');
			$array[] = array('kodas' => '53', 'pavadinimas' => 'Finansin??s ??? investicin??s veikos pajamos');
			$array[] = array('kodas' => '532', 'pavadinimas' => 'Dividend?? pajamos');
			$array[] = array('kodas' => '533', 'pavadinimas' => 'Baudos ir delspinigiai u?? pradelst?? pirk??j?? ??siskolinim??');
			$array[] = array('kodas' => '534', 'pavadinimas' => 'Investicij?? perleidimo pelnas');
			$array[] = array('kodas' => '535', 'pavadinimas' => 'Pal??kan?? pajamos');
			$array[] = array('kodas' => '5350', 'pavadinimas' => 'Pal??kanos u?? ind??lius');
			$array[] = array('kodas' => '5351', 'pavadinimas' => 'Pal??kanos u?? s??skaitas bankuose');
			$array[] = array('kodas' => '5352', 'pavadinimas' => 'Lizingo pal??kanos');
			$array[] = array('kodas' => '5353', 'pavadinimas' => 'Pal??kanos u?? vertybinius popierius');
			$array[] = array('kodas' => '5354', 'pavadinimas' => 'Pal??kanos u?? i??simok??tinai parduotas prekes');
			$array[] = array('kodas' => '536', 'pavadinimas' => 'Teigiama valiut?? kurs?? pasikeitimo ??taka');
			$array[] = array('kodas' => '537', 'pavadinimas' => 'Mok??tin?? sum?? diskontai ir nuolaidos');
			$array[] = array('kodas' => '538', 'pavadinimas' => 'Investicij?? perkainojimo pajamos');
			$array[] = array('kodas' => '539', 'pavadinimas' => 'Kitos finansin??s ??? investicin??s veiklos pajamos');
			$array[] = array('kodas' => '54', 'pavadinimas' => 'Pagaut??');
			$array[] = array('kodas' => '59', 'pavadinimas' => 'Pelno paskirstymas');
			$array[] = array('kodas' => '591', 'pavadinimas' => 'Pra??jusi?? met?? nepaskirstytasis pelnas');
			$array[] = array('kodas' => '592', 'pavadinimas' => 'Pervedimai i?? rezerv??');
			$array[] = array('kodas' => '593', 'pavadinimas' => 'Nepaskirstytasis nuostolis');
			$array[] = array('kodas' => '594', 'pavadinimas' => 'Akcinink?? ??na??ai nuostoliams padengti');
			$array[] = array('kodas' => '595', 'pavadinimas' => 'Akcij?? pried?? ma??inimas');
			$array[] = array('kodas' => '6', 'pavadinimas' => 'S??NAUDOS');
			$array[] = array('kodas' => '60', 'pavadinimas' => 'Parduot?? preki?? ir suteikt?? paslaug?? savikaina');
			$array[] = array('kodas' => '600', 'pavadinimas' => 'Parduot?? preki?? ir suteikt?? paslaug?? savikaina');
			$array[] = array('kodas' => '6000', 'pavadinimas' => 'Parduot?? preki?? savikaina');
			$array[] = array('kodas' => '60000', 'pavadinimas' => 'I??simok??tinai parduot?? preki?? savikaina');
			$array[] = array('kodas' => '6001', 'pavadinimas' => 'Suteikt?? paslaug?? savikaina');
			$array[] = array('kodas' => '6002', 'pavadinimas' => 'Pirkimai');
			$array[] = array('kodas' => '6003', 'pavadinimas' => 'Tiesiogin??s gamybos i??laidos');
			$array[] = array('kodas' => '60031', 'pavadinimas' => 'Pagrindin??s ??aliavos');
			$array[] = array('kodas' => '60032', 'pavadinimas' => 'Tiesioginis darbo u??mokestis');
			$array[] = array('kodas' => '60033', 'pavadinimas' => 'Socialinis draudimas nuo tiesioginio darbo u??mokes??io');
			$array[] = array('kodas' => '6004', 'pavadinimas' => 'Netiesiogin??s gamybos i??laidos');
			$array[] = array('kodas' => '60041', 'pavadinimas' => 'Pagalbin??s ??aliavos');
			$array[] = array('kodas' => '60042', 'pavadinimas' => 'Pagalbini?? darbinink?? darbo u??mokestis');
			$array[] = array('kodas' => '60043', 'pavadinimas' => 'Socialinis draudimas nuo pagalbini?? darbinink?? darbo u??mokes??io');
			$array[] = array('kodas' => '60044', 'pavadinimas' => 'Gamybos ??rengini?? nusid??v??jimas');
			$array[] = array('kodas' => '60045', 'pavadinimas' => 'Gamybini?? pastat?? nusid??v??jimas');
			$array[] = array('kodas' => '60046', 'pavadinimas' => 'Netiesiogin??s paslaugos');
			$array[] = array('kodas' => '6005', 'pavadinimas' => 'Atsarg?? padid??jimas / suma????jimas');
			$array[] = array('kodas' => '60051', 'pavadinimas' => '??aliavos ir komplektuojamieji gaminiai');
			$array[] = array('kodas' => '60052', 'pavadinimas' => 'Nebaigta gamyba');
			$array[] = array('kodas' => '60053', 'pavadinimas' => 'Pagaminta produkcija');
			$array[] = array('kodas' => '60054', 'pavadinimas' => 'Pirktos prek??s, skirtos perparduoti');
			$array[] = array('kodas' => '609', 'pavadinimas' => 'Nuolaidos, gr????inimai (-)');
			$array[] = array('kodas' => '6090', 'pavadinimas' => 'Pirkim?? diskontai (-)');
			$array[] = array('kodas' => '6091', 'pavadinimas' => 'Pirkim?? diskont?? praradimas');
			$array[] = array('kodas' => '6092', 'pavadinimas' => 'Pirkim?? nuolaidos (-)');
			$array[] = array('kodas' => '6093', 'pavadinimas' => 'Pirkt?? preki?? gr????inimai (-)');
			$array[] = array('kodas' => '6094', 'pavadinimas' => 'Pirkt?? preki?? nukainojimai (-)');
			$array[] = array('kodas' => '61', 'pavadinimas' => 'Veiklos s??naudos');
			$array[] = array('kodas' => '610', 'pavadinimas' => 'Pardavim?? s??naudos');
			$array[] = array('kodas' => '6100', 'pavadinimas' => 'Komisini?? tretiesiems asmenims s??naudos');
			$array[] = array('kodas' => '6101', 'pavadinimas' => 'Pardavim?? paslaug?? s??naudos');
			$array[] = array('kodas' => '61010', 'pavadinimas' => 'Baudos ir netesybos u?? nekokybi??k?? preki?? pardavim??');
			$array[] = array('kodas' => '61011', 'pavadinimas' => 'Atid??jim?? garantiniam parduot?? preki?? remontui s??naudos');
			$array[] = array('kodas' => '61012', 'pavadinimas' => 'Nepanaudot?? atid??jim?? garantiniam parduot?? preki?? remontui atstatymas (-)');
			$array[] = array('kodas' => '61013', 'pavadinimas' => 'Preki?? atsarg?? draudimo s??naudos');
			$array[] = array('kodas' => '6102', 'pavadinimas' => 'Skelbim?? ir reklamos s??naudos');
			$array[] = array('kodas' => '6103', 'pavadinimas' => 'Darbuotoj?? darbo u??mokes??io ir su juo susijusios s??naudos');
			$array[] = array('kodas' => '6104', 'pavadinimas' => 'Nat??ralios preki?? netekties s??naudos');
			$array[] = array('kodas' => '6105', 'pavadinimas' => 'Prekybin??s ??rangos nusid??v??jimo s??naudos');
			$array[] = array('kodas' => '6108', 'pavadinimas' => 'Kitos pardavim?? s??naudos');
			$array[] = array('kodas' => '6109', 'pavadinimas' => 'Gautos nuolaidos (-)');
			$array[] = array('kodas' => '611', 'pavadinimas' => 'Bendrosios ir administracin??s s??naudos');
			$array[] = array('kodas' => '6110', 'pavadinimas' => 'Nuomos s??naudos');
			$array[] = array('kodas' => '6111', 'pavadinimas' => 'Remonto ir eksploatavimo s??naudos');
			$array[] = array('kodas' => '6112', 'pavadinimas' => 'Mok??jim?? tretiesiems asmenims s??naudos');
			$array[] = array('kodas' => '6113', 'pavadinimas' => 'Draudimo s??naudos');
			$array[] = array('kodas' => '6114', 'pavadinimas' => 'Darbuotoj?? darbo u??mokestis ir su juo susijusios s??naudos');
			$array[] = array('kodas' => '61140', 'pavadinimas' => 'Darbuotoj?? darbo u??mokes??io s??naudos');
			$array[] = array('kodas' => '61141', 'pavadinimas' => 'Darbuotoj?? socialinio draudimo s??naudos');
			$array[] = array('kodas' => '6115', 'pavadinimas' => 'Ilgalaikio turto nusid??v??jimo ir amortizacijos s??naudos');
			$array[] = array('kodas' => '61150', 'pavadinimas' => 'Ilgalaikio materialiojo turto nusid??v??jimo s??naudos');
			$array[] = array('kodas' => '61151', 'pavadinimas' => 'Nematerialiojo turto amortizacijos s??naudos');
			$array[] = array('kodas' => '61152', 'pavadinimas' => 'Dotacij?? ir subsidij??, susijusi?? su ilgalaikiu turtu, amortizuota dalis (-)');
			$array[] = array('kodas' => '6117', 'pavadinimas' => 'Abejotin?? skol?? s??naudos');
			$array[] = array('kodas' => '61170', 'pavadinimas' => 'Abejotin?? skol?? ??vertinimo s??naudos');
			$array[] = array('kodas' => '61171', 'pavadinimas' => 'Abejotin?? skol?? atstatymas (-)');
			$array[] = array('kodas' => '6118', 'pavadinimas' => '??vairios bendrosios s??naudos');
			$array[] = array('kodas' => '61181', 'pavadinimas' => 'Atsarg?? (i??skyrus prekes) nat??ralios netekties s??naudos');
			$array[] = array('kodas' => '61183', 'pavadinimas' => '??mon??s reklamos s??naudos');
			$array[] = array('kodas' => '6119', 'pavadinimas' => 'Gautos nuolaidos (-)');
			$array[] = array('kodas' => '612', 'pavadinimas' => 'Veiklos mokes??i?? s??naudos');
			$array[] = array('kodas' => '6121', 'pavadinimas' => 'Keli?? mokes??io s??naudos');
			$array[] = array('kodas' => '6122', 'pavadinimas' => 'Nekilnojamojo turto mokes??io s??naudos');
			$array[] = array('kodas' => '6123', 'pavadinimas' => 'Neatskaitomo PVM s??naudos');
			$array[] = array('kodas' => '6124', 'pavadinimas' => 'Aplinkos ter??imo mokes??io s??naudos');
			$array[] = array('kodas' => '6125', 'pavadinimas' => 'Kit?? mokes??i?? s??naudos');
			$array[] = array('kodas' => '613', 'pavadinimas' => 'Turto vert??s suma????jimo s??naudos');
			$array[] = array('kodas' => '6131', 'pavadinimas' => 'Atsarg?? vert??s suma????jimo s??naudos');
			$array[] = array('kodas' => '6132', 'pavadinimas' => 'Nematerialiojo turto vert??s suma????jimo s??naudos');
			$array[] = array('kodas' => '6133', 'pavadinimas' => 'Materialiojo ilgalaikio turto vert??s suma????jimo s??naudos');
			$array[] = array('kodas' => '6134', 'pavadinimas' => 'Kito turto vert??s suma????jimo s??naudos');
			$array[] = array('kodas' => '6139', 'pavadinimas' => 'Anks??iau nukainuoto turto vert??s atstatymai (-)');
			$array[] = array('kodas' => '614', 'pavadinimas' => 'Atid??jim?? s??naudos');
			$array[] = array('kodas' => '615', 'pavadinimas' => 'Kitos bendrosios ir administracin??s s??naudos');
			$array[] = array('kodas' => '6151', 'pavadinimas' => 'Suteikta labdara ir parama');
			$array[] = array('kodas' => '62', 'pavadinimas' => 'Kitos veiklos s??naudos');
			$array[] = array('kodas' => '621', 'pavadinimas' => 'Nuostolis d??l ilgalaikio turto perleidimo');
			$array[] = array('kodas' => '622', 'pavadinimas' => 'Tipinei veiklai nepriskirt?? pardavim?? ir paslaug?? teikimo savikaina');
			$array[] = array('kodas' => '6221', 'pavadinimas' => 'Pagal lizingo sutartis parduodamo turto savikaina');
			$array[] = array('kodas' => '623', 'pavadinimas' => 'Tarpininkavimo veiklos s??naudos');
			$array[] = array('kodas' => '624', 'pavadinimas' => 'Nebaigt?? vykdyti sutar??i?? savikaina');
			$array[] = array('kodas' => '628', 'pavadinimas' => 'Kitos  veiklos s??naudos');
			$array[] = array('kodas' => '63', 'pavadinimas' => 'Finansin??s ir investicin??s veiklos s??naudos');
			$array[] = array('kodas' => '630', 'pavadinimas' => 'Pal??kan?? s??naudos');
			$array[] = array('kodas' => '632', 'pavadinimas' => 'Investicij?? vert??s suma????jimo s??naudos');
			$array[] = array('kodas' => '633', 'pavadinimas' => 'Investicij?? perleidimo nuostolis');
			$array[] = array('kodas' => '635', 'pavadinimas' => 'Baudos ir delspinigiai u?? pav??luotus atsiskaitymus');
			$array[] = array('kodas' => '636', 'pavadinimas' => 'Neigiama valiut?? kurs?? pasikeitimo ??taka');
			$array[] = array('kodas' => '637', 'pavadinimas' => 'Investicij?? perkainojimo s??naudos');
			$array[] = array('kodas' => '6370', 'pavadinimas' => 'Investicij?? nukainojimo s??naudos');
			$array[] = array('kodas' => '6371', 'pavadinimas' => 'Anks??iau nukainot?? investicij?? vert??s atstatymas (-)');
			$array[] = array('kodas' => '6373', 'pavadinimas' => 'Investicij??, premij?? amortizacija');
			$array[] = array('kodas' => '638', 'pavadinimas' => 'Kitos finansin??s ??? investicin??s veiklos s??naudos');
			$array[] = array('kodas' => '6380', 'pavadinimas' => 'Gautin?? sum?? diskontai');
			$array[] = array('kodas' => '64', 'pavadinimas' => 'Netekimai');
			$array[] = array('kodas' => '65', 'pavadinimas' => 'Pelno mokes??iai');
			$array[] = array('kodas' => '651', 'pavadinimas' => 'Ataskaitini?? met?? pelno mokes??iai');
			$array[] = array('kodas' => '652', 'pavadinimas' => 'Pervedimai ?? atid??tuosius pelno mokes??ius');
			$array[] = array('kodas' => '653', 'pavadinimas' => 'Pervedimai i?? atid??t??j?? pelno mokes??i??');
			$array[] = array('kodas' => '69', 'pavadinimas' => 'Pelno paskirstymas');
			$array[] = array('kodas' => '690', 'pavadinimas' => 'Pervedimai ?? kitus rezervus');
			$array[] = array('kodas' => '691', 'pavadinimas' => 'Pervedimai ?? ??statymo nustatytus rezervus');
			$array[] = array('kodas' => '692', 'pavadinimas' => 'Pervedimai ?? kitus rezervus');
			$array[] = array('kodas' => '693', 'pavadinimas' => 'Nepaskirstytasis pelnas');
			$array[] = array('kodas' => '694', 'pavadinimas' => 'Dividendai');
			$array[] = array('kodas' => '696', 'pavadinimas' => 'Kiti paskirstymai');
			return $array;
		}
	}
?>