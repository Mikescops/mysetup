<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
?>

<div class="row">

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
            clientid: '778528865548-81re1rpb0l4au681unfo2ko1jiuf8d8p.apps.googleusercontent.com'
          });


          /**
           * Create a new ViewSelector instance to be rendered inside of an
           * element with the id "view-selector-container".
           */
          var viewSelector = new gapi.analytics.ViewSelector({
            container: 'view-selector-container'
          });

          // Render the view selector to the page.
          viewSelector.execute();


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
              'end-date': 'yesterday'
            },
            chart: {
              container: 'chart-container',
              type: 'LINE',
              options: {
                width: '100%'
              }
            }
          });


          /**
           * Render the dataChart on the page whenever a new view is selected.
           */
          viewSelector.on('change', function(ids) {
            dataChart.set({query: {ids: ids}}).execute();
          });

        });
    </script>

    <!-- Step 1: Create the containing elements. -->

    <section id="auth-button"></section>
    <section id="view-selector"></section>
    <section id="timeline"></section>

    <!-- Step 2: Load the library. -->

    <script>
    (function(w,d,s,g,js,fjs){
      g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(cb){this.q.push(cb)}};
      js=d.createElement(s);fjs=d.getElementsByTagName(s)[0];
      js.src='https://apis.google.com/js/platform.js';
      fjs.parentNode.insertBefore(js,fjs);js.onload=function(){g.load('analytics')};
    }(window,document,'script'));
    </script>

    <script>
    gapi.analytics.ready(function() {

      // Step 3: Authorize the user.

      var CLIENT_ID = '778528865548-81re1rpb0l4au681unfo2ko1jiuf8d8p.apps.googleusercontent.com';

      gapi.analytics.auth.authorize({
        container: 'auth-button',
        clientid: CLIENT_ID,
      });

      // Step 4: Create the view selector.

      var viewSelector = new gapi.analytics.ViewSelector({
        container: 'view-selector'
      });

      // Step 5: Create the timeline chart.

      var timeline = new gapi.analytics.googleCharts.DataChart({
        reportType: 'ga',
        query: {
          'dimensions': 'ga:date',
          'metrics': 'ga:sessions',
          'start-date': '30daysAgo',
          'end-date': 'yesterday',
        },
        chart: {
          type: 'LINE',
          container: 'timeline'
        }
      });

      // Step 6: Hook up the components to work together.

      gapi.analytics.auth.on('success', function(response) {
        viewSelector.execute();
      });

      viewSelector.on('change', function(ids) {
        var newIds = {
          query: {
            ids: ids
          }
        }
        timeline.set(newIds).execute();
      });
    });
    </script>

    <div class="col-sm-12">
        <h3><?= __('Setups') ?></h3>
        <table class="table table-striped table-responsive" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('author') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('featured') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('creationDate') ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($setups as $setup): ?>
                <tr>
                    <td><?= $this->Number->format($setup->id) ?></td>
                    <td><?= $setup->has('user') ? $this->Html->link($setup->user->name, ['controller' => 'Users', 'action' => 'view', $setup->user->id]) : '' ?></td>
                    <td><?= h($setup->title) ?></td>
                    <td><?= h($setup->author) ?></td>
                    <td><?= h($setup->featured) ?></td>
                    <td><?= h($setup->creationDate) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $setup->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $setup->id], ['confirm' => __('Are you sure you want to delete this setup ?')]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->first('<< ' . __('first')) ?>
                <?= $this->Paginator->prev('< ' . __('previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('next') . ' >') ?>
                <?= $this->Paginator->last(__('last') . ' >>') ?>
            </ul>
            <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
        </div>
    </div>
</div>
