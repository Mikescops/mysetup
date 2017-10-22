<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
    $this->assign('title', __('Dashboard | myAdmin'));
?>

<div class="col-12 col-md-9 col-xl-10">

	<div class="row mb-3">
		<div class="col-xl-3 col-sm-6">
			<div class="card text-white bg-danger h-100">
				<div class="card-body bg-danger">
					<div class="rotate">
						<i class="fa fa-list fa-3x"></i>
					</div>
					<h6 class="text-uppercase">Setups</h6>
					<h1 class="display-4"><?= $total_setups ?></h1>
				</div>
			</div>
		</div>
    <div class="col-xl-3 col-sm-6">
      <div class="card bg-success text-white h-100">
        <div class="card-body bg-success">
          <div class="rotate">
            <i class="fa fa-user fa-3x"></i>
          </div>
          <h6 class="text-uppercase">Users</h6>
          <h1 class="display-4"><?= $total_users ?></h1>
        </div>
      </div>
    </div>
		<div class="col-xl-3 col-sm-6">
			<div class="card text-white bg-info h-100">
				<div class="card-body bg-info">
					<div class="rotate">
						<i class="fa fa-comment fa-3x"></i>
					</div>
					<h6 class="text-uppercase">Comments</h6>
					<h1 class="display-4"><?= $total_comments ?></h1>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-sm-6">
			<div class="card text-white bg-warning h-100">
				<div class="card-body bg-warning">
					<div class="rotate">
						<i class="fa fa-database fa-3x"></i>
					</div>
					<h6 class="text-uppercase">Resources</h6>
					<h1 class="display-4"><?= $total_resources ?></h1>
				</div>
			</div>
		</div>
	</div>

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
