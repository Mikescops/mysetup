<?php
/**
  * @var \App\View\AppView $this
  */

	$this->layout = 'admin';
	$this->assign('title', __('Dashboard | myAdmin'));
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>

<div class="col-12 col-md-9 col-xl-10">

	<div class="row mb-3">
		<div class="col-xl-3 col-sm-6">
			<div class="card text-white bg-danger h-100">
				<div class="card-body bg-danger">
					<div class="rotate">
						<h1 class="display-4"><?= $stats['count']['setups'] ?> <i class="fa fa-list"></i></h1> SETUPS
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-sm-6">
			<div class="card bg-success text-white h-100">
				<div class="card-body bg-success">
					<div class="rotate">
						<h1 class="display-4"><?= $stats['count']['users'] ?> <i class="fa fa-users"></i></h1> USERS
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-sm-6">
			<div class="card text-white bg-info h-100">
				<div class="card-body bg-info">
					<div class="rotate">
						<h1 class="display-4"><?= $stats['count']['comments'] ?> <i class="fa fa-comments"></i></h1> COMMENTS
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-sm-6">
			<div class="card text-white bg-warning h-100">
				<div class="card-body bg-warning">
					<div class="rotate">
						<h1 class="display-4"><?= $stats['count']['resources'] ?> <i class="fa fa-database"></i></h1> RESOURCES
					</div>
					
				</div>
			</div>
		</div>
	</div>

	<hr>

	<div class="row mb-3">
		<div class="col-xl-3 col-sm-6">
			<br>
			<h3>Certified users</h3>
			<canvas id="certified-user" width="400" height="400"></canvas>
		</div>
		<div class="col-xl-3 col-sm-6">
			<br>
			<h3>Twitch users</h3>
			<canvas id="twitch-user" width="400" height="400"></canvas>
		</div>
		<div class="col-xl-6 col-sm-6">
			<br>
			<h3>Analytics</h3>

			<div id="embed-api-auth-container"></div>
			<div id="chart-container"></div>
			<div id="view-selector-container"></div>

			<script>
			(function(w,d,s,g,js,fs){
			  g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
			  js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
			  js.src='https://apis.google.com/js/platform.js';
			  fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
			}(window,document,'script'));
			</script>

			<script>
				gapi.analytics.ready(function() {

				/**
				 * Authorize the user immediately if the user has already granted access.
				 * If no access has been created, render an authorize button inside the
				 * element with the ID "embed-api-auth-container".
				 */
				gapi.analytics.auth.authorize({
				  container: 'embed-api-auth-container',
				  clientid: '778528865548-90d6afa4q9skpr4kqa8rbanrbhar05i6.apps.googleusercontent.com'
				});

				/**
				 * Create a new ViewSelector instance to be rendered inside of an
				 * element with the id "view-selector-container".
				 */
				// var viewSelector = new gapi.analytics.ViewSelector({
				//   container: 'view-selector-container'
				// });

				// // Render the view selector to the page.
				// viewSelector.execute();

				/**
				 * Create a new DataChart instance with the given query parameters
				 * and Google chart options. It will be rendered inside an element
				 * with the id "chart-container".
				 */
				var dataChart = new gapi.analytics.googleCharts.DataChart({
				  query: {
					metrics: 'ga:sessions',
					dimensions: 'ga:date',
					'start-date': '30daysAgo',
					'end-date': 'today'
				  },
				  chart: {
					container: 'chart-container',
					type: 'LINE',
					options: {
					  width: '100%'
					}
				  }
				});

				dataChart.set({query: {ids: 'ga:' + '149707050'}}).execute();

				/**
				 * Render the dataChart on the page whenever a new view is selected.
				 */
				// viewSelector.on('change', function(ids) {
				//   dataChart.set({query: {ids: ids}}).execute();
				// });
				});
			</script>
		</div>
	</div>

	<div class="row mb-3">
		<div class="col-xl-6 col-sm-6">
			<br>		
			<h3>Recently connected</h3>
			<div class="list-group">
				<?php foreach ($stats['users']['recentConnected'] as $user):?>
					<a href="<?=$this->Url->build('/users/'.$user->id)?>" targe="_blank" class="list-group-item list-group-item-action flex-column align-items-start">
						<div class="media">
							<img class="mr-3 rounded" height="45" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $user->id . '.png?' . $this->Time->format($user->modificationDate, 'mmss', null, null)); ?>">
							<div class="media-body">
								<div class="d-flex w-100 justify-content-between">
									<h5 class="mb-1"><?= h($user->name) ?>
										<?php if(is_null($user->mailVerification)):?><i class="fa fa-envelope" title="Mail verified"></i><?php endif;?>
										<?php if($user->twitchToken):?><i class="fa fa-twitch" title="Twitch user id : <?= $user->twitchUserId ?>"></i><?php endif;?>
										<?php if($user->verified == "1"):?><i class="fa fa-check-circle" title="Certified user"></i><?php endif;?>
										<?php if($user->verified == "125"):?><i class="fa fa-fort-awesome" title="Admin user"></i><?php endif;?>
									</h5>
									<small><?= $this->Time->format($user->lastLogginDate, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $user->lastLogginDate, $authUser['timeZone']); if(!$authUser): echo ' (GMT)'; endif; ?></small>
								</div>
								<small><?= h($user->mail) ?></small>
							</div>
						</div>
					</a>
				<?php endforeach ?>
			</div>
		</div>

		<div class="col-xl-6 col-sm-6">
			<br>
			<h3>Recently registered</h3>
			<div class="list-group">
				<?php foreach ($stats['users']['recentCreated'] as $user):?>
					<a href="<?=$this->Url->build('/users/'.$user->id)?>" targe="_blank" class="list-group-item list-group-item-action flex-column align-items-start">
						<div class="media">
							<img class="mr-3 rounded" height="45" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $user->id . '.png?' . $this->Time->format($user->modificationDate, 'mmss', null, null)); ?>">
							<div class="media-body">
								<div class="d-flex w-100 justify-content-between">
									<h5 class="mb-1"><?= h($user->name) ?>
										<?php if(is_null($user->mailVerification)):?><i class="fa fa-envelope" title="Mail verified"></i><?php endif;?>
										<?php if($user->twitchToken):?><i class="fa fa-twitch" title="Twitch user id : <?= $user->twitchUserId ?>"></i><?php endif;?>
										<?php if($user->verified == "1"):?><i class="fa fa-check-circle" title="Certified user"></i><?php endif;?>
										<?php if($user->verified == "125"):?><i class="fa fa-fort-awesome" title="Admin user"></i><?php endif;?>
									</h5>
									<small><?= $this->Time->format($user->creationDate, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $user->creationDate, $authUser['timeZone']); if(!$authUser): echo ' (GMT)'; endif; ?></small>
								</div>
								<small><?= h($user->mail) ?></small>
							</div>
						</div>
					</a>
				<?php endforeach ?>
			</div>
		</div>
	</div>

	<div class="row mb-3">
		<div class="col-xl-6 col-sm-6">
			<br>
			<h3>Latest comments</h3>
			<div class="list-group">
				<?php foreach ($stats['comments']['recentCreated'] as $comment):?>
					<a href="<?=$this->Url->build('/setups/'.$comment->setup_id)?>" targe="_blank" class="list-group-item list-group-item-action flex-column align-items-start">
						<div class="d-flex w-100 justify-content-between">
							<h5 class="mb-1"><strong><?= h($comment->user->name) ?></strong> on <strong><?= h($comment->setup->title) ?></strong></h5>
							<small><?= $this->Time->format($comment->dateTime, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $comment->dateTime, $authUser['timeZone']); if(!$authUser): echo ' (GMT)'; endif; ?></small>
						</div>
						<p class="mb-1"><?= h($comment->content) ?></p>
						<small><?= h($user->mail) ?></small>
					</a>
				<?php endforeach ?>
			</div>
		</div>

		<div class="col-xl-6 col-sm-6">
			<br>
			<h3>Ownership requests</h3>
			<div class="list-group">
				<?php foreach ($stats['requests']['onGoing'] as $request):?>
					<a href="<?=$this->Url->build('/setups/'.$request->setup_id)?>" targe="_blank" class="list-group-item list-group-item-action flex-column align-items-start">
						<div class="d-flex w-100 justify-content-between">
							<h5 class="mb-1"><strong><?= h($request->user->name) ?></strong> ask for ownership on <strong><?= h($request->setup->title) ?></strong></h5>
							<small>#<?= $request->token ?></small>
						</div>
						<p class="mb-1"><?= h($request->content) ?></p>
						<small><?= h($user->mail) ?></small>
					</a>
				<?php endforeach ?>
			</div>
		</div>
	</div>
	
	<script>
		new Chart(document.getElementById("certified-user"),{"type":"doughnut","data":{"labels":["Certified","Not Certified"],"datasets":[{"label":"Certified users","data":[<?=$stats['users']['certified']?>,100-<?=$stats['users']['certified']?>],"backgroundColor":["rgb(54, 162, 235)","rgb(255, 99, 132)"]}]}});

		new Chart(document.getElementById("twitch-user"),{"type":"doughnut","data":{"labels":["Twitch user","Simple user"],"datasets":[{"label":"Certified users","data":[<?=$stats['users']['twitch']?>,100-<?=$stats['users']['twitch']?>],"backgroundColor":["#6441a5","rgb(255, 99, 132)"]}]}});
	</script>
</div>
