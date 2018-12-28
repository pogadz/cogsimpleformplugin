<?php

/*
Plugin Name: Cog Form
Plugin URI: https://github.com/pogadz/cogsimpleformplugin
description: Cog Simple Contact Form WP plugin
Version: 0.1
Author: Pogadz
Author URI: http://www.hyperboink.com/
*/


class CogForm{

	private $errors = array();

	function __construct(){

		add_shortcode('cog_form', array($this, 'shortcode'));

	}

	static public function form(){ ?>

		<form action="<?=esc_url($_SERVER['REQUEST_URI'])?>" method="POST">

			<div class="row">

				<div class="form-group col-md-6">
					<label for="cog-name">Your Name</label>
					<input type="text" name="cog_name" pattern="[a-zA-Z0-9 ]+" class="form-control" id="cog-name" value="<?=isset($_POST["cog_name"]) ? esc_attr($_POST["cog_name"]) : ''?>">
				</div>

				<div class="form-group col-md-6">
					<label for="phone-number">Your Number</label>
					<input type="text" name="cog_phone" class="form-control" id="phone-number"value="<?=isset($_POST["cog_phone"]) ? esc_attr($_POST["cog_phone"]) : ''?>">
				</div>

			</div>

			<div class="form-group">
				<label for="message">Message</label>
				<textarea class="form-control" name="cog_message" id="message" rows="3"><?=isset($_POST["cog_message"]) ? esc_attr($_POST["cog_message"]) : ''?></textarea>
			</div>

			<button type="submit" name="cog_submit" class="btn font-weight-bold cog-cta cog-cta-wide cta-red my-3">Submit</button>

		</form>

	<?php }

	public function validate($name, $phone, $message){

		if(empty($name) || empty($phone) || empty($message)){

			array_push($this->errors, 'Please fill up the required fields');

		}

	}

	public function process(){

		$name = $_POST['cog_name'] ?? '';
		$phone = $_POST['cog_phone'] ?? '';
		$message = $_POST['cog_message'] ?? '';

		if(isset($_POST['cog_submit'])){

			$this->validate($name, $phone, $message);

			if(is_array($this->errors)){

				foreach($this->errors as $error){

					echo '<div class="error">'.$error.'</div>';

				}
			}

		}

		$this->send($name, $phone, $message);

		self::form();

	}

	public function send($name, $phone, $message){

		if(count($this->errors)){

			$name = sanitize_text_field($name);
            $phone = sanitize_text_field($phone);
            $message = esc_textarea($message);

            $to = get_option('admin_email');

            $headers = "From: $name";

            if(wp_mail($to, $name, $message, $headers)){

            	echo 'Success';

            }

		}
	}

	public function shortcode(){

		ob_start();

		$this->process();

		return ob_get_clean();
		
	}

}

new CogForm;




