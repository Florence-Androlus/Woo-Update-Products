<?php 

 namespace wdp\Classes;
 use WP_Query;

 class Readfile 
 {

    static public function update($fileName)
    {
        global $wpdb;
        $file = fopen($fileName, "r");
        $compteur=0;
        while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {

            $UGS=$column[1];
            $UGS = str_replace('-', '', $UGS);
            $UGS=substr_replace($UGS,"20",2,0);
            $UGS = substr_replace($UGS,"05",6,0);

            $Prix_unit_HT=$column[5];
            $Poids=$column[3];
            
            $sql = 'SELECT product_id FROM `'.$wpdb->prefix.'wc_product_meta_lookup` WHERE sku LIKE "%'.$UGS.'"';

            $result =$wpdb->get_results($sql);

            if( !empty($result) ){

                foreach( $result as $enreg ) {
                    $product_id=$enreg->product_id ;
                }
                $product_id=intval($product_id);

                Readfile::update_purchase_price($product_id,$Prix_unit_HT);
                Readfile::update_poids($product_id,$Poids);

                if ( $wpdb->last_error ) {
                    echo 'wpdb error: ' . $wpdb->last_error;
                }

                $compteur++;
            }
            
        }
      //  die;
        fclose($file);
        return $compteur;

    }

    static public function delete($fileName)
    {
        global $wpdb;
        $file = fopen($fileName, "r");
        $compteur=0;
        while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {

            $UGS=$column[0];
            $UGS=substr_replace($UGS,"20",2,0);
            $UGS = substr_replace($UGS,"05",6,0);

            $sql = 'SELECT product_id FROM `'.$wpdb->prefix.'wc_product_meta_lookup` WHERE sku LIKE "%'.$UGS.'"';

            $result =$wpdb->get_results($sql);
           // var_dump($result );
            if( !empty($result) ){

                foreach( $result as $enreg ) {
                    $product_id=$enreg->product_id ;
                }
                $product_id=intval($product_id);
                
                $sql_product_id = 'SELECT post_name,post_status FROM `'.$wpdb->prefix.'posts` where ID="'.$product_id.'"';

                $product =$wpdb->get_results($sql_product_id);

                foreach( $product as $enreg ) {
                //teste en local    $product_slug="ecommerce/produit/".$enreg->post_name ;
                    $product_slug="produit/".$enreg->post_name ;
                    $product_status=$enreg->post_status ;
                }

                if($product_status!="draft"){
                    $sql_product_category_id = 'SELECT term_taxonomy_id FROM `'.$wpdb->prefix.'term_relationships`where object_id="'.$product_id.'"';
                    $product_category_id =$wpdb->get_results($sql_product_category_id);
    
                    foreach( $product_category_id as $enreg ) {
                        $category_id=$enreg->term_taxonomy_id ;
                        $sql_taxonomy = 'SELECT taxonomy,parent FROM `'.$wpdb->prefix.'term_taxonomy`where term_id="'.$category_id.'"';
                        $taxonomy =$wpdb->get_results($sql_taxonomy);
                        foreach( $taxonomy as $enreg ) {
    
                            if( $enreg->taxonomy === "product_cat" and $enreg->parent==="0")
                            {
                                $sql_category =' SELECT slug FROM `'.$wpdb->prefix.'terms`where term_id="'.$category_id.'"';
                                $category =$wpdb->get_results($sql_category);
    
                                foreach( $category as $enreg ) {
                                    $category=$enreg->slug ;
                                }
                                
    
                                $url = site_url()."/categorie-produit/".$category."/";
    
                                $table =$wpdb->prefix."redirection_items";
    
                                $sql_redirection ="INSERT INTO `$table` (`url`, `match_url`, `match_data`, `regex`, `position`, `last_count`, `last_access`, `group_id`, `status`, `action_type`, `action_code`, `action_data`, `match_type`, `title`) VALUES ('$product_slug', '$product_slug', NULL, 0, 0, 1, now(), 1, 'enabled', 'url', 301, '$url', 'url', NULL)";
                                $result =$wpdb->get_results($sql_redirection);
    
                                wp_update_post(array(
                                    'ID'            =>  $product_id,
                                    'post_status'   =>  "draft"
                                ));
                                
                                $compteur++;
                                if ( $wpdb->last_error ) {
                                    echo 'wpdb error: ' . $wpdb->last_error;
                                }
                            }
                        }
    
                    }
                }

            }

        }
        fclose($file);
        return $compteur;

    }

    static public function update_purchase_price($product_id,$Prix_unit_HT){
        global $wpdb;
		$meta_key="_purchase_price";
        $sql = 'SELECT * FROM `'.$wpdb->prefix.'wc_product_attributes_lookup` WHERE product_or_parent_id = '.$product_id;
        $result =$wpdb->get_results($sql);
        if( !empty($result)){

            foreach( $result as $enreg ) {
                $product_id=intval($enreg->product_id) ;


                update_post_meta($product_id, $meta_key, $Prix_unit_HT);
            }

        }
        else{    
            update_post_meta($product_id, $meta_key, $Prix_unit_HT);
        }
    }
    static public function update_poids($product_id,$Poids){
        global $wpdb;
        $taxonomy="pa_poids";
        if ($Poids!="-"){
            // on cherche si le Poids existe dans wp_terms
            $Poids=$Poids." gr";
        //    var_dump($Poids);

            $sql = 'SELECT * FROM `'.$wpdb->prefix.'terms` WHERE name LIKE "'.$Poids.'"';
            $result =$wpdb->get_results($sql);
            $term_taxonomy_id = $result[0]->term_id; 
        //    var_dump($result);

            if( empty($result)){
                /* si non 
                * {
                *  on le crée 
                *  SELECT * FROM `wp_terms`where term_id=392
                *  SELECT * FROM `wp_term_taxonomy` WHERE taxonomy="pa_poids"
                *  SELECT * FROM `wp_termmeta` where term_id=392???
                * }
                */
             
        //        var_dump("si Poids n'existe pas");
                $result = wp_insert_term(
                    $Poids, // the term name
                    $taxonomy, // the taxonomy
                    array(
                        'slug' => $Poids
                    )
                );
                $term_taxonomy_id = $result["term_id"]; 
        //        var_dump("Poids enregistré");
            }
            
            /* si oui
            * {
            *  on fait la relation entre le produit et le poid
            *  SELECT * FROM `wp_term_relationships` where term_taxonomy_id=392
            * }
            */
        //    var_dump("si Poids existe deja");
        //    var_dump($product_id);
        //    var_dump($term_taxonomy_id);
        //    var_dump($taxonomy);
            /**
             * on cherche si la relation existe deja
             */ 
            $sql='SELECT * FROM '.$wpdb->prefix.'term_relationships WHERE object_id = '.$product_id.' AND term_taxonomy_id = '.$term_taxonomy_id;
            $result =$wpdb->get_results($sql);
            var_dump($result);
            if( empty($result)){  /*si relation entre objet et poids n'exsite pas on ajoute la relation */
        //        var_dump("si poids de la relation different on ajoute la relation");

                $wp_term_relationships =wp_add_object_terms( $product_id,$Poids, $taxonomy,true);
                var_dump($wp_term_relationships);
               
                $attributes[$taxonomy] = array(
                    'name'         => $taxonomy,
                    'value'        => $Poids,
                    'position'     => 0,
                    'is_visible'   => 1,
                    'is_variation' => 0,
                    'is_taxonomy'  => 1,
                );
                var_dump($attributes);
                $update_post_meta=update_post_meta( $product_id, '_product_attributes', $attributes );
                
                var_dump($update_post_meta);   
            } 
 

        }
    }
}