<?php

namespace wdp;
use wdp\Classes\Router;

class WUPSettingsPage {
	public function __construct() {
		// Register the settings page.
		add_action( 'admin_menu', array( $this, 'register_settings' ) );
		// on ajoute nos URL custom
		add_action('init', [$this, 'registerCustomRewrites']);
    }

    public function registerCustomRewrites()
    {
        Router::init();
    }

	// Register settings.
	public function register_settings(){
		add_menu_page(
			'WUP-settings', // The title of your settings page.
			'WooUpdateProducts', // The name of the menu item.
			'manage_options', // The capability required for this menu to be displayed to the user.
			'WUP-settings', // The slug name to refer to this menu by (should be unique for this menu).
			array( $this, 'render_settings_page' ), // The callback function used to render the settings page.
			'dashicons-database-remove', // The icon to be used for this menu.
			59 // The position in the menu order this one should appear.
		);
	}


	// Render the settings page.
	public function render_settings_page(){
		wp_enqueue_style('bootstrap5', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
		$compteur = intval($_GET['compteur']);
		$action = $_GET['action'];
?>
		<div style="margin-top:5em;">
			<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item">
				<a id="li-add" class="nav-link active" href="#add" role="tab" data-toggle="tab">Ajouter</a>
			</li>
			<li class="nav-item">
				<a id="li-delete" class="nav-link" href="#delete" role="tab" data-toggle="tab">Supprimer</a>
			</li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="add">		
					<div class="div_conteneur_parent">
						<div class="div_conteneur_page"  >
							<div class="div_int_page">			
								<div class="div_h1" >
								<h1>Ajouter le prix des produits fournisseur via fichier CSV UTF8</h1>
								</div>
					
								<div class="div_saut_ligne">
								</div>		
								
								<div style="width:100%;height:auto;text-align:center;">
											
									<div style="width:800px;display:inline-block;" id="conteneur">
									
										<div class="centre">
											<div class="titre_centre">
												<form id="form" name="form" enctype="multipart/form-data" method="post" action="<?= home_url('ajout') ?>">
													<input type="hidden" name="action" value="add">
													<input name="fichier" type="file"  accept=".csv" id="fichier" size="200" class="liste">
													<div class="liste_div" >
													<button type="submit">Mettre à jour</button>
													</div>						
												</form>	
											</div>	
										</div>

										
									</div>
								
								</div>
					
								<div class="div_saut_ligne" style="height:50px;">
								</div>

								<?php 

								if($compteur>0 && $action==="add"){
									echo '<div id="resultadd" style="width:auto;display:block;height:auto;text-align:center;background-color:#ccccff;border:#7030a0 1px solid;padding-top:12px;box-shadow: 6px 6px 0px #aaa;color:#7030a0;">';
									
									echo "<h2>".$compteur." prix de produit ont été mis à jour</h2>"; 

									echo'</div>';
			
								}
								else if ($compteur === 1 && $action==="add")	
								{
									echo '<div id="resultadd" style="width:auto;display:block;height:auto;text-align:center;background-color:#ccccff;border:#7030a0 1px solid;padding-top:12px;box-shadow: 6px 6px 0px #aaa;color:#7030a0;">';
														
									echo "<h2>".$compteur." prix de produit a été mis à jour</h2>"; 

									echo '</div>';
								}
								else if ($compteur === 0 && $action==="add"){
									echo '<div id="resultadd" style="width:auto;display:block;height:auto;text-align:center;background-color:#ccccff;border:#7030a0 1px solid;padding-top:12px;box-shadow: 6px 6px 0px #aaa;color:#7030a0;">';
														
									echo "<h2> aucun prix de produit n'a été mis à jour</h2>"; 

									echo '</div>';
								}
								?>																	
							</div>
						</div>
					</div>	
				</div>

				<div role="tabpanel" class="tab-pane " id="delete">
					<div class="div_conteneur_parent">
						<div class="div_conteneur_page"  >
							<div class="div_int_page">			
							
								<div class="div_h1" >
								<h1>Mise en brouillon et redirection des produits fournisseur via fichier CSV UTF8</h1>
								</div>
														
					
								<div class="div_saut_ligne">
								</div>		
								
								<div style="width:100%;height:auto;text-align:center;">
											
									<div style="width:800px;display:inline-block;" id="conteneur">
									
										<div class="centre">
											<div class="titre_centre">
											<form id="form2" name="form2" enctype="multipart/form-data" method="post" action="<?= home_url('suppression') ?>">
												<input type="hidden" name="action" value="delete">
												<input name="fichier" type="file"  accept=".csv" id="fichier" size="200" class="liste">
												<div class="liste_div" >
													<button type="submit">Supprimer</button>
												</div>						
											</form>					
											</div>	
										</div>

										
									</div>
								
								</div>
					
								<div class="div_saut_ligne" style="height:50px;">
								</div>

								<?php 		

									if($compteur>0 && $action==="delete"){
										echo '<div id="resultdelete" style="width:auto;display:block;height:auto;text-align:center;background-color:#ccccff;border:#7030a0 1px solid;padding-top:12px;box-shadow: 6px 6px 0px #aaa;color:#7030a0;">';
										
										echo "<h2>".$compteur." produits ont été mis en status brouillon et redirigé dans la categorie correspondante</h2>"; 

										echo '</div>';
				
									}
									else if ($compteur === 1 && $action==="delete")	
									{
										echo '<div id="resultdelete" style="width:auto;display:block;height:auto;text-align:center;background-color:#ccccff;border:#7030a0 1px solid;padding-top:12px;box-shadow: 6px 6px 0px #aaa;color:#7030a0;">';
															
										echo "<h2>".$compteur." produit a été mis en status brouillon et redirigé dans la categorie correspondante</h2>"; 

										echo '</div>';
									}
									else if ($compteur === 0 && $action==="delete"){

										echo '<div id="resultdelete" style="width:auto;display:block;height:auto;text-align:center;background-color:#ccccff;border:#7030a0 1px solid;padding-top:12px;box-shadow: 6px 6px 0px #aaa;color:#7030a0;">';
															
										echo "<h2> aucun produit n'a été mis en status brouillon et redirigé dans la categorie correspondante</h2>"; 

										echo '</div>';
									} 		
								?>																	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div-->
	<script type="text/javascript">
	var action = "<?= $action; ?>";
</script>
<?php
	
	}
}			






