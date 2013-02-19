<?php
/*
Template Name: Home
*/
?>
<?php get_header(); ?>
	<section id="main-section" class="span-15 prepend-1 append-1">
		<article class="destaque-principal post">
			<h3 class="subtitulo"><?php _oi('Destaque', 'Home: Chapéu do destaque maior'); ?></h3>
            <?php $destaques = get_option('destaques'); ?>
			<header>
				<p class="bottom">				
					<?php echo $destaques['1_data']; ?>
				</p>
				<h1><a href="<?php echo $destaques['1_link']; ?>" title="<?php echo esc_attr($destaques['1_titulo']); ?>"><?php echo $destaques['1_titulo']; ?></a></h1>					
			</header>
			<div class="post-content clearfix">
				<!-- se tiver imagem, colocar aqui com a classe ".destaque-principal-thumbnail" de 230x176 -->
                <?php if ($destaques['1_imagem']): ?>
                    <img src="<?php echo $destaques['1_imagem']; ?>" class="destaque-principal-thumbnail" />
                <?php endif; ?>
				<div class="post-entry">
					<p><?php echo $destaques['1_txt']; ?><br />
					<a href="<?php echo $destaques['1_link']; ?>">Leia mais</a>
					</p>
				</div>	
			</div>
			<!-- .post-content -->
		</article>
		<!-- .destaque-principal -->
		<h3 class="subtitulo"><?php _oi('Veja também', 'Home: Chapéu dos destaques menores'); ?></h3>
		<article class="destaque post span-7 append-1"><!-- ATENÇÃO! O outro destaque não tem a classe "append-1", mas tem a classe "last"!!!! -->
			
            <!-- se tiver imagem, colocar aqui com a classe ".destaque-thumbnail" de 270x132 -->
            <?php if ($destaques['2_imagem']): ?>
                <img src="<?php echo $destaques['2_imagem']; ?>" class="destaque-thumbnail" />
            <?php endif; ?>
			<header>
				<p class="bottom">				
					<?php echo $destaques['2_data']; ?>
				</p>
				<h1><a href="<?php echo $destaques['2_link']; ?>" title="<?php echo esc_attr($destaques['2_titulo']); ?>"><?php echo $destaques['2_titulo']; ?></a></h1>
			</header>
			<div class="post-content clearfix">
				<div class="post-entry">
					<p><?php echo $destaques['2_txt']; ?><br />
					<a href="<?php echo $destaques['2_link']; ?>">Leia mais</a>
					</p>
				</div>
			</div>
			<!-- .post-content -->
            
		</article>
		<article class="destaque post span-7 last">
			
            <!-- se tiver imagem, colocar aqui com a classe ".destaque-thumbnail" de 270x132 -->
            <?php if ($destaques['3_imagem']): ?>
                <img src="<?php echo $destaques['3_imagem']; ?>" class="destaque-thumbnail" />
            <?php endif; ?>
			<header>
				<p class="bottom">				
					<?php echo $destaques['3_data']; ?>
				</p>
				<h1><a href="<?php echo $destaques['3_link']; ?>" title="<?php echo esc_attr($destaques['3_titulo']); ?>"><?php echo $destaques['3_titulo']; ?></a></h1>
			</header>
			<div class="post-content clearfix">
				<div class="post-entry">
					<p><?php echo $destaques['3_txt']; ?><br />
					<a href="<?php echo $destaques['3_link']; ?>">Leia mais</a>
					</p>
				</div>
			</div>
			<!-- .post-content -->
            
            
		</article>
		
	</section>
	<!-- #main-section -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
