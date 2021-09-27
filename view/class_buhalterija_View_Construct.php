<?php 
	class class_buhalterija_View_Construct {
		private $meniu_Items = array('Operacijų Žurnalas', 'Įmonių Sąrašas', 'Pinigų Judėjimas', 'Balanso Ataskaita');
		private $curent_URL;


		function __construct() {
			global $wp; 
			$this->curent_URL = add_query_arg( $wp->query_vars, null );
			wp_enqueue_script('popper', '/wp-content/plugins/freya_Buhalterija/js/popper.min.js');
			wp_enqueue_script('my_custom_script', '/wp-content/plugins/freya_Buhalterija/js/bootstrap.min.js');
			wp_enqueue_script('my_custom_script2', '/wp-content/plugins/freya_Buhalterija/js/jquery-ui.min.js');
			wp_enqueue_script('my_custom_script3', '/wp-content/plugins/freya_Buhalterija/js/buhalterija.js');
			wp_enqueue_script('my_custom_script4', '/wp-content/plugins/freya_Buhalterija/js/jquery.validate.min.js');
			wp_enqueue_script('my_custom_script5', '/wp-content/plugins/freya_Buhalterija/js/localization/messages_lt.js');
			wp_enqueue_script('my_custom_script6', '/wp-content/plugins/freya_Buhalterija/js/bootstrap-select.min.js');


			wp_enqueue_style('my-css', '/wp-content/plugins/freya_Buhalterija/css/bootstrap.css');
			wp_enqueue_style('my-css2', '/wp-content/plugins/freya_Buhalterija/css/jquery-ui.min.css');
			wp_enqueue_style('my-css3', '/wp-content/plugins/freya_Buhalterija/css/buhalterija.css');
			wp_enqueue_style('my-css4', '/wp-content/plugins/freya_Buhalterija/css/bootstrap-select.min.css');
			wp_enqueue_style('my-css5', '/wp-content/plugins/freya_Buhalterija/css/font-awesome-4.7.0/css/font-awesome.min.css');
       		$this->meniu_Construct();
       		$this->top();
    	}

    	public function get_OZ_View() {
    		require ABSPATH . 'wp-content/plugins/freya_Buhalterija/view/class_Operaciju_Zurnalas_View.php';
    		new class_Operaciju_Zurnalas_View();
    	}

    	public function get_IS_View() {
    		require ABSPATH . 'wp-content/plugins/freya_Buhalterija/view/class_Imoniu_Sarasas_View.php';
    		new class_Imonu_Sarasas_View();    		
    	}

    	public function get_PJ_View() {
    		
    	}

    	public function get_BA_View() {
    		
    	}

    	public function bottom() {
    		echo '</div>';
    	}

    	private function top() {
    		echo '<div class="container-fluid">';
    	}

    	private function meniu_Construct() {
    		?>
    		<nav class="navbar navbar-expand-lg navbar-light bg-light">
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
			    	<ul class="navbar-nav mr-auto">
			     
			    	<?php
			    		$i = 0;
			    		foreach ($this->meniu_Items as $menu_Item) {
			    			$i++;
			    			?>	<li class="nav-item active">
					    			<a class="nav-link" href="?page=freya-buhalterija&psl=<?php echo $i; ?>"> <?php echo $menu_Item; ?> </a>
					    		</li>
					    	<?php
			    		}
					?>
					</ul>
				</div>
			</nav>
			<?php
    	}
    	
    }
?>