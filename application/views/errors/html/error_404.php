<section id="content">
	<div class="container">
		<div class="col-xs-12">
			<img src="<?php echo base_url('assets/img/headers/' .rand(1,12).'.png' ) ?>" class="img-wide">
			<div class="jumbotron">
				<div class="container">
					<h1><?php echo $Content['heading'] ?></h1>
					<p><?php echo $Content['subheading'] ?></p>
					<span>Go back to <a href="<?php echo base_url() ?>">Dashoard</a></span>
				</div>
			</div>
		</div>
	</div>
</section>