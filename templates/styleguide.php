<?php
/**
 * Template Name: Styleguide
 */
?>
<?php get_header(); ?>

    <section class="o-wrapper o-styleguide">



      <!-- Style de caractères et paragraphes<br>
      ==================================<br> -->
      <div class="o-styleguide__section o-section o-section--bg">

        <h1 class="a-title">Titre de niveau 1</h1>

      </div>

      <div class="o-styleguide__section o-section">

        <h2 class="a-title--2">Titre de niveau 2</h2>

        <span class="a-title--2-sub">Sous-titre de niveau 2</span>

        <p class="a-paragraph has-large-font-size">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, <strong>quis nostrud exercitation ullamco laboris</strong> nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum <em>dolore eu fugiat nulla pariatur</em>. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

      </div>

      <div class="o-styleguide__section o-section o-section--bg">

        <h3 class="a-title--3">Titre de niveau 3</h3>

        <span class="a-title--3-sub">Sous-titre de niveau 3</span>

        <p class="a-paragraph">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, <strong>quis nostrud exercitation ullamco laboris</strong> nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum <em>dolore eu fugiat nulla pariatur</em>. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

      </div>

      <div class="o-styleguide__section o-section">

        <h4 class="a-title--4">Titre de niveau 4</h4>

      </div>

      <div class="o-styleguide__section o-section o-section--bg">
        <p class="a-paragraph has-big-font-size">
          Texte d'une phrase mise en exergue
        </p>
      </div>

      <div class="o-styleguide__section o-section">
        <p class="a-paragraph">
          <a href="javascript:void(0);">Lien hypertexte</a>
        </p>
      </div>


      <div class="o-styleguide__section o-section o-section--bg">
        <ul>
          <li>Liste à puce</li>
          <li>Liste à puce</li>
          <li>Liste à puce</li>
        </ul>
        <ol>
          <li>Liste numérotée</li>
          <li>Liste numérotée</li>
          <li>Liste numérotée</li>
        </ol>
      </div>



      <!-- Liens et boutons d’actions<br>
      ==========================<br> -->

      <div class="o-styleguide__section o-section">
        <!-- [BUTTONS] Bouton action principal / version Light (fond dark) -> <?php echo(htmlspecialchars("<a>, .a-button, .a-button--light")); ?><br> -->
        <a class="a-button" href="javascript:void(0);">Bouton d'action principal</a><br>
        <br>
        <br>
        <a class="a-button a-button--light" href="javascript:void(0);">
          <span class="a-button__label">Taille minimum</span>
        </a><br>
      </div>

      <div class="o-styleguide__section o-section">
        <!-- [BUTTONS] icone + Bouton action principal + flèche / version Light (fond dark)<br> -->
        <br>
        <br>
        <a class="a-button a-button--with-icon" href="javascript:void(0);">
          <span class="a-button__label">Bouton d'action principal</span>
          <span class="a-button__icon a-icon"><?php locate_template( 'img/fleche.svg', true, false ); ?></span>
        </a><br>
        <br>
        <a class="a-button a-button--light a-button--with-icon" href="javascript:void(0);">
          <span class="a-button__label">Télécharger le document</span>
          <span class="a-button__icon a-icon"><?php locate_template( 'img/download.svg', true, false ); ?></span>
        </a>
      </div>

      <div class="o-styleguide__section o-section o-section--bg">
        <!-- [BUTTONS] icone + Bouton d'action secondaire / version Light (fond dark)<br> -->
        <a class="a-button a-button--alt" href="javascript:void(0);">
          <span class="a-button__icon a-icon a-icon--circled"><?php locate_template( 'img/fleche.svg', true, false ); ?></span>
          <span class="a-button__label">Bouton d'action secondaire</span>
        </a><br>
        <br>
        <a class="a-button a-button--alt" href="javascript:void(0);">
          <span class="a-button__icon a-icon a-icon--circled"><?php locate_template( 'img/voir.svg', true, false ); ?></span>
          <span class="a-button__label">Bouton d'action secondaire</span>
        </a><br>
        <br>
      </div>
      <!-- <div class="o-styleguide__section"> -->
        <!-- [ICONS] icone alignée gauche / alignée droite -> <?php echo(htmlspecialchars(".a-icon")); ?><br> -->
        <!-- <span class="a-icon">icône exemple</span> -->
      <!-- </div> -->

<?php /*
      <div class="o-styleguide__section">


        <form novalidate>
          <div class="a-field">
            <!-- [FORMS] Liste déroulante / <?php echo(htmlspecialchars("<select>, .a-select")); ?> joli<br> -->
            <select class="a-select">
              <option>Liste déroulante</option>
              <option>Choix 1</option>
              <option>Choix 2</option>
              <option>Choix 3</option>
              <option>Choix 4</option>
            </select>
          </div>

          <div class="a-field">
            <!-- [FORMS] Label/Input formulaire / avec icône gauche, placeholder<br> -->
            <input class="a-input a-input--icon a-input--icon-search" id="input-1" type="search" placeholder="Écrivez quelque chose...">
            <label class="a-input__label" type="text" for="input-1">Votre nom</label>
          </div>

          <div class="a-field">
            <!-- [FORMS] Label/Input formulaire / sans icône gauche, placeholder<br> -->
            <input class="a-input" id="input-2" type="text" placeholder="Intitulé du formulaire">
            <label class="a-input__label" type="text" for="input-2">Intitulé du formulaire</label>
          </div>

          <div class="a-field">
            <!-- [FORMS] Label/Input formulaire erreur / sans icône gauche, placeholder<br> -->
            <input class="a-input a-input--error" id="input-3" type="text" placeholder="Intitulé du formulaire" required>
            <label class="a-input__label" type="text" for="input-3">Votre email*</label>
            <span class="a-input--error">Ce champ est obligatoire, merci de le renseigner.</span>
          </div>

          <div class="a-field">
            <!-- [FORMS] Checkbox cochée / non cochée<br> -->
            <input type="checkbox" class="a-checkbox" id="check-1" checked="checked"><label class="a-checkbox__label" for="check-1">Checkbox</label>
            <input type="checkbox" class="a-checkbox" id="check-2"><label class="a-checkbox__label" for="check-2">Checkbox non cochée</label>
          </div>


          <div class="a-field">
            <!-- [FORMS] Textarea / placeholder<br> -->
            <textarea class="a-textarea" id="textarea-1" placeholder="Votre message ici ..."></textarea>
            <label class="a-input__label" type="text" for="textarea-1">Votre message</label>
          </div>

          <div class="a-field">
            <!-- [FORMS] Bouton sumbit / placement<br> -->
            <button class="a-button" type="submit">Bouton d'action</button>
          </div>
        </form>
      </div>

*/ ?>

    </section>


<?php get_footer();
