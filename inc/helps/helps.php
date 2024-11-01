<?php
if (!defined('ABSPATH')) exit;

class sstssfbHelpPage {

	function __construct() {
		// additional sub menu page
		add_action(
					'admin_menu',
					 array($this, 'sstssfb_help_page')
				   );
	}
	
	function sstssfb_help_page() { 
		add_submenu_page(
							'edit.php?post_type=sstssfb_builder',
							'Help',
							'Help',
							'manage_options',
							'sstssfb-help_page',
							 array($this, 'sstssfb_helpanddocs_page')
						);
	}
	
	function sstssfb_helpanddocs_page() {
		$videocommontitle = array(
						"Opening The Subscribe Form Creator Panel",
						"Creating Your First Subscribe Form",
						"Changing Subscribe Form's Position",
						"\"Rules\" Tab Explained",
						"Exclude Page",
						"Displaying Subscribe Form Only On The Selected Page",
						"\"Specific Locator\" Add-On For SSTSubscribeForm Builder - Demo"
					  );
		
		$videocommonurl = array(
					"https://www.youtube.com/watch?v=6WyJxYfQz3U",
					"https://www.youtube.com/watch?v=Ps9p80piUvI",
					"https://www.youtube.com/watch?v=wN5-fygLqXU",
					"https://www.youtube.com/watch?v=UE82LxwJ-H0",
					"https://www.youtube.com/watch?v=DEsyopYCL00",
					"https://www.youtube.com/watch?v=Leey5EsL470",
					"http://soursoptree.com/specific-locator-add-on-for-sst-subscribe-form-builder-plugin/"
					);
					
		$themecustomizervid = array(
								  "Opening The Theme Customizer",
								  "Changing Theme's Width",
								  "Changing Validation Tooltip's Position",
								  "Changing Background Color",
								  "Changing Text Color",
								  "Changing Text Alignment",
								  "Changing Font Family",
								  "Changing Text",
								  "\"load original\" Button Explained",
								  "\"Unselect\" And \"Reset\" Buttons Explained ",
								  "\"Load Saved\" And \"front end = Edited\" Button Explanation",
								  "Closing Theme Customizer",
								  "Closing"
							  );
	    $themecustomizervidurl = array(
								"https://www.youtube.com/watch?v=M_YmfJXSQKU",
								"https://www.youtube.com/watch?v=06VvKQoxl5Y",
								"https://www.youtube.com/watch?v=GWB3Gk3d_90",
								"https://www.youtube.com/watch?v=gy2LRxuxNDA",
								"https://www.youtube.com/watch?v=ZVcssTLCyWw",
								"https://www.youtube.com/watch?v=1PAWqC3hhL8",
								"https://www.youtube.com/watch?v=n-geIyL3L-o",
								"https://www.youtube.com/watch?v=jkBPfJJmBaU",
								"https://www.youtube.com/watch?v=658fnC6lwpc",
								"https://www.youtube.com/watch?v=Ms_b1V7Vurk",
								"https://www.youtube.com/watch?v=Z3pF7-poo6s",
								"https://www.youtube.com/watch?v=254ePNvbnXc",
								"https://www.youtube.com/watch?v=xW42weKLv5g"
							  );
		?>
			<h1>Help and Documentation</h1>
			<div id="documentationandhelps">
			<span>Here you can find the list of documentation videos to get started using SST Subscribe Form Builder WP Plugin</span>
			<h3>Subscribe to SoursopTree:</h3>
			<div class="g-ytsubscribe" data-channelid="UCHhzCyjloprGMq6Rfo94lNQ" data-layout="full" data-count="default"></div>
			<h2>Basic usage:</h2>
				<ul class="video_links">
					<?php
					foreach($videocommontitle as $key => $value) {
						?>
							<li><a href="<?php echo $videocommonurl[$key]; ?>" target="_blank"><?php echo $value; ?></a></li>
						<?php
					}
					?>
				</ul>
			<h2>Using theme customizer:</h2>
				<ul class="video_links">
					<?php
					foreach($themecustomizervid as $key => $value) {
						?>
							<li><a href="<?php echo $themecustomizervidurl[$key]; ?>" target="_blank"><?php echo $value; ?></a></li>
						<?php
					}
					?>
				</ul>
				<?php
				if(file_exists(SSTSSFB_ASSET_DIR . 'GeoIP.dat')) {
				?>
					This product includes GeoLite2 data created by MaxMind, available from
					<a href="http://www.maxmind.com">http://www.maxmind.com</a>.
				<?php
				}
				?>
			</div>
		<?php
	}
	
}
	new sstssfbHelpPage();
?>