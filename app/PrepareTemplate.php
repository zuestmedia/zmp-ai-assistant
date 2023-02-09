<?php

namespace ZMP\AIAssistant;

/**
 * Saved post data can be fetched via these variables:
 * 
 *  - [[title]]
 *  - [[content]]
 *  - [[excerpt]]
 *  - [[tax_XXX]] (XXX=taxonomy-slug)
 *  - [[postmeta_XXX]] (XXX=postmeta-key)
 * 
 * into those prepared Fields of AI Assistant form:
 * 
 *  - completion: prompt, suffix
 *  - edit: input, instruction
 *  - image: imageprompt
 * 
 * This class can be overwritten or extended by adding an extended class to wp uploads dir:
 * 
 * add file PrepareTemplate.php with 
 *    
 *   "namespace ZMP\AIAssistant\ExtendClass" 
 * 
 * and
 * 
 *   "class PrepareTemplate extends \ZMP\AIAssistant\PrepareTemplate" 
 * 
 * to wp-uploads directory in a new folder: 
 * 
 *   /uploads/zmp-ai-assistant
 * 
 * Example PrepareTemplate.php: 
 * 
 * <?php 
 * 
 * namespace ZMP\AIAssistant\ExtendClass;
 * 
 * class PrepareTemplate extends \ZMP\AIAssistant\PrepareTemplate {
 * 
 *   //use this function to filter terms by taxonomy...
 *   //change term -> name 
 *   public function filterTerms( $taxonomy, $terms, $single_data, $id, $template_name ){
 *
 *     return $terms;
 *
 *   }
 *
 *   //use this function to filter meta data by key...
 *   public function filterMetaData( $key, $value, $single_data, $id, $template_name ){
 *
 *     return $value;
 *
 *   }
 * 
 * }
 * 
 * ?>
 *  
 */

class PrepareTemplate {

  public function prepareTemplateData( $data, $id, $template_name ){

    if($id != 0){

      if(array_key_exists('prompt', $data)){

        $data['prompt'] = $this->getTitle($data['prompt'], $id, $template_name);
        $data['prompt'] = $this->getContent($data['prompt'], $id, $template_name);
        $data['prompt'] = $this->getExcerpt($data['prompt'], $id, $template_name);
        $data['prompt'] = $this->getTaxonomies($data['prompt'], $id, $template_name);
        $data['prompt'] = $this->getPostMeta($data['prompt'], $id, $template_name);
  
      }

      if(array_key_exists('suffix', $data)){

        $data['suffix'] = $this->getTitle($data['suffix'], $id, $template_name);
        $data['suffix'] = $this->getContent($data['suffix'], $id, $template_name);
        $data['suffix'] = $this->getExcerpt($data['suffix'], $id, $template_name);
        $data['suffix'] = $this->getTaxonomies($data['suffix'], $id, $template_name);
        $data['suffix'] = $this->getPostMeta($data['suffix'], $id, $template_name);
  
      }

      if(array_key_exists('instruction', $data)){

        $data['instruction'] = $this->getTitle($data['instruction'], $id, $template_name);
        $data['instruction'] = $this->getContent($data['instruction'], $id, $template_name);
        $data['instruction'] = $this->getExcerpt($data['instruction'], $id, $template_name);
        $data['instruction'] = $this->getTaxonomies($data['instruction'], $id, $template_name);
        $data['instruction'] = $this->getPostMeta($data['instruction'], $id, $template_name);
  
      }

      if(array_key_exists('input', $data)){

        $data['input'] = $this->getTitle($data['input'], $id, $template_name);
        $data['input'] = $this->getContent($data['input'], $id, $template_name);
        $data['input'] = $this->getExcerpt($data['input'], $id, $template_name);
        $data['input'] = $this->getTaxonomies($data['input'], $id, $template_name);
        $data['input'] = $this->getPostMeta($data['input'], $id, $template_name);
  
      }

      if(array_key_exists('imageprompt', $data)){

        $data['imageprompt'] = $this->getTitle($data['imageprompt'], $id, $template_name);
        $data['imageprompt'] = $this->getContent($data['imageprompt'], $id, $template_name);
        $data['imageprompt'] = $this->getExcerpt($data['imageprompt'], $id, $template_name);
        $data['imageprompt'] = $this->getTaxonomies($data['imageprompt'], $id, $template_name);
        $data['imageprompt'] = $this->getPostMeta($data['imageprompt'], $id, $template_name);
  
      }

    }

    return $data;

  }

  public function getTitle($single_data, $id, $template_name){

    if( strpos($single_data, "[[title]]") !== false ){

      $new_data = get_the_title( $id );
      $single_data = $this->replaceVar("[[title]]", $new_data, $single_data);

    }

    return $single_data;

  }
  
  public function getContent($single_data, $id, $template_name){

    if( strpos($single_data, "[[content]]") !== false ){

      $new_data = get_the_content( NULL, NULL, $id );
      $single_data = $this->replaceVar("[[content]]", $new_data, $single_data);

    }

    return $single_data;

  }

  public function getExcerpt($single_data, $id, $template_name){

    if( strpos($single_data, "[[excerpt]]") !== false && has_excerpt($id) ){

      $new_data = get_the_excerpt( $id );
      $single_data = $this->replaceVar("[[excerpt]]", $new_data, $single_data);

    }

    return $single_data;

  }

  public function getTaxonomies($single_data, $id, $template_name){

    if( strpos($single_data, "[[tax_") !== false ){

      $taxonomies = get_post_taxonomies($id);

      if(is_array($taxonomies) && !empty($taxonomies)){

        foreach($taxonomies as $taxonomy){

          $terms = get_the_terms( $id, $taxonomy );

          if($terms != false && !is_wp_error($terms)){

            $terms = $this->filterTerms($taxonomy, $terms, $single_data, $id, $template_name );

            $terms_string = join(', ', wp_list_pluck($terms, 'name'));

            $single_data = $this->replaceVar("[[tax_$taxonomy]]", $terms_string, $single_data);

          }

        }

      }

    }

    return $single_data;

  }

  public function getPostMeta( $single_data, $id, $template_name ){

    if( strpos($single_data, "[[postmeta_") !== false ){

      $post_meta = get_post_meta( $id );

      if($post_meta !== false){

        foreach($post_meta as $key => $meta_data){

          if(!is_array( $meta_data[0] ) && count($meta_data) == 1){

            $meta_value = $this->filterMetaData($key, $meta_data[0], $single_data, $id, $template_name );

            $single_data = $this->replaceVar("[[postmeta_$key]]", $meta_value, $single_data);

          } else {

            $single_data = $this->replaceVar("[[postmeta_$key]]", "[[error postmeta_$key is an array or has multiple values]]", $single_data);

          }

        }

      }

    }

    return $single_data;

  }

  //use this function to filter terms by taxonomy...
  //change term -> name 
  public function filterTerms( $taxonomy, $terms, $single_data, $id, $template_name ){

    return $terms;

  }

  //use this function to filter meta data by key...
  public function filterMetaData( $key, $value, $single_data, $id, $template_name ){

    return $value;

  }

  public function replaceVar($var,$var_replacement,$data){
    if($var_replacement){
      if($var_replacement == '_remove_var_'){
        $var_replacement = '';
      }
      $data = str_replace($var, $var_replacement, $data );
    }
    return $data;
  }

}
