<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
    $this->assign('title', __('Dashboard | myAdmin'));
?>

<div class="col-12 col-md-9 col-xl-10">


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