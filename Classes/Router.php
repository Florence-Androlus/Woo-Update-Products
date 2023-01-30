<?php

namespace wdp\Classes;
use wdp\Classes\Readfile;

class Router {

    static public function init()
    {
        // objectif :

        // @TODO: déplacer la déclaration de la rewrite rule dans l'activation du plugin
        // si l'URL courante est ajout, afficher le template readfile.php du thème
        // 2e argument : URL réelle correspondant à la "fausse URL" de l'argument 1 
        // 1. ajout de la réécriture = on permet à WP de reconnaître notre URL custom :  
        add_rewrite_rule('ajout', 'index.php?wup-page=ajout', 'top');      
        add_rewrite_rule('suppression', 'index.php?wup-page=suppression', 'top');

        // 2. on rafraîchit les réécritures au sein de WP
        flush_rewrite_rules();

        // 3. Autoriser notre query var (paramètre d'URL) custom dans WP
        add_filter('query_vars', function($query_vars) {
            $query_vars[] = 'wup-page'; // on rajoute notre propre query var en tant que query var autorisée

            // on return le tableau $query_vars
            return $query_vars;
        });

        // 4. Surcharger (ou pas !) le choix de template fait par WP
        // $template contient le chemin vers le fichier de template que WP comptait charger si on ne l'avait pas interrompu
        add_action( 'template_include', function( $template ) {
            // on vérifie si notre query var custom est présente et a une valeur qu'on connaît
            // pour lire une query var, on utilise get_query_var()
            if (get_query_var('wup-page') == 'ajout') {

                // si la méthode HTTP (= le verbe de la requête) est POST => on vient de soumettre le formulaire, on doit traiter les données transmises
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // enregistrer les données de l'utilisateur fournies par $_POST

                    if(isset($_FILES["fichier"]["tmp_name"]) && $_POST['action']==="add")
                    {
                        
                        $fileName = $_FILES["fichier"]["tmp_name"];

                        if ($_FILES["fichier"]["size"] > 0) {
                            
                            $compteur=Readfile::update($fileName);
	
                        }
                    }
                    // on redirige vers la page wp-admin/admin.php?page=WUP-settings (mais en GET) => si l'utilisateur rafraîchit, on ne resoumettra pas le formulaire (on rafraîchira la requête GET et non POST)
                    $url=home_url( 'wp-admin/admin.php?page=WUP-settings');
                    wp_redirect(add_query_arg(['compteur'=> $compteur,'action'=> $_POST['action']], $url));
                    exit(); // on empêche le reste du code de s'exécuter, on laisse la redirection se faire tout de suite.
                }


                // si c'est le cas, on réagit en conséquence
                wp_redirect(home_url('wp-admin/admin.php?page=WUPP-settings'));
            } 
            else if (get_query_var('wup-page') == 'suppression') {

                // si la méthode HTTP (= le verbe de la requête) est POST => on vient de soumettre le formulaire, on doit traiter les données transmises
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // enregistrer les données de l'utilisateur fournies par $_POST

                    if(isset($_FILES["fichier"]["tmp_name"]) && $_POST['action']==="delete")
                    {
                        
                        $fileName = $_FILES["fichier"]["tmp_name"];

                        if ($_FILES["fichier"]["size"] > 0) {
                            
                            $compteur=Readfile::delete($fileName);

                        }
                    }
                    // on redirige vers la page wp-admin/admin.php?page=WADP-settings (mais en GET) => si l'utilisateur rafraîchit, on ne resoumettra pas le formulaire (on rafraîchira la requête GET et non POST)
                    $url=home_url( 'wp-admin/admin.php?page=WUP-settings');
                    wp_redirect(add_query_arg(['compteur'=> $compteur,'action'=> $_POST['action']], $url));
                    exit(); // on empêche le reste du code de s'exécuter, on laisse la redirection se faire tout de suite.
                }


                // si c'est le cas, on réagit en conséquence
                wp_redirect(home_url('wp-admin/admin.php?page=WUP-settings'));
            } 
            else {
                // sinon, on laisse WP faire
                return $template;
            }
        } );
    }
}
