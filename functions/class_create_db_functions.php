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
			$array[] = array('kodas' => '111', 'pavadinimas' => 'Plėtros darbai');
			$array[] = array('kodas' => '1110', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '1118', 'pavadinimas' => 'Amortizacija (-)');
			$array[] = array('kodas' => '1119', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '112', 'pavadinimas' => 'Prestižas');
			$array[] = array('kodas' => '1120', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '1128', 'pavadinimas' => 'Amortizacija (-)');
			$array[] = array('kodas' => '1129', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '113', 'pavadinimas' => 'Patentai, licencijos');
			$array[] = array('kodas' => '1130', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '1138', 'pavadinimas' => 'Amortizacija (-)');
			$array[] = array('kodas' => '1139', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '114', 'pavadinimas' => 'Programinė įranga');
			$array[] = array('kodas' => '1140', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '1141', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '1143', 'pavadinimas' => 'Programinės įrangos atnaujinimo kapitalizuotos išlaidos');
			$array[] = array('kodas' => '1148', 'pavadinimas' => 'Amortizacija (-)');
			$array[] = array('kodas' => '1149', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '115', 'pavadinimas' => 'Kitas nematerialusis turtas');
			$array[] = array('kodas' => '1150', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '1151', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '1158', 'pavadinimas' => 'Amortizacija (-)');
			$array[] = array('kodas' => '1159', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '12', 'pavadinimas' => 'Materialusis turtas');
			$array[] = array('kodas' => '120', 'pavadinimas' => 'Žemė');
			$array[] = array('kodas' => '1200', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '1201', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '1205', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '1209', 'pavadinimas' => 'Vertės sumažėjimas (-)121');
			$array[] = array('kodas' => '121', 'pavadinimas' => 'Pastatai ir statiniai');
			$array[] = array('kodas' => '1210', 'pavadinimas' => 'Gamybiniai pastatai');
			$array[] = array('kodas' => '12100', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '12101', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12102', 'pavadinimas' => 'Ruošiami naudoti');
			$array[] = array('kodas' => '12103', 'pavadinimas' => 'Kapitalizuotos remonto, rekonstravimo išlaidos');
			$array[] = array('kodas' => '12105', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '12107', 'pavadinimas' => 'Įsigijimo savikainos nusidėvėjimas (-)');
			$array[] = array('kodas' => '12108', 'pavadinimas' => 'Perkainotos dalies nusidėvėjimas (-)');
			$array[] = array('kodas' => '12109', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '1211', 'pavadinimas' => 'Administraciniai pastatai');
			$array[] = array('kodas' => '12110', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '12111', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12112', 'pavadinimas' => 'Ruošiami naudoti');
			$array[] = array('kodas' => '12113', 'pavadinimas' => 'Kapitalizuotos remonto, rekonstravimo išlaidos');
			$array[] = array('kodas' => '12115', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '12117', 'pavadinimas' => 'Įsigijimo savikainos nusidėvėjimas (-)');
			$array[] = array('kodas' => '12118', 'pavadinimas' => 'Perkainotos dalies nusidėvėjimas (-)');
			$array[] = array('kodas' => '12119', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '1212', 'pavadinimas' => 'Kiti pastatai');
			$array[] = array('kodas' => '12120', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '12121', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12122', 'pavadinimas' => 'Ruošiami naudoti');
			$array[] = array('kodas' => '12123', 'pavadinimas' => 'Kapitalizuotos remonto, rekonstravimo išlaidos');
			$array[] = array('kodas' => '12125', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '12127', 'pavadinimas' => 'Įsigijimo savikainos nusidėvėjimas (-)');
			$array[] = array('kodas' => '12128', 'pavadinimas' => 'Perkainotos dalies nusidėvėjimas (-)');
			$array[] = array('kodas' => '12129', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '1213', 'pavadinimas' => 'Statiniai');
			$array[] = array('kodas' => '12130', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '12131', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12132', 'pavadinimas' => 'Ruošiami naudoti');
			$array[] = array('kodas' => '12133', 'pavadinimas' => 'Kapitalizuotos remonto, rekonstravimo išlaidos');
			$array[] = array('kodas' => '12135', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '12137', 'pavadinimas' => 'Įsigijimo savikainos nusidėvėjimas (-)');
			$array[] = array('kodas' => '12138', 'pavadinimas' => 'Perkainotos dalies nusidėvėjimas (-)');
			$array[] = array('kodas' => '12139', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '122', 'pavadinimas' => 'Transporto priemonės');
			$array[] = array('kodas' => '1220', 'pavadinimas' => 'Lengvieji automobiliai');
			$array[] = array('kodas' => '12200', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '12201', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12202', 'pavadinimas' => 'Ruošiamos naudoti');
			$array[] = array('kodas' => '12203', 'pavadinimas' => 'Kapitalizuotos remonto išlaidos');
			$array[] = array('kodas' => '12205', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '12207', 'pavadinimas' => 'Įsigijimo savikainos nusidėvėjimas (-)');
			$array[] = array('kodas' => '12208', 'pavadinimas' => 'Perkainotos dalies nusidėvėjimas (-)');
			$array[] = array('kodas' => '12209', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '1221', 'pavadinimas' => 'Krovininiai automobiliai, priekabos, puspriekabės ir autobusai');
			$array[] = array('kodas' => '12210', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '12211', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12212', 'pavadinimas' => 'Ruošiamos naudoti');
			$array[] = array('kodas' => '12213', 'pavadinimas' => 'Kapitalizuotos remonto išlaidos');
			$array[] = array('kodas' => '12215', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '12217', 'pavadinimas' => 'Įsigijimo savikainos nusidėvėjimas (-)');
			$array[] = array('kodas' => '12218', 'pavadinimas' => 'Perkainotos dalies nusidėvėjimas (-)');
			$array[] = array('kodas' => '12219', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '1222', 'pavadinimas' => 'Kitos transporto priemonės');
			$array[] = array('kodas' => '12220', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '12221', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12222', 'pavadinimas' => 'Ruošiamos naudoti');
			$array[] = array('kodas' => '12223', 'pavadinimas' => 'Kapitalizuotos remonto išlaidos');
			$array[] = array('kodas' => '12225', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '12227', 'pavadinimas' => 'Įsigijimo savikainos nusidėvėjimas (-)');
			$array[] = array('kodas' => '12228', 'pavadinimas' => 'Perkainotos dalies nusidėvėjimas (-)');
			$array[] = array('kodas' => '12229', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '123', 'pavadinimas' => 'Kita įranga, prietaisai, įrankiai ir įrengimai');
			$array[] = array('kodas' => '1231', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '1232', 'pavadinimas' => 'Ruošiami naudoti+');
			$array[] = array('kodas' => '1233', 'pavadinimas' => 'Kapitalizuotos remonto išlaidos');
			$array[] = array('kodas' => '1235', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '1237', 'pavadinimas' => 'Įsigijimo savikainos nusidėvėjimas (-)');
			$array[] = array('kodas' => '1238', 'pavadinimas' => 'Perkainotos dalies nusidėvėjimas (-)');
			$array[] = array('kodas' => '1239', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '124', 'pavadinimas' => 'Mašinos ir įrengimai');
			$array[] = array('kodas' => '1240', 'pavadinimas' => 'Mašinos');
			$array[] = array('kodas' => '12400', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '12401', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12402', 'pavadinimas' => 'Ruošiamos naudoti');
			$array[] = array('kodas' => '12403', 'pavadinimas' => 'Kapitalizuotos remonto išlaidos');
			$array[] = array('kodas' => '12405', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '12407', 'pavadinimas' => 'Įsigijimo savikainos nusidėvėjimas (-)');
			$array[] = array('kodas' => '12408', 'pavadinimas' => 'Perkainotos dalies nusidėvėjimas (-)');
			$array[] = array('kodas' => '12409', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '1241', 'pavadinimas' => 'Įrengimai');
			$array[] = array('kodas' => '12410', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '12411', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '12412', 'pavadinimas' => 'Ruošiami naudoti');
			$array[] = array('kodas' => '12413', 'pavadinimas' => 'Kapitalizuotos remonto išlaidos');
			$array[] = array('kodas' => '12415', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '12417', 'pavadinimas' => 'Įsigijimo savikainos nusidėvėjimas (-)');
			$array[] = array('kodas' => '12418', 'pavadinimas' => 'Perkainotos dalies nusidėvėjimas (-)');
			$array[] = array('kodas' => '12419', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '125', 'pavadinimas' => 'Nebaigta statyba');
			$array[] = array('kodas' => '1250', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '1251', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '1252', 'pavadinimas' => 'Ruošiamos naudoti');
			$array[] = array('kodas' => '1259', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '126', 'pavadinimas' => 'Kitas materialusis turtas');
			$array[] = array('kodas' => '1260', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '1261', 'pavadinimas' => 'Perkainojimas');
			$array[] = array('kodas' => '1262', 'pavadinimas' => 'Ruošiamas naudoti');
			$array[] = array('kodas' => '1263', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '1264', 'pavadinimas' => 'Kapitalizuotos remonto išlaidos');
			$array[] = array('kodas' => '1265', 'pavadinimas' => 'Išsinuomoto turto kapitalizuotos remonto ir rekonstravimo išlaidos');
			$array[] = array('kodas' => '1267', 'pavadinimas' => 'Įsigijimo savikainos nusidėvėjimas (-)');
			$array[] = array('kodas' => '1268', 'pavadinimas' => 'Perkainotos dalies nusidėvėjimas (-)');
			$array[] = array('kodas' => '1269', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '127', 'pavadinimas' => 'Investicinis turtas');
			$array[] = array('kodas' => '1270', 'pavadinimas' => 'Žemė');
			$array[] = array('kodas' => '12700', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '12702', 'pavadinimas' => 'Ruošiama naudoti');
			$array[] = array('kodas' => '12705', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '12709', 'pavadinimas' => 'Vertės padidėjimas (sumažėjimas)');
			$array[] = array('kodas' => '1271', 'pavadinimas' => 'Pastatai');
			$array[] = array('kodas' => '12710', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '12712', 'pavadinimas' => 'Ruošiama naudoti');
			$array[] = array('kodas' => '12715', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '12717', 'pavadinimas' => 'Įsigijimo savikainos nusidėvėjimas');
			$array[] = array('kodas' => '12719', 'pavadinimas' => 'Vertės padidėjimas (sumažėjimas)');
			$array[] = array('kodas' => '16', 'pavadinimas' => 'Finansinis turtas');
			$array[] = array('kodas' => '160', 'pavadinimas' => 'Investicijos į dukterines ir asocijuotas įmones');
			$array[] = array('kodas' => '1600', 'pavadinimas' => 'Investicijos į dukterines įmones');
			$array[] = array('kodas' => '1601', 'pavadinimas' => 'Investicijos į asocijuotas įmones');
			$array[] = array('kodas' => '1609', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '161', 'pavadinimas' => 'Paskolos asocijuotoms ir dukterinėms įmonėms');
			$array[] = array('kodas' => '1610', 'pavadinimas' => 'Paskolos dukterinėms įmonėms');
			$array[] = array('kodas' => '1611', 'pavadinimas' => 'Paskolos asocijuotoms įmonėms');
			$array[] = array('kodas' => '1618', 'pavadinimas' => 'Kontroliuotinos skolos');
			$array[] = array('kodas' => '1619', 'pavadinimas' => 'Abejotinos skolos (-)');
			$array[] = array('kodas' => '162', 'pavadinimas' => 'Po vienerių metų gautinos sumos');
			$array[] = array('kodas' => '1621', 'pavadinimas' => 'Prekybos skolos');
			$array[] = array('kodas' => '1622', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '16221', 'pavadinimas' => 'Ateinančių laikotarpių sąnaudos, pripažintinos patirtomis vėliau nei per kitą ataskaitinį laikotarpį');
			$array[] = array('kodas' => '1623', 'pavadinimas' => 'Lizingo (finansinės nuomos) skolininkai');
			$array[] = array('kodas' => '1624', 'pavadinimas' => 'Po vienerių metų gautinos sumos');
			$array[] = array('kodas' => '1625', 'pavadinimas' => 'Kitos po vienerių metų gautinos sumos');
			$array[] = array('kodas' => '16251', 'pavadinimas' => 'Sukauptos pajamos, apmokėtinos vėliau nei per kitą ataskaitinį laikotarpį');
			$array[] = array('kodas' => '1628', 'pavadinimas' => 'Kontroliuotinos skolos');
			$array[] = array('kodas' => '1629', 'pavadinimas' => 'Abejotinos skolos (-)');
			$array[] = array('kodas' => '163', 'pavadinimas' => 'Kitas finansinis turtas');
			$array[] = array('kodas' => '1631', 'pavadinimas' => 'Kitos investicijos');
			$array[] = array('kodas' => '1632', 'pavadinimas' => 'Ilgalaikės investicijos laikomos iki išpirkimo');
			$array[] = array('kodas' => '16321', 'pavadinimas' => 'Investicijos į Vyriausybės vertybinius popierius');
			$array[] = array('kodas' => '163210', 'pavadinimas' => 'Nominali vertė');
			$array[] = array('kodas' => '163211', 'pavadinimas' => 'Diskontai (-)');
			$array[] = array('kodas' => '163212', 'pavadinimas' => 'Premijos');
			$array[] = array('kodas' => '16322', 'pavadinimas' => 'Investicijos į kitų įmonių išleistas obligacijas');
			$array[] = array('kodas' => '163220', 'pavadinimas' => 'Nominali vertė');
			$array[] = array('kodas' => '163221', 'pavadinimas' => 'Diskontai (-)');
			$array[] = array('kodas' => '163222', 'pavadinimas' => 'Premijos');
			$array[] = array('kodas' => '1633', 'pavadinimas' => 'Kitas ilgalaikis finansinis turtas');
			$array[] = array('kodas' => '1639', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '164', 'pavadinimas' => 'Atidėtojo pelno mokesčio turtas');
			$array[] = array('kodas' => '1641', 'pavadinimas' => 'Atidėtojo pelno mokesčio turtas');
			$array[] = array('kodas' => '1649', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '165', 'pavadinimas' => 'Kitas ilgalaikis turtas');
			$array[] = array('kodas' => '2', 'pavadinimas' => 'TRUMPALAIKIS TURTAS');
			$array[] = array('kodas' => '20', 'pavadinimas' => 'Atsargos, išankstiniai apmokėjimai ir nebaigtos vykdyti sutartys');
			$array[] = array('kodas' => '201', 'pavadinimas' => 'Atsargos');
			$array[] = array('kodas' => '2011', 'pavadinimas' => 'Žaliavos ir komplektavimo gaminiai');
			$array[] = array('kodas' => '20110', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '20119', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '2012', 'pavadinimas' => 'Nebaigta gamyba');
			$array[] = array('kodas' => '20120', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '20129', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '2013', 'pavadinimas' => 'Pagaminta produkcija');
			$array[] = array('kodas' => '20130', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '20139', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '2014', 'pavadinimas' => 'Pirktos prekės, skirtos perparduoti');
			$array[] = array('kodas' => '20140', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '20149', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '2015', 'pavadinimas' => 'Atsarginės dalys');
			$array[] = array('kodas' => '20150', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '20159', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '2016', 'pavadinimas' => 'Kitos atsargos');
			$array[] = array('kodas' => '20160', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '20169', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '2017', 'pavadinimas' => 'Atsargos kelyje');
			$array[] = array('kodas' => '20171', 'pavadinimas' => 'Žaliavos ir komplektavimo gaminiai');
			$array[] = array('kodas' => '201710', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '201719', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '20172', 'pavadinimas' => 'Nebaigta gamyba');
			$array[] = array('kodas' => '201720', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '201729', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '20173', 'pavadinimas' => 'Pagaminta produkcija');
			$array[] = array('kodas' => '201730', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '201739', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '20174', 'pavadinimas' => 'Pirktos prekės, skirtos perparduoti');
			$array[] = array('kodas' => '201740', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '201749', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '20175', 'pavadinimas' => 'Atsarginės dalys');
			$array[] = array('kodas' => '201750', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '201759', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '20176', 'pavadinimas' => 'Kitos atsargos');
			$array[] = array('kodas' => '201760', 'pavadinimas' => 'Įsigijimo savikaina');
			$array[] = array('kodas' => '201769', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '202', 'pavadinimas' => 'Išankstiniai apmokėjimai');
			$array[] = array('kodas' => '2021', 'pavadinimas' => 'Išankstiniai apmokėjimai tiekėjams');
			$array[] = array('kodas' => '2023', 'pavadinimas' => 'Išankstiniai apmokėjimai biudžetui, išskyrus pelno mokestį');
			$array[] = array('kodas' => '2024', 'pavadinimas' => 'Būsimųjų laikotarpių sąnaudos');
			$array[] = array('kodas' => '2029', 'pavadinimas' => 'Abejotinos skolos (-)');
			$array[] = array('kodas' => '203', 'pavadinimas' => 'Nebaigtos vykdyti sutartys');
			$array[] = array('kodas' => '2031', 'pavadinimas' => 'Savikaina');
			$array[] = array('kodas' => '2032', 'pavadinimas' => 'Pripažintas pelnas');
			$array[] = array('kodas' => '2033', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '24', 'pavadinimas' => 'Per vienerius metus gautinos sumos');
			$array[] = array('kodas' => '241', 'pavadinimas' => 'Pirkėjų įsiskolinimas');
			$array[] = array('kodas' => '2411', 'pavadinimas' => 'Pirkėjai');
			$array[] = array('kodas' => '2412', 'pavadinimas' => 'Pirkėjų skola už išsimokėtinai parduotas prekes');
			$array[] = array('kodas' => '2413', 'pavadinimas' => 'Gauti prekybiniai vekseliai');
			$array[] = array('kodas' => '2418', 'pavadinimas' => 'Kontroliuotinos skolos');
			$array[] = array('kodas' => '2419', 'pavadinimas' => 'Abejotinos skolos (-)');
			$array[] = array('kodas' => '242', 'pavadinimas' => 'Dukterinių ir asocijuotų įmonių skolos');
			$array[] = array('kodas' => '2421', 'pavadinimas' => 'Dukterinių įmonių skolos');
			$array[] = array('kodas' => '2422', 'pavadinimas' => 'Asocijuotų įmonių skolos');
			$array[] = array('kodas' => '2428', 'pavadinimas' => 'Kontroliuotinos skolos');
			$array[] = array('kodas' => '2429', 'pavadinimas' => 'Abejotinos skolos (-)');
			$array[] = array('kodas' => '243', 'pavadinimas' => 'Kitos gautinos sumos');
			$array[] = array('kodas' => '2431', 'pavadinimas' => 'Gautinas PVM');
			$array[] = array('kodas' => '24311', 'pavadinimas' => 'Pirkimo PVM');
			$array[] = array('kodas' => '24312', 'pavadinimas' => 'Pirkimo PVM atskaita');
			$array[] = array('kodas' => '24313', 'pavadinimas' => 'Pirkimo PVM atskaitos tikslinimas (-)');
			$array[] = array('kodas' => '24314', 'pavadinimas' => 'Neatskaitomas pirkimo PVM');
			$array[] = array('kodas' => '2432', 'pavadinimas' => 'Biudžeto skola įmonei');
			$array[] = array('kodas' => '2433', 'pavadinimas' => 'Sodros skola įmonei');
			$array[] = array('kodas' => '2434', 'pavadinimas' => 'Kitos gautinos skolos');
			$array[] = array('kodas' => '2435', 'pavadinimas' => 'Atsakingi asmenys');
			$array[] = array('kodas' => '2436', 'pavadinimas' => 'Sukauptos gautinos pajamos');
			$array[] = array('kodas' => '2437', 'pavadinimas' => 'Suteiktos piniginės garantijos');
			$array[] = array('kodas' => '2438', 'pavadinimas' => 'Darbo užmokesčio avansas');
			$array[] = array('kodas' => '2439', 'pavadinimas' => 'Abejotinos skolos (-)');
			$array[] = array('kodas' => '26', 'pavadinimas' => 'Kitas trumpalaikis turtas');
			$array[] = array('kodas' => '261', 'pavadinimas' => 'Trumpalaikės investicijos');
			$array[] = array('kodas' => '2611', 'pavadinimas' => 'Kitų įmonių akcijos');
			$array[] = array('kodas' => '2612', 'pavadinimas' => 'Obligacijos');
			$array[] = array('kodas' => '2613', 'pavadinimas' => 'Kiti vertybiniai popieriai');
			$array[] = array('kodas' => '2615', 'pavadinimas' => 'Išankstiniai apmokėjimai už trumpalaikes investicijas');
			$array[] = array('kodas' => '2619', 'pavadinimas' => 'Vertės sumažėjimas (-)');
			$array[] = array('kodas' => '262', 'pavadinimas' => 'Terminuoti indėliai');
			$array[] = array('kodas' => '263', 'pavadinimas' => 'Kitas trumpalaikis turtas');
			$array[] = array('kodas' => '2631', 'pavadinimas' => 'Kitas trumpalaikis turtas');
			$array[] = array('kodas' => '2632', 'pavadinimas' => 'Iš anksto sumokėtas pelno mokestis');
			$array[] = array('kodas' => '27', 'pavadinimas' => 'Pinigai ir pinigų ekvivalentai');
			$array[] = array('kodas' => '270', 'pavadinimas' => 'Pinigų ekvivalentai');
			$array[] = array('kodas' => '271', 'pavadinimas' => 'Sąskaitos bankuose');
			$array[] = array('kodas' => '2711', 'pavadinimas' => 'Litai');
			$array[] = array('kodas' => '2712', 'pavadinimas' => 'JAV doleriai');
			$array[] = array('kodas' => '2713', 'pavadinimas' => 'Šveicarijos frankai');
			$array[] = array('kodas' => '2714', 'pavadinimas' => 'Rusijos rubliai');
			$array[] = array('kodas' => '2715', 'pavadinimas' => 'Eurai');
			$array[] = array('kodas' => '2716', 'pavadinimas' => 'Kita valiuta (pagal rūšį)');
			$array[] = array('kodas' => '272', 'pavadinimas' => 'Kasa');
			$array[] = array('kodas' => '2721', 'pavadinimas' => 'Litai');
			$array[] = array('kodas' => '2722', 'pavadinimas' => 'JAV doleriai');
			$array[] = array('kodas' => '2723', 'pavadinimas' => 'Šveicarijos frankai');
			$array[] = array('kodas' => '2724', 'pavadinimas' => 'Rusijos rubliai');
			$array[] = array('kodas' => '2725', 'pavadinimas' => 'Eurai');
			$array[] = array('kodas' => '2726', 'pavadinimas' => 'Kita valiuta (pagal rūšį)');
			$array[] = array('kodas' => '273', 'pavadinimas' => '');
			$array[] = array('kodas' => '2731', 'pavadinimas' => 'Litai');
			$array[] = array('kodas' => '2732', 'pavadinimas' => 'JAV doleriai');
			$array[] = array('kodas' => '2735', 'pavadinimas' => 'Eurai');
			$array[] = array('kodas' => '2736', 'pavadinimas' => 'Kita valiuta (pagal rūšį)');
			$array[] = array('kodas' => '279', 'pavadinimas' => 'Įšaldytos sąskaitos (-)');
			$array[] = array('kodas' => '3', 'pavadinimas' => 'NUOSAVAS KAPITALAS');
			$array[] = array('kodas' => '30', 'pavadinimas' => 'Kapitalas ');
			$array[] = array('kodas' => '301', 'pavadinimas' => 'Įstatinis pasirašytasis kapitalas');
			$array[] = array('kodas' => '3011', 'pavadinimas' => 'Paprastosios akcijos');
			$array[] = array('kodas' => '3012', 'pavadinimas' => 'Privilegijuotosios akcijos');
			$array[] = array('kodas' => '3013', 'pavadinimas' => 'Darbuotojų akcijos');
			$array[] = array('kodas' => '302', 'pavadinimas' => 'Pasirašytasis neapmokėtas pelnas');
			$array[] = array('kodas' => '303', 'pavadinimas' => 'Akcijų priedai');
			$array[] = array('kodas' => '304', 'pavadinimas' => 'Savos akcijos (-)');
			$array[] = array('kodas' => '32', 'pavadinimas' => 'Perkainojimo rezervas (rezultatai)');
			$array[] = array('kodas' => '321', 'pavadinimas' => 'Ilgalaikio materialiojo turto perkainojimo rezultatas');
			$array[] = array('kodas' => '322', 'pavadinimas' => 'Ilgalaikio finansinio turto perkainojimo rezultatas');
			$array[] = array('kodas' => '331', 'pavadinimas' => 'Privalomasis ');
			$array[] = array('kodas' => '332', 'pavadinimas' => 'Savom akcijoms įsigyti');
			$array[] = array('kodas' => '333', 'pavadinimas' => 'Kiti rezervai');
			$array[] = array('kodas' => '34', 'pavadinimas' => 'Nepaskirstytasis pelnas (nuostoliai)');
			$array[] = array('kodas' => '341', 'pavadinimas' => 'Ataskaitinių metų nepaskirstytasis pelnas (nuostoliai)');
			$array[] = array('kodas' => '3411', 'pavadinimas' => 'Pelno (nuostolių) ataskaitoje pripažintas ataskaitinių metų pelnas (nuostoliai)');
			$array[] = array('kodas' => '3412', 'pavadinimas' => 'Pelno (nuostolių) ataskaitoje nepripažintas ataskaitinių metų pelnas (nuostoliai)');
			$array[] = array('kodas' => '34121', 'pavadinimas' => 'Pelnas (nuostoliai) iš savų akcijų perleidimo');
			$array[] = array('kodas' => '34122', 'pavadinimas' => 'Pelnas (nuostoliai) iš savų akcijų anuliavimo');
			$array[] = array('kodas' => '342', 'pavadinimas' => 'Ankstesnių metų nepaskirstytasis pelnas (nuostoliai)');
			$array[] = array('kodas' => '3421', 'pavadinimas' => 'Pelno (nuostolių) ataskaitoje pripažintas ankstesnių metų pelnas (nuostoliai)');
			$array[] = array('kodas' => '3422', 'pavadinimas' => 'Pelno (nuostolių) apskaitoje pripažintas ankstesniųjų metų pelnas (nuostoliai)');
			$array[] = array('kodas' => '3423', 'pavadinimas' => 'Ankstesniųjų metų apskaitos politikos keitimo pelnas (nuostoliai)');
			$array[] = array('kodas' => '34231', 'pavadinimas' => 'Lyginamojo laikotarpio apskaitos politikos keitimo pelnas (nuostoliai)');
			$array[] = array('kodas' => '34232', 'pavadinimas' => 'Ankstesnių už lyginamąjį laikotarpių apskaitos politikos keitimo pelnas (nuostoliai)');
			$array[] = array('kodas' => '3424', 'pavadinimas' => 'Ankstesniųjų metų esminių klaidų taisymo pelnas (nuostoliai)');
			$array[] = array('kodas' => '34241', 'pavadinimas' => 'Lyginamojo laikotarpio  esminių klaidų taisymo pelnas (nuostoliai)');
			$array[] = array('kodas' => '34242', 'pavadinimas' => 'Ankstesniųjų už lyginamąjį laikotarpių esminių klaidų taisymo pelnas (nuostoliai)');
			$array[] = array('kodas' => '3425', 'pavadinimas' => 'Po paskirstymo likęs nepaskirstytas pelnas (nuostoliai)');
			$array[] = array('kodas' => '35', 'pavadinimas' => 'Dotacijos ir subsidijos');
			$array[] = array('kodas' => '3501', 'pavadinimas' => 'Gautinos dotacijos ir subsidijos');
			$array[] = array('kodas' => '35010', 'pavadinimas' => 'Su turtu susijusios gautinos dotacijos ir subsidijos');
			$array[] = array('kodas' => '35011', 'pavadinimas' => 'Su pajamomis susijusios gautinos dotacijos ir subsidijos');
			$array[] = array('kodas' => '3511', 'pavadinimas' => 'Gautos dotacijos ir subsidijos');
			$array[] = array('kodas' => '35110', 'pavadinimas' => 'Su turtu susijusios gautos dotacijos ir subsidijos');
			$array[] = array('kodas' => '35111', 'pavadinimas' => 'Su pajamomis susijusios gautos dotacijos ir subsidijos');
			$array[] = array('kodas' => '390', 'pavadinimas' => 'Suvestinė sąskaita');
			$array[] = array('kodas' => '3900', 'pavadinimas' => 'Gamybos suvestinė');
			$array[] = array('kodas' => '3901', 'pavadinimas' => 'Pajamų ir sąnaudų suvestinė');
			$array[] = array('kodas' => '3902', 'pavadinimas' => 'Turto ir nuosavybės suvestinė');
			$array[] = array('kodas' => '3908', 'pavadinimas' => 'Laukimo sąskaita');
			$array[] = array('kodas' => '4', 'pavadinimas' => 'MOKĖTINOS SUMOS IR ĮSIPAREIGOJIMAI');
			$array[] = array('kodas' => '40', 'pavadinimas' => 'Po vienerių metų mokėtinos sumos ir ilgalaikiai įsipareigojimai');
			$array[] = array('kodas' => '401', 'pavadinimas' => 'Finansiniai įsipareigojimai');
			$array[] = array('kodas' => '4011', 'pavadinimas' => 'Lizingo (finansinės nuomos) ar panašūs įsipareigojimai');
			$array[] = array('kodas' => '4012', 'pavadinimas' => 'Kredito įstaigos');
			$array[] = array('kodas' => '4013', 'pavadinimas' => 'Kitos finansinės skolos');
			$array[] = array('kodas' => '40130', 'pavadinimas' => 'Įsipareigojimai dukterinėms įmonėms');
			$array[] = array('kodas' => '40131', 'pavadinimas' => 'Įsipareigojimai asocijuotoms įmonėms');
			$array[] = array('kodas' => '40132', 'pavadinimas' => 'Finansiniai įsipareigojimai kitiems kreditoriams');
			$array[] = array('kodas' => '401321', 'pavadinimas' => 'Finansiniai įsipareigojimai kitiems kreditoriams ');
			$array[] = array('kodas' => '401322', 'pavadinimas' => 'Finansinių įsipareigojimų kitiems kreditoriams diskontai (-)');
			$array[] = array('kodas' => '401323', 'pavadinimas' => 'Finansinių įsipareigojimų kitiems kreditoriams premijos');
			$array[] = array('kodas' => '402', 'pavadinimas' => 'Įsipareigojimai tiekėjams ');
			$array[] = array('kodas' => '403', 'pavadinimas' => 'Gauti išankstiniai apmokėjimai');
			$array[] = array('kodas' => '4031', 'pavadinimas' => 'Ateinančių laikotarpių pajamos, pripažintos uždirbtomis vėliau nei per ateinančius metus');
			$array[] = array('kodas' => '4032', 'pavadinimas' => 'Gauti išankstiniai apmokėjimai už vėliau nei per ateinančius metus pateiktas prekes');
			$array[] = array('kodas' => '4033', 'pavadinimas' => 'Gauti išankstiniai apmokėjimai už vėliau nei per ateinančius metus suteiktas paslaugas');
			$array[] = array('kodas' => '404', 'pavadinimas' => 'Atidėjimai');
			$array[] = array('kodas' => '4041', 'pavadinimas' => 'Įsipareigojimų ir reikalavimų padengimo atidėjimai');
			$array[] = array('kodas' => '4042', 'pavadinimas' => 'Pensijų ir panašių įsipareigojimų atidėjimai');
			$array[] = array('kodas' => '4043', 'pavadinimas' => 'Kiti atidėjimai');
			$array[] = array('kodas' => '405', 'pavadinimas' => 'Atidėtieji mokesčiai');
			$array[] = array('kodas' => '4051', 'pavadinimas' => 'Atidėtojo pelno mokesčio įsipareigojimai');
			$array[] = array('kodas' => '406', 'pavadinimas' => 'Kitos mokėtinos sumos ir ilgalaikiai įsipareigojimai');
			$array[] = array('kodas' => '4061', 'pavadinimas' => 'Įsipareigojimai pagal mokestinės paskolos sutartis');
			$array[] = array('kodas' => '4062', 'pavadinimas' => 'Kiti įsipareigojimai');
			$array[] = array('kodas' => '4063', 'pavadinimas' => 'Sukauptos sąnaudos, apmokėtinos vėliau nei per ateinančius metus');
			$array[] = array('kodas' => '44', 'pavadinimas' => 'Per vienerius metus mokėtinos sumos ir trumpalaikiai įsipareigojimai');
			$array[] = array('kodas' => '441', 'pavadinimas' => 'Ilgalaikių įsipareigojimų einamųjų metų dalis');
			$array[] = array('kodas' => '4411', 'pavadinimas' => 'Lizingo (finansinės nuomos) ir kiti panašūs įsipareigojimai');
			$array[] = array('kodas' => '44111', 'pavadinimas' => 'Lizingo (finansinės nuomos) ir kiti panašūs įsipareigojimai');
			$array[] = array('kodas' => '44112', 'pavadinimas' => 'Lizingo (finansinės nuomos) ir kiti panašūs įsipareigojimai pagal gautas sąskaitas');
			$array[] = array('kodas' => '4412', 'pavadinimas' => 'Prekybiniai įsipareigojimai');
			$array[] = array('kodas' => '4413', 'pavadinimas' => 'Kiti įsipareigojimai');
			$array[] = array('kodas' => '442', 'pavadinimas' => 'Finansiniai įsipareigojimai');
			$array[] = array('kodas' => '4421', 'pavadinimas' => 'Kredito įstaigoms');
			$array[] = array('kodas' => '44210', 'pavadinimas' => 'Iš kredito įstaigų gautos paskolos');
			$array[] = array('kodas' => '44211', 'pavadinimas' => 'Išduoti finansiniai vekseliai');
			$array[] = array('kodas' => '4422', 'pavadinimas' => 'Kiti finansiniai įsipareigojimai');
			$array[] = array('kodas' => '44220', 'pavadinimas' => 'Įsipareigojimai dukterinėms įmonėms');
			$array[] = array('kodas' => '44221', 'pavadinimas' => 'Įsipareigojimai asocijuotoms įmonėms');
			$array[] = array('kodas' => '443', 'pavadinimas' => 'Įsipareigojimai tiekėjams');
			$array[] = array('kodas' => '4430', 'pavadinimas' => 'Įsipareigojimai prekių ir paslaugų tiekėjams');
			$array[] = array('kodas' => '4431', 'pavadinimas' => 'Išduoti prekybiniai vekseliai');
			$array[] = array('kodas' => '4432', 'pavadinimas' => 'Įsipareigojimai pagal gautinas sąskaitas');
			$array[] = array('kodas' => '4433', 'pavadinimas' => 'Nefaktūruoti pirkimai');
			$array[] = array('kodas' => '4438', 'pavadinimas' => 'Pirkimo diskontai (-)');
			$array[] = array('kodas' => '444', 'pavadinimas' => 'Gauti išankstiniai apmokėjimai');
			$array[] = array('kodas' => '4441', 'pavadinimas' => 'Ateinančių laikotarpių pajamos, pripažintinos uždirbtomis per ateinančius metus');
			$array[] = array('kodas' => '4442', 'pavadinimas' => 'Gauti išankstiniai apmokėjimai už per ateinančius metus pateiktas prekes');
			$array[] = array('kodas' => '4443', 'pavadinimas' => 'Gauti išankstiniai apmokėjimai už per ateinančius metus suteiktas paslaugas');
			$array[] = array('kodas' => '445', 'pavadinimas' => 'Pelno mokesčio įsipareigojimai');
			$array[] = array('kodas' => '446', 'pavadinimas' => 'Su darbo santykiai susiję įsipareigojimai');
			$array[] = array('kodas' => '4461', 'pavadinimas' => 'Mokėtinas darbo užmokestis');
			$array[] = array('kodas' => '4462', 'pavadinimas' => 'Mokėtinas gyventojų pajamų mokestis');
			$array[] = array('kodas' => '4463', 'pavadinimas' => 'Mokėtinos socialinio draudimo įmokos');
			$array[] = array('kodas' => '4464', 'pavadinimas' => 'Mokėtinos Garantinio fondo įmokos');
			$array[] = array('kodas' => '4465', 'pavadinimas' => 'Kitos išmokos darbuotojams');
			$array[] = array('kodas' => '4466', 'pavadinimas' => 'Atostoginių kaupimai');
			$array[] = array('kodas' => '447', 'pavadinimas' => 'Atidėjimai');
			$array[] = array('kodas' => '4471', 'pavadinimas' => 'Įsipareigojimų ir reikalavimų padengimo atidėjimai');
			$array[] = array('kodas' => '4473', 'pavadinimas' => 'Kiti atidėjimai');
			$array[] = array('kodas' => '448', 'pavadinimas' => 'Kitos mokėtinos sumos ir trumpalaikiai įsipareigojimai');
			$array[] = array('kodas' => '4481', 'pavadinimas' => 'Mokėtini dividendai ir kiti įsipareigojimai akcininkams');
			$array[] = array('kodas' => '4482', 'pavadinimas' => 'Mokėtinos tantjemos');
			$array[] = array('kodas' => '4483', 'pavadinimas' => 'Sukauptos sąnaudos, apmokėjimai per ateinančius metus');
			$array[] = array('kodas' => '4484', 'pavadinimas' => 'Mokėtinas PVM ');
			$array[] = array('kodas' => '44841', 'pavadinimas' => 'Mokėtinas pardavimo PVM');
			$array[] = array('kodas' => '44843', 'pavadinimas' => 'Pardavimo PVM koregavimai (-)');
			$array[] = array('kodas' => '4485', 'pavadinimas' => 'Kiti mokėti mokesčiai biudžetui');
			$array[] = array('kodas' => '4488', 'pavadinimas' => 'Kitos mokėtinos sumos');
			$array[] = array('kodas' => '44880', 'pavadinimas' => 'Įsipareigojimai dėl neįasmenintai gautų pinigų');
			$array[] = array('kodas' => '44881', 'pavadinimas' => 'Gautos piniginės garantijos');
			$array[] = array('kodas' => '44882', 'pavadinimas' => 'Įsipareigojimai pagal nebaigtas vykdyti sutartis');
			$array[] = array('kodas' => '44886', 'pavadinimas' => 'Kitos mokėtinos sumos');
			$array[] = array('kodas' => '5', 'pavadinimas' => 'PAJAMOS');
			$array[] = array('kodas' => '50', 'pavadinimas' => 'Pardavimo pajamos');
			$array[] = array('kodas' => '500', 'pavadinimas' => 'Pardavimo pajamos');
			$array[] = array('kodas' => '5001', 'pavadinimas' => 'Pardavimo pajamos');
			$array[] = array('kodas' => '5002', 'pavadinimas' => 'Pardavimai išsimokėtinai');
			$array[] = array('kodas' => '5003', 'pavadinimas' => 'Pripažintas pelnas iš pardavimų išsimokėtinai');
			$array[] = array('kodas' => '5004', 'pavadinimas' => 'Prekių mainų pajamos');
			$array[] = array('kodas' => '5005', 'pavadinimas' => 'Suteiktų paslaugų pajamos');
			$array[] = array('kodas' => '5006', 'pavadinimas' => 'Paslaugų mainų pajamos');
			$array[] = array('kodas' => '509', 'pavadinimas' => 'Nuolaidos, grąžinimai');
			$array[] = array('kodas' => '5090', 'pavadinimas' => 'Pardavimo nuolaidos (-)');
			$array[] = array('kodas' => '5091', 'pavadinimas' => 'Parduotų prekių grąžinimai (-)');
			$array[] = array('kodas' => '5092', 'pavadinimas' => 'Parduotų prekių nukainojimai (-)');
			$array[] = array('kodas' => '5093', 'pavadinimas' => 'Pardavimų diskontai (-)');
			$array[] = array('kodas' => '5094', 'pavadinimas' => 'Nepanaudoti pardavimų diskontai');
			$array[] = array('kodas' => '52', 'pavadinimas' => 'Kitos veiklos pajamos');
			$array[] = array('kodas' => '5211', 'pavadinimas' => 'Pelnas dėl ilgalaikio turto perleidimo');
			$array[] = array('kodas' => '5212', 'pavadinimas' => 'Tipinei veiklai nepriskirtų pardavimų ir paslaugų pajamos');
			$array[] = array('kodas' => '52120', 'pavadinimas' => 'Turto pardavimo pagal lizingo sutartis pajamos');
			$array[] = array('kodas' => '52121', 'pavadinimas' => 'Reklamos pardavimo pajamos');
			$array[] = array('kodas' => '52122', 'pavadinimas' => 'Nuomos pajamos');
			$array[] = array('kodas' => '52123', 'pavadinimas' => 'Komisinių pajamos');
			$array[] = array('kodas' => '52124', 'pavadinimas' => 'Tipinei veiklai nepriskirtų mainų pajamos');
			$array[] = array('kodas' => '5213', 'pavadinimas' => 'Nebaigtų vykdyti sutarčių pajamos');
			$array[] = array('kodas' => '5214', 'pavadinimas' => 'Negauta kitos veiklos pajamas kompensuojančių dotacijų ir subsidijų amortizuota dalis');
			$array[] = array('kodas' => '5228', 'pavadinimas' => 'Kitos netipinės veiklos pajamos');
			$array[] = array('kodas' => '53', 'pavadinimas' => 'Finansinės – investicinės veikos pajamos');
			$array[] = array('kodas' => '532', 'pavadinimas' => 'Dividendų pajamos');
			$array[] = array('kodas' => '533', 'pavadinimas' => 'Baudos ir delspinigiai už pradelstą pirkėjų įsiskolinimą');
			$array[] = array('kodas' => '534', 'pavadinimas' => 'Investicijų perleidimo pelnas');
			$array[] = array('kodas' => '535', 'pavadinimas' => 'Palūkanų pajamos');
			$array[] = array('kodas' => '5350', 'pavadinimas' => 'Palūkanos už indėlius');
			$array[] = array('kodas' => '5351', 'pavadinimas' => 'Palūkanos už sąskaitas bankuose');
			$array[] = array('kodas' => '5352', 'pavadinimas' => 'Lizingo palūkanos');
			$array[] = array('kodas' => '5353', 'pavadinimas' => 'Palūkanos už vertybinius popierius');
			$array[] = array('kodas' => '5354', 'pavadinimas' => 'Palūkanos už išsimokėtinai parduotas prekes');
			$array[] = array('kodas' => '536', 'pavadinimas' => 'Teigiama valiutų kursų pasikeitimo įtaka');
			$array[] = array('kodas' => '537', 'pavadinimas' => 'Mokėtinų sumų diskontai ir nuolaidos');
			$array[] = array('kodas' => '538', 'pavadinimas' => 'Investicijų perkainojimo pajamos');
			$array[] = array('kodas' => '539', 'pavadinimas' => 'Kitos finansinės – investicinės veiklos pajamos');
			$array[] = array('kodas' => '54', 'pavadinimas' => 'Pagautė');
			$array[] = array('kodas' => '59', 'pavadinimas' => 'Pelno paskirstymas');
			$array[] = array('kodas' => '591', 'pavadinimas' => 'Praėjusių metų nepaskirstytasis pelnas');
			$array[] = array('kodas' => '592', 'pavadinimas' => 'Pervedimai iš rezervų');
			$array[] = array('kodas' => '593', 'pavadinimas' => 'Nepaskirstytasis nuostolis');
			$array[] = array('kodas' => '594', 'pavadinimas' => 'Akcininkų įnašai nuostoliams padengti');
			$array[] = array('kodas' => '595', 'pavadinimas' => 'Akcijų priedų mažinimas');
			$array[] = array('kodas' => '6', 'pavadinimas' => 'SĄNAUDOS');
			$array[] = array('kodas' => '60', 'pavadinimas' => 'Parduotų prekių ir suteiktų paslaugų savikaina');
			$array[] = array('kodas' => '600', 'pavadinimas' => 'Parduotų prekių ir suteiktų paslaugų savikaina');
			$array[] = array('kodas' => '6000', 'pavadinimas' => 'Parduotų prekių savikaina');
			$array[] = array('kodas' => '60000', 'pavadinimas' => 'Išsimokėtinai parduotų prekių savikaina');
			$array[] = array('kodas' => '6001', 'pavadinimas' => 'Suteiktų paslaugų savikaina');
			$array[] = array('kodas' => '6002', 'pavadinimas' => 'Pirkimai');
			$array[] = array('kodas' => '6003', 'pavadinimas' => 'Tiesioginės gamybos išlaidos');
			$array[] = array('kodas' => '60031', 'pavadinimas' => 'Pagrindinės žaliavos');
			$array[] = array('kodas' => '60032', 'pavadinimas' => 'Tiesioginis darbo užmokestis');
			$array[] = array('kodas' => '60033', 'pavadinimas' => 'Socialinis draudimas nuo tiesioginio darbo užmokesčio');
			$array[] = array('kodas' => '6004', 'pavadinimas' => 'Netiesioginės gamybos išlaidos');
			$array[] = array('kodas' => '60041', 'pavadinimas' => 'Pagalbinės žaliavos');
			$array[] = array('kodas' => '60042', 'pavadinimas' => 'Pagalbinių darbininkų darbo užmokestis');
			$array[] = array('kodas' => '60043', 'pavadinimas' => 'Socialinis draudimas nuo pagalbinių darbininkų darbo užmokesčio');
			$array[] = array('kodas' => '60044', 'pavadinimas' => 'Gamybos įrenginių nusidėvėjimas');
			$array[] = array('kodas' => '60045', 'pavadinimas' => 'Gamybinių pastatų nusidėvėjimas');
			$array[] = array('kodas' => '60046', 'pavadinimas' => 'Netiesioginės paslaugos');
			$array[] = array('kodas' => '6005', 'pavadinimas' => 'Atsargų padidėjimas / sumažėjimas');
			$array[] = array('kodas' => '60051', 'pavadinimas' => 'Žaliavos ir komplektuojamieji gaminiai');
			$array[] = array('kodas' => '60052', 'pavadinimas' => 'Nebaigta gamyba');
			$array[] = array('kodas' => '60053', 'pavadinimas' => 'Pagaminta produkcija');
			$array[] = array('kodas' => '60054', 'pavadinimas' => 'Pirktos prekės, skirtos perparduoti');
			$array[] = array('kodas' => '609', 'pavadinimas' => 'Nuolaidos, grąžinimai (-)');
			$array[] = array('kodas' => '6090', 'pavadinimas' => 'Pirkimų diskontai (-)');
			$array[] = array('kodas' => '6091', 'pavadinimas' => 'Pirkimų diskontų praradimas');
			$array[] = array('kodas' => '6092', 'pavadinimas' => 'Pirkimų nuolaidos (-)');
			$array[] = array('kodas' => '6093', 'pavadinimas' => 'Pirktų prekių grąžinimai (-)');
			$array[] = array('kodas' => '6094', 'pavadinimas' => 'Pirktų prekių nukainojimai (-)');
			$array[] = array('kodas' => '61', 'pavadinimas' => 'Veiklos sąnaudos');
			$array[] = array('kodas' => '610', 'pavadinimas' => 'Pardavimų sąnaudos');
			$array[] = array('kodas' => '6100', 'pavadinimas' => 'Komisinių tretiesiems asmenims sąnaudos');
			$array[] = array('kodas' => '6101', 'pavadinimas' => 'Pardavimų paslaugų sąnaudos');
			$array[] = array('kodas' => '61010', 'pavadinimas' => 'Baudos ir netesybos už nekokybiškų prekių pardavimą');
			$array[] = array('kodas' => '61011', 'pavadinimas' => 'Atidėjimų garantiniam parduotų prekių remontui sąnaudos');
			$array[] = array('kodas' => '61012', 'pavadinimas' => 'Nepanaudotų atidėjimų garantiniam parduotų prekių remontui atstatymas (-)');
			$array[] = array('kodas' => '61013', 'pavadinimas' => 'Prekių atsargų draudimo sąnaudos');
			$array[] = array('kodas' => '6102', 'pavadinimas' => 'Skelbimų ir reklamos sąnaudos');
			$array[] = array('kodas' => '6103', 'pavadinimas' => 'Darbuotojų darbo užmokesčio ir su juo susijusios sąnaudos');
			$array[] = array('kodas' => '6104', 'pavadinimas' => 'Natūralios prekių netekties sąnaudos');
			$array[] = array('kodas' => '6105', 'pavadinimas' => 'Prekybinės įrangos nusidėvėjimo sąnaudos');
			$array[] = array('kodas' => '6108', 'pavadinimas' => 'Kitos pardavimų sąnaudos');
			$array[] = array('kodas' => '6109', 'pavadinimas' => 'Gautos nuolaidos (-)');
			$array[] = array('kodas' => '611', 'pavadinimas' => 'Bendrosios ir administracinės sąnaudos');
			$array[] = array('kodas' => '6110', 'pavadinimas' => 'Nuomos sąnaudos');
			$array[] = array('kodas' => '6111', 'pavadinimas' => 'Remonto ir eksploatavimo sąnaudos');
			$array[] = array('kodas' => '6112', 'pavadinimas' => 'Mokėjimų tretiesiems asmenims sąnaudos');
			$array[] = array('kodas' => '6113', 'pavadinimas' => 'Draudimo sąnaudos');
			$array[] = array('kodas' => '6114', 'pavadinimas' => 'Darbuotojų darbo užmokestis ir su juo susijusios sąnaudos');
			$array[] = array('kodas' => '61140', 'pavadinimas' => 'Darbuotojų darbo užmokesčio sąnaudos');
			$array[] = array('kodas' => '61141', 'pavadinimas' => 'Darbuotojų socialinio draudimo sąnaudos');
			$array[] = array('kodas' => '6115', 'pavadinimas' => 'Ilgalaikio turto nusidėvėjimo ir amortizacijos sąnaudos');
			$array[] = array('kodas' => '61150', 'pavadinimas' => 'Ilgalaikio materialiojo turto nusidėvėjimo sąnaudos');
			$array[] = array('kodas' => '61151', 'pavadinimas' => 'Nematerialiojo turto amortizacijos sąnaudos');
			$array[] = array('kodas' => '61152', 'pavadinimas' => 'Dotacijų ir subsidijų, susijusių su ilgalaikiu turtu, amortizuota dalis (-)');
			$array[] = array('kodas' => '6117', 'pavadinimas' => 'Abejotinų skolų sąnaudos');
			$array[] = array('kodas' => '61170', 'pavadinimas' => 'Abejotinų skolų įvertinimo sąnaudos');
			$array[] = array('kodas' => '61171', 'pavadinimas' => 'Abejotinų skolų atstatymas (-)');
			$array[] = array('kodas' => '6118', 'pavadinimas' => 'Įvairios bendrosios sąnaudos');
			$array[] = array('kodas' => '61181', 'pavadinimas' => 'Atsargų (išskyrus prekes) natūralios netekties sąnaudos');
			$array[] = array('kodas' => '61183', 'pavadinimas' => 'Įmonės reklamos sąnaudos');
			$array[] = array('kodas' => '6119', 'pavadinimas' => 'Gautos nuolaidos (-)');
			$array[] = array('kodas' => '612', 'pavadinimas' => 'Veiklos mokesčių sąnaudos');
			$array[] = array('kodas' => '6121', 'pavadinimas' => 'Kelių mokesčio sąnaudos');
			$array[] = array('kodas' => '6122', 'pavadinimas' => 'Nekilnojamojo turto mokesčio sąnaudos');
			$array[] = array('kodas' => '6123', 'pavadinimas' => 'Neatskaitomo PVM sąnaudos');
			$array[] = array('kodas' => '6124', 'pavadinimas' => 'Aplinkos teršimo mokesčio sąnaudos');
			$array[] = array('kodas' => '6125', 'pavadinimas' => 'Kitų mokesčių sąnaudos');
			$array[] = array('kodas' => '613', 'pavadinimas' => 'Turto vertės sumažėjimo sąnaudos');
			$array[] = array('kodas' => '6131', 'pavadinimas' => 'Atsargų vertės sumažėjimo sąnaudos');
			$array[] = array('kodas' => '6132', 'pavadinimas' => 'Nematerialiojo turto vertės sumažėjimo sąnaudos');
			$array[] = array('kodas' => '6133', 'pavadinimas' => 'Materialiojo ilgalaikio turto vertės sumažėjimo sąnaudos');
			$array[] = array('kodas' => '6134', 'pavadinimas' => 'Kito turto vertės sumažėjimo sąnaudos');
			$array[] = array('kodas' => '6139', 'pavadinimas' => 'Anksčiau nukainuoto turto vertės atstatymai (-)');
			$array[] = array('kodas' => '614', 'pavadinimas' => 'Atidėjimų sąnaudos');
			$array[] = array('kodas' => '615', 'pavadinimas' => 'Kitos bendrosios ir administracinės sąnaudos');
			$array[] = array('kodas' => '6151', 'pavadinimas' => 'Suteikta labdara ir parama');
			$array[] = array('kodas' => '62', 'pavadinimas' => 'Kitos veiklos sąnaudos');
			$array[] = array('kodas' => '621', 'pavadinimas' => 'Nuostolis dėl ilgalaikio turto perleidimo');
			$array[] = array('kodas' => '622', 'pavadinimas' => 'Tipinei veiklai nepriskirtų pardavimų ir paslaugų teikimo savikaina');
			$array[] = array('kodas' => '6221', 'pavadinimas' => 'Pagal lizingo sutartis parduodamo turto savikaina');
			$array[] = array('kodas' => '623', 'pavadinimas' => 'Tarpininkavimo veiklos sąnaudos');
			$array[] = array('kodas' => '624', 'pavadinimas' => 'Nebaigtų vykdyti sutarčių savikaina');
			$array[] = array('kodas' => '628', 'pavadinimas' => 'Kitos  veiklos sąnaudos');
			$array[] = array('kodas' => '63', 'pavadinimas' => 'Finansinės ir investicinės veiklos sąnaudos');
			$array[] = array('kodas' => '630', 'pavadinimas' => 'Palūkanų sąnaudos');
			$array[] = array('kodas' => '632', 'pavadinimas' => 'Investicijų vertės sumažėjimo sąnaudos');
			$array[] = array('kodas' => '633', 'pavadinimas' => 'Investicijų perleidimo nuostolis');
			$array[] = array('kodas' => '635', 'pavadinimas' => 'Baudos ir delspinigiai už pavėluotus atsiskaitymus');
			$array[] = array('kodas' => '636', 'pavadinimas' => 'Neigiama valiutų kursų pasikeitimo įtaka');
			$array[] = array('kodas' => '637', 'pavadinimas' => 'Investicijų perkainojimo sąnaudos');
			$array[] = array('kodas' => '6370', 'pavadinimas' => 'Investicijų nukainojimo sąnaudos');
			$array[] = array('kodas' => '6371', 'pavadinimas' => 'Anksčiau nukainotų investicijų vertės atstatymas (-)');
			$array[] = array('kodas' => '6373', 'pavadinimas' => 'Investicijų, premijų amortizacija');
			$array[] = array('kodas' => '638', 'pavadinimas' => 'Kitos finansinės – investicinės veiklos sąnaudos');
			$array[] = array('kodas' => '6380', 'pavadinimas' => 'Gautinų sumų diskontai');
			$array[] = array('kodas' => '64', 'pavadinimas' => 'Netekimai');
			$array[] = array('kodas' => '65', 'pavadinimas' => 'Pelno mokesčiai');
			$array[] = array('kodas' => '651', 'pavadinimas' => 'Ataskaitinių metų pelno mokesčiai');
			$array[] = array('kodas' => '652', 'pavadinimas' => 'Pervedimai į atidėtuosius pelno mokesčius');
			$array[] = array('kodas' => '653', 'pavadinimas' => 'Pervedimai iš atidėtųjų pelno mokesčių');
			$array[] = array('kodas' => '69', 'pavadinimas' => 'Pelno paskirstymas');
			$array[] = array('kodas' => '690', 'pavadinimas' => 'Pervedimai į kitus rezervus');
			$array[] = array('kodas' => '691', 'pavadinimas' => 'Pervedimai į įstatymo nustatytus rezervus');
			$array[] = array('kodas' => '692', 'pavadinimas' => 'Pervedimai į kitus rezervus');
			$array[] = array('kodas' => '693', 'pavadinimas' => 'Nepaskirstytasis pelnas');
			$array[] = array('kodas' => '694', 'pavadinimas' => 'Dividendai');
			$array[] = array('kodas' => '696', 'pavadinimas' => 'Kiti paskirstymai');
			return $array;
		}
	}
?>