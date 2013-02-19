	<footer id="main-footer" class="span-24">
		<p class="textright"><a href="http://www.cultura.gov.br" title="Ministério da Cultura - Governo Federal"><?php html::image('minc-gov.png', 'alguma coisa'); ?></a></p>
		<!-- <p class="creditos textright textright">desenvolvido por <a href="http://hacklab.com.br" title="Hacklab"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/img/hacklab.png" alt="" /></a> com <a href="http://wordpress.org" title="WordPress"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/img/wp.png" alt="" /></a></p>
        -->
	</footer> 
	<!-- #main-footer -->
    
</div>

<?php wp_nav_menu( array( 'theme_location' => 'rodape', 'container' => '<ul>', 'menu_class' => '', 'fallback_cb' => '', 'depth' => '1') ); ?>

<!-- .container -->
<?php wp_footer(); ?>
</body>
</html>
