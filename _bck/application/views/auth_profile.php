<div id="container">
	<h1>Welcome to this little Azure B2C Demo!</h1>

	<div id="body">
		
        <?php 
        
        $CI =& get_instance();
        $CI->load->library('table');
    
        $template = array(
                'table_open' => '<table class="table table-striped table-hover">'
        );
        $CI->table->set_template($template);
        $CI->table->set_heading('Attribute', 'Value');
    
        
        foreach($profile as $key => $value) {
            $CI->table->add_row($key, $value);
        }
        echo $CI->table->generate();
        
        ?>

	</div>

</div>