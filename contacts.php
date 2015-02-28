<?php 
/*
	Template name: Contact page
*/
?>
<?php 
	$error_name  = false;
	$error_email = false;
	$error_msg   = false;

	if(isset($_GET['contact-submit'])) {
		header("Content-type: application/json");
		$name = '';
		$email = '';
		$website = '';
		$message = '';
		$reciever_email = '';
		$return = array();

		if(trim($_GET['contact-name']) === '') {
			$error_name = true;
		} else{
			$name = trim($_GET['contact-name']);
		}

		if(trim($_GET['contact-email']) === '' || !isValidEmail($_GET['contact-email'])) {
			$error_email = true;
		} else{
			$email = trim($_GET['contact-email']);
		}

		if(trim($_GET['contact-msg']) === '') {
			$error_msg = true;
		} else{
			$message = trim($_GET['contact-msg']);
		}

		$website = stripslashes(trim($_GET['contact-website']));

		// Check if we have errors

		if(!$error_name && !$error_email && !$error_msg) {
			// Get the received email
			$reciever_email = etheme_get_option('contacts_email');

			$subject = 'You have been contacted by ' . $name;

			$body = "You have been contacted by $name. Their message is: " . PHP_EOL . PHP_EOL;
			$body .= $message . PHP_EOL . PHP_EOL;
			$body .= "You can contact $name via email at $email";
			if ($website != '') {
				$body .= " and visit their website at $website" . PHP_EOL . PHP_EOL;
			}
			$body .= PHP_EOL . PHP_EOL;

			$headers = "From $email ". PHP_EOL;
			$headers .= "Reply-To: $email". PHP_EOL;
			$headers .= "MIME-Version: 1.0". PHP_EOL;
			$headers .= "Content-type: text/plain; charset=utf-8". PHP_EOL;
			$headers .= "Content-Transfer-Encoding: quoted-printable". PHP_EOL;

			if(wp_mail($reciever_email, $subject, $body, $headers)) {
				$return['status'] = 'success';
				$return['msg'] = __('All is well, your email has been sent.', ETHEME_DOMAIN);
			} else{
				$return['status'] = 'error';
				$return['msg'] = __('Error while sending a message!', ETHEME_DOMAIN);
			}

		}else{
			// Return errors
			$return['status'] = 'error';
			$return['msg'] = __('Please, fill in the required fields!', ETHEME_DOMAIN);
		}

		echo json_encode($return);
		die();
	} 

?>

<?php
	get_header();
?>

<?php 
	$contact_page = etheme_get_option('contact_page_type');
	$googleMap = etheme_get_option('google_map_enable');
	if(isset($_GET['cont']) && $_GET['cont'] == 2) {
		$contact_page = 'custom';
	}
?>


<div class="page-heading bc-type-<?php etheme_option('breadcrumb_type'); ?>">
	<div class="container">
		<div class="row-fluid">
			<div class="span12 a-center">
				<h1 class="title"><span><?php the_title(); ?></span></h1>
				<?php etheme_breadcrumbs(); ?>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="page-content contact-page-<?php echo $contact_page; ?>">
		<?php if ($contact_page == 'default' && $googleMap): ?>
			<div id="map" class="google-map googlemap-wide">
			    <p>Enable your JavaScript!</p>
			</div>	
		<?php endif ?>
		<div class="row-fluid">
			<?php if(have_posts()): while(have_posts()) : the_post(); ?>
				<?php if($contact_page == 'default'): ?>
					<div class="span12">
						<div class="row-fluid">
							<div class="span7">
								<h3 class="contact-form-title"><?php _e('Contact Form', ETHEME_DOMAIN) ?></h3>
								<div id="contactsMsgs"></div>
								<form action="<?php the_permalink(); ?>" method="post" id="contact-form">
										
										<div class="row-fluid">
											
											<div class="span6">
												<p class="form-name">
													<label for="name"><?php _e('Name and Surname', ETHEME_DOMAIN) ?> <span class="required">*</span></label>
													<input type="text" name="contact-name" class="required-field" id="contact-name">
												</p>
											</div>
											<div class="span6">
												<p class="form-name">
													<label for="name"><?php _e('Email', ETHEME_DOMAIN) ?> <span class="required">*</span></label>
													<input type="text" name="contact-email" class="required-field" id="contact-email">
												</p>
											</div>

										</div>
										
										<p class="form-name hidden">
											<label for="name"><?php _e('Website', ETHEME_DOMAIN) ?></label>
											<input type="text" name="contact-website" id="contact-website">
										</p>
										
										<p class="form-textarea">
											<label for="contact_msg"><?php _e('Message', ETHEME_DOMAIN); ?> <span class="required">*</span></label>
											<textarea name="contact-msg" id="contact-msg" class="required-field" cols="30" rows="7"></textarea>
										</p>
										<p class="a-right" style="margin-bottom: 20px;">
											<input type="checkbox" name="newsletter" id="newsletter">
											<label for="newsletter">Yes, Subscribe Me to Newsletter!</label>
										</p>
										<p class="a-right">
											<input type="hidden" name="contact-submit" id="contact-submit" value="true" >
											<span class="spinner"><?php _e('Sending...', ETHEME_DOMAIN) ?></span>
											<button class="button" id="submit" type="submit"><?php _e('Send message', ETHEME_DOMAIN) ?></button>
										</p>
									<div class="clear"></div>
									<!-- Newsletter Signup Style -->
									<style>
										input#newsletter {
									    width: 20px;
									    margin-left: 10px;
									    background-size: 20px;
										}
										.a-right label {
											display: inline;
										}
									</style>
								</form>

								<!-- Newsletter form -->
								<div id="hidden-newsletter" style="display:none">
								  <script type="text/javascript">
									//<![CDATA[
									window.gon={};gon.payment_method_available=false;gon.plans={"free":{"price":0,"level":0,"annual":false,"permissions":{"branding_removal":false,"basic_analytics":false,"advanced_analytics":false,"premium_themes":false,"content_gate":false},"id":"free","name":"Free"},"silver":{"price":900,"level":1,"annual":false,"permissions":{"branding_removal":true,"basic_analytics":true},"id":"mailmunch-silver","name":"Silver"},"silver-annual":{"price":7560,"level":1,"annual":true,"permissions":{"branding_removal":true,"basic_analytics":true},"id":"mailmunch-silver-annual","name":"Silver (Annual)"},"gold":{"price":1900,"level":2,"annual":false,"permissions":{"branding_removal":true,"basic_analytics":true,"advanced_analytics":true,"premium_themes":true,"content_gate":true},"id":"mailmunch-gold","name":"Gold"},"gold-annual":{"price":15960,"level":2,"annual":true,"permissions":{"branding_removal":true,"basic_analytics":true,"advanced_analytics":true,"premium_themes":true,"content_gate":true},"id":"mailmunch-gold-annual","name":"Gold (Annual)"},"platinum":{"price":4900,"level":3,"annual":false,"permissions":{"branding_removal":true,"basic_analytics":true,"advanced_analytics":true,"premium_themes":true,"content_gate":true},"id":"mailmunch-platinum","name":"Platinum"},"platinum-annual":{"price":41160,"level":3,"annual":true,"permissions":{"branding_removal":true,"basic_analytics":true,"advanced_analytics":true,"premium_themes":true,"content_gate":true},"id":"mailmunch-platinum-annual","name":"Platinum (Annual)"}};gon.widget_id=42948;gon.site_id=33815;gon.content_gate=false;
									//]]>

										// Add the email to the newsletter
										jQuery('button#submit').on('click', function(){
											if(jQuery('input#newsletter').is(':checked')){
												var email = jQuery('#contact-email').val();
												jQuery('#hidden-newsletter .email-field').val(email);
												jQuery('#hidden-newsletter input[name="hidden-submit"]').click();
											}
										});
									</script>
								  <script src="//mailmunch.s3.amazonaws.com/production/assets/widget-0e9b06866f30d7845fd39db5703fed5a.js"></script>
									<div class="theme theme-simple live">
								    <div id="new_contact" class="new_contact">
											<div id="mailmunch-optin-form">
											  <div class="screen optin-form-screen" data-screen-name="Optin Form">
											    <div class="optin-form-wrapper clearfix">
											      <div id="custom_html_html" class="optin-form custom_html">
											      	<div class="custom-fields-preview-container">
											      		<div class="custom-field-preview-container required" data-custom-field-id="97158">
																  <div class="email-field-wrapper">
																    <input type="email" id="custom_field_text_field_97158" name="contact[contact_fields_attributes][97158][value]" value="" placeholder="Enter your email" style="" class="input-field email-field">
																    <input type="hidden" name="contact[contact_fields_attributes][97158][label]" value="Email">
																    <input type="hidden" name="contact[contact_fields_attributes][97158][custom_field_id]" value="97158">
																  </div>
																</div>
																<div class="custom-field-preview-container" data-custom-field-id="97159">
																  <div class="submit-wrapper">
																    <input type="submit" id="custom_field_submit_97159" name="hidden-submit" value="Subscribe" style="color: #FFFFFF; background-color: #009ba6;" class="submit">
																  </div>
																</div>
															</div>
														</div>
											    </div>
											  </div>
											</div>
								    </div>
									</div>
								</div>
							</div>
							<div class="span5">
								<?php the_content(); ?>
							</div>
						</div>
					</div>
					<?php if ($googleMap): ?>
						<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
						<script type="text/javascript">
						    
						    function etheme_google_map() {
						        var styles = {};
						        
						        var myLatlng = new google.maps.LatLng(<?php etheme_option('google_map') ?>);
						        var myOptions = {
						            zoom: 17,
						            center: myLatlng,
						            mapTypeId: google.maps.MapTypeId.ROADMAP,
						            disableDefaultUI: true,
						            mapTypeId: '8theme',
						            draggable: true,
						            zoomControl: true,
									panControl: false,
									mapTypeControl: true,
									scaleControl: true,
									streetViewControl: true,
									overviewMapControl: true,
						            scrollwheel: false,
						            disableDoubleClickZoom: false
						        }
						        var map = new google.maps.Map(document.getElementById("map"), myOptions);
						        var styledMapType = new google.maps.StyledMapType(styles['8theme'], {name: '8theme'});
						        map.mapTypes.set('8theme', styledMapType);
						        
						        var marker = new google.maps.Marker({
						            position: myLatlng, 
						            map: map,
						            title:""
						        });   
						    }
						    
						    jQuery(document).ready(function(){
							    etheme_google_map();
						    });
						    
						    jQuery(document).resize(function(){
							    etheme_google_map();
						    });
						</script>
					<?php endif ?>
				<?php else: ?>
					<div class="span8">
						<?php the_content(); ?>
					</div>
					<div class="span4">
						<h3 class="contact-form-title"><?php _e('Contact Form', ETHEME_DOMAIN) ?></h3>
						<div id="contactsMsgs"></div>
						<form action="<?php the_permalink(); ?>" method="post" id="contact-form">
							<p class="form-name">
								<label for="name"><?php _e('Name and Surname', ETHEME_DOMAIN) ?> <span class="required">*</span></label>
								<input type="text" name="contact-name" class="required-field" id="contact-name">
							</p>
							
							<p class="form-name">
								<label for="name"><?php _e('Email', ETHEME_DOMAIN) ?> <span class="required">*</span></label>
								<input type="text" name="contact-email" class="required-field" id="contact-email">
							</p>
							
							<p class="form-name">
								<label for="name"><?php _e('Website', ETHEME_DOMAIN) ?></label>
								<input type="text" name="contact-website" id="contact-website">
							</p>
							<p class="form-textarea">
								<label for="contact_msg"><?php _e('Message', ETHEME_DOMAIN); ?> <span class="required">*</span></label>
								<textarea name="contact-msg" id="contact-msg" class="required-field" cols="30" rows="7"></textarea>
							</p>
							<p class="a-right">
								<input type="hidden" name="contact-submit" id="contact-submit" value="true" >
								<span class="spinner"><?php _e('Sending...', ETHEME_DOMAIN) ?></span>
								<button class="button" id="submit" type="submit"><?php _e('Send message', ETHEME_DOMAIN) ?></button>
							</p>
							<div class="clear"></div>
						</form>
					</div>
				<?php endif; ?>

			<?php endwhile; else: ?>

				<h1><?php _e('No pages were found!', ETHEME_DOMAIN) ?></h1>

			<?php endif; ?>
		</div>

	</div>
</div>

	
<?php
	get_footer();
?>