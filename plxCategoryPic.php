<?php
class plxCategoryPic extends plxPlugin {
  public function __construct($default_lang) {
    # appel du constructeur de la classe plxPlugin (obligatoire)
    parent::__construct($default_lang);
    # Ajoute des hooks
    $this->addHook('plxAdminEditCategoriesNew', 'plxAdminEditCategoriesNew');
    $this->addHook('plxAdminEditCategoriesUpdate', 'plxAdminEditCategoriesUpdate');
    $this->addHook('plxAdminEditCategoriesXml', 'plxAdminEditCategoriesXml');
    $this->addHook('plxMotorGetCategories', 'plxMotorGetCategories');
    $this->addHook('plxAdminEditCategorie', 'plxAdminEditCategorie');
    $this->addHook('AdminCategory', 'AdminCategory');
  }
  /**
   * Méthode qui affiche la liste des catégories actives.
   * position.
   *
   * Ajout : support de l'image d'accroche
   *
   * @param	extra	nom du lien vers la page d'accueil
   * @param	format	format du texte pour chaque catégorie (variable : #cat_id, #cat_status, #cat_url, #cat_name, #cat_description, #art_nb)
   * @param	include	liste des catégories à afficher séparées par le caractère | (exemple: 001|003)
   * @param	exclude	liste des catégories à ne pas afficher séparées par le caractère | (exemple: 002|003)
   * @return	stdout
   * @scope	global
   * @author	(basé sur le code de) Anthony GUÉRIN, Florent MONTHEL, Stephane F
   * @author	luffah
   **/

  public static function catList($plxShow,
    $format='<li id="#cat_id" class="#cat_status"><a href="#cat_url" title="#cat_name">#cat_name</a></li>',
    $include='', $exclude='') {
/*    # Si on a la variable extra, on affiche un lien vers la page d'accueil (avec $extra comme nom)*/
/*    if($extra != '') {*/
/*      $name = str_replace('#cat_id','cat-home',$format);*/
/*      $name = str_replace('#cat_url',$this->plxMotor->urlRewrite(),$name);*/
/*      $name = str_replace('#cat_name',plxUtils::strCheck($extra),$name);*/
/*      $name = str_replace('#cat_status',($this->catId()=='home'?'active':'noactive'), $name);*/
/*      $name = str_replace('#art_nb','',$name);*/
/*      echo $name;*/
/*    }*/
    # On verifie qu'il y a des categories
    if($plxShow->plxMotor->aCats) {
      foreach($plxShow->plxMotor->aCats as $k=>$v) {
        $in = (empty($include) OR preg_match('/('.$include.')/', $k));
        $ex = (!empty($exclude) AND preg_match('/('.$exclude.')/', $k));
        if($in AND !$ex) {
          if(($v['articles']>0 OR $plxShow->plxMotor->aConf['display_empty_cat']) AND ($v['menu']=='oui') AND $v['active']) { # On a des articles
            # On modifie nos motifs
            $name = str_replace('#cat_id','cat-'.intval($k),$format);
            $name = str_replace('#cat_url',$plxShow->plxMotor->urlRewrite('?categorie'.intval($k).'/'.$v['url']),$name);
            $name = str_replace('#cat_name',plxUtils::strCheck($v['name']),$name);
            $name = str_replace('#cat_status',($plxShow->catId()==intval($k)?'active':'noactive'), $name);
            $name = str_replace('#cat_description',plxUtils::strCheck($v['description']),$name);
            $name = str_replace('#cat_thumbnail',
              '<img src="'.plxUtils::strCheck($v['thumbnail']).'" title="'.plxUtils::strCheck($v['thumbnail_title']).'" alt="'.plxUtils::strCheck($v['thumbnail_alt']).'"/>'
            ,$name);
            $name = str_replace('#cat_img_src',plxUtils::strCheck($v['thumbnail']),$name);
            $name = str_replace('#cat_img_alt',plxUtils::strCheck($v['thumbnail_alt']),$name);
            $name = str_replace('#cat_img_title',plxUtils::strCheck($v['thumbnail_title']),$name);
            $name = str_replace('#art_nb',$v['articles'],$name);
            echo $name;
          }
        }
      } # Fin du while
    }
  }
  /*
   *  Nouvelle catégorie
   **/
  public function plxAdminEditCategoriesNew() {
    $cat="\$this->aCats[\$content['new_catid']]";
    echo "<?php 
${cat}['thumbnail']='img';
${cat}['thumbnail_title']='';
${cat}['thumbnail_alt']='';
?>";
  }

  /*
   * Mise à jour de la liste de catégories (par exemple au renommage d'une catégorie)
   **/
  public function plxAdminEditCategoriesUpdate() {
    $cat="\$this->aCats[\$cat_id]";
    echo "<?php 
${cat}['thumbnail'] = (isset(${cat}['thumbnail'])?${cat}['thumbnail']:'');
${cat}['thumbnail_alt'] = (isset(${cat}['thumbnail_alt'])?${cat}['thumbnail_alt']:'');
${cat}['thumbnail_title'] = (isset(${cat}['thumbnail_title'])?${cat}['thumbnail_title']:'');
?>";
  }

  /*
   * Lecture des données dans le fichier XML
   **/
  public function plxMotorGetCategories() {
    $cat="\$this->aCats[\$number]";
    echo "<?php 
${cat}['thumbnail'] = isset(\$iTags['thumbnail'][\$i])?\$values[\$iTags['thumbnail'][\$i]]['value']:''; 
${cat}['thumbnail_alt'] = isset(\$iTags['thumbnail_alt'][\$i])?\$values[\$iTags['thumbnail_alt'][\$i]]['value']:''; 
${cat}['thumbnail_title'] = isset(\$iTags['thumbnail_title'][\$i])?\$values[\$iTags['thumbnail_title'][\$i]]['value']:''; 
?>";
  }
  /*
   * Écriture des données dans le fichier XML
   **/
  public function plxAdminEditCategoriesXml() {
    echo "<?php 
\$xml .= '<thumbnail><![CDATA['.plxUtils::cdataCheck(\$cat['thumbnail']).']]></thumbnail>';
\$xml .= '<thumbnail_alt><![CDATA['.plxUtils::cdataCheck(\$cat['thumbnail_alt']).']]></thumbnail_alt>';
\$xml .= '<thumbnail_title><![CDATA['.plxUtils::cdataCheck(\$cat['thumbnail_title']).']]></thumbnail_title>';
?>";
  }

  /*
   * Lecture des données dans POST ($content['id']) 
   **/
  public function plxAdminEditCategorie() {
    $cat="\$this->aCats[\$content['id']]";
    echo "<?php 
${cat}['thumbnail'] = trim(\$content['thumbnail']);
${cat}['thumbnail_alt'] = trim(\$content['thumbnail_alt']);
${cat}['thumbnail_title'] = trim(\$content['thumbnail_title']);
?>";
  }
  /*
   * Écran d'option
   **/
  public function AdminCategory() {
    $string = <<<END
<script>
function refreshImg(dta) {
  if(dta.trim()==='') {
    document.getElementById('id_thumbnail_img').innerHTML = '';
  } else {
    var link = dta.match(/^(https?:\/\/[^\s]+)/gi) ? dta : '<?php echo \$plxAdmin->racine ?>'+dta;
    document.getElementById('id_thumbnail_img').innerHTML = '<img src="'+link+'" alt="" />';
  }
}
</script>
<?php 
  \$thumbnail_title =\$plxAdmin->aCats[\$id]['thumbnail_title'] ;
  \$thumbnail_alt =\$plxAdmin->aCats[\$id]['thumbnail_alt'] ;
  \$thumbnail =\$plxAdmin->aCats[\$id]['thumbnail'] ;
?>
      <div class="grid gridthumb">
        <div class="col sml-12">
          <label for="id_thumbnail">
            <?php echo L_THUMBNAIL ?>&nbsp;:&nbsp;
            <a title="<?php echo L_THUMBNAIL_SELECTION ?>" id="toggler_thumbnail" href="javascript:void(0)" onclick="mediasManager.openPopup('id_thumbnail', true)" style="outline:none; text-decoration: none">+</a>
          </label>
          <?php plxUtils::printInput('thumbnail',plxUtils::strCheck(\$thumbnail),'text','255-255',false,'full-width','','onkeyup="refreshImg(this.value)"'); ?>
          <div class="grid" style="padding-top:10px">
            <div class="col sml-12 lrg-6">
              <label for="id_thumbnail_alt"><?php echo L_THUMBNAIL_TITLE ?>&nbsp;:</label>
              <?php plxUtils::printInput('thumbnail_title',plxUtils::strCheck(\$thumbnail_title),'text','255-255',false,'full-width'); ?>
            </div>
            <div class="col sml-12 lrg-6">
              <label for="id_thumbnail_alt"><?php echo L_THUMBNAIL_ALT ?>&nbsp;:</label>
              <?php plxUtils::printInput('thumbnail_alt',plxUtils::strCheck(\$thumbnail_alt),'text','255-255',false,'full-width'); ?>
            </div>
          </div>
          <?php
          \$imgUrl = PLX_ROOT.\$thumbnail;
          if(is_file(\$imgUrl)) {
            echo '<div id="id_thumbnail_img"><img src="'.\$imgUrl.'" alt="" /></div>';
          } else {
            echo '<div id="id_thumbnail_img"></div>';
          }
          ?>
        </div>
      </div>
END;
    echo $string;
  }
  }
?>
