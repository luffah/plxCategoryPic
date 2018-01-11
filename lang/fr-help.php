<h2>Aide</h2>
<p>
  
  Pour ajouter des images pour illustrer des catégories.
  
</p>
<h3 style="font-size:1.3em;font-weight:bold;padding:10px 0 10px 0">Dans le template du thème</h3>
<p>
Dans le fichier de template contenant l'appel à la vue des catégories, vous pouvez remplacer <pre style="display:inline">$plxShow->catList(...)</pre>
par <pre style="display:inline">cateporyPic::catList(...)</pre> comme suit :
</p>
<pre style="font-size:12px; padding-left:40px">
&lt;?php 
plxCategoryPic::catList(
  // l'instance de plxShow 
  $plxShow,
  // le format de la vue ( type String)
  '&lt;li id="#cat_id" class="#cat_status"&gt;
  &lt;a href="#cat_url" title="#cat_name"&gt; #cat_thumbnail #cat_name &lt;/a&gt;
  &lt;/li&gt;'
  );
?&gt;
</pre>
<p>
La fonction <pre style="display:inline">cateporyPic::catList(...)</pre> permet d'utiliser :
<ul>
<li><em>#cat_tumbnail</em> qui retourne l'image complète</li>
<li><em>#cat_img_src</em> qui retourne le nom du ficher </li>
<li><em>#cat_img_alt</em> qui retourne l'audio-description</li>
<li><em>#cat_img_title</em> qui retourne le titre (qui s'affiche au passage de la souris)</li>
</ul>
</p>

<h3 style="font-size:1.3em;font-weight:bold;padding:10px 0 10px 0">Dans la pages d'administration des catégorie</h3>
<p>
Dans la page réservée à l'édition des paramètres de la catégorie, vous trouverez les champs pour ajouter l'image d'accroche.
Vous pourrez sélectionner l'image de votre choix en appuyant sur le bouton (lien) <a href="#!">+</a>.
Veillez à remplir les champs 'alt' et 'title', c'est la seul chose que pourront voir les personnes malvoyantes ou aveugle.
C'est aussi ce qu'utiliseront les moteurs de recherches pour décrire votre image.
</p>



