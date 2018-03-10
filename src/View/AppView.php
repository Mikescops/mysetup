<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;
use Cake\Core\Configure;

/**
 * Application View
 *
 * Your application’s default view class
 *
 * @link http://book.cakephp.org/3.0/en/views.html#the-app-view
 */
class AppView extends View
{

    public function initialize()
    {
        $this->set('debug', Configure::read('debug'));

        if($this->request->action === 'view')
        {
            // We'll only load our Markdown Helper for '{Setups,Articles}.view' pages !
            if(in_array($this->request->controller, ['Setups', 'Articles']))
            {
                $this->loadHelper('Tanuck/Markdown.Markdown');
            }

            // Here we'll apply special templates for our modal check-boxes
            if(in_array($this->request->controller, ['Setups', 'Users']))
            {
                $this->Form->setTemplates([
                    'checkboxContainer' => '
                        <div class="checkbox_container">
                            {{content}}
                        </div>
                    ',
                    'nestingLabel' => '
                        {{input}}
                        <label{{attrs}}>
                            {{text}}
                        </label>
                    '
                ]);
            }
        }

        // Let's set a beautiful template for the Admin pages `Paginator`
        if($this->request->controller === 'Admin')
        {
            $this->Paginator->setTemplates([
                'current' => '<li class="page-item active"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                'number' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                'first' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                'last' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                'nextActive' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                'prevActive' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                'nextDisabled' => '<li class="page-item disabled"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                'prevDisabled' => '<li class="page-item disabled"><a class="page-link" href="{{url}}">{{text}}</a></li>'
            ]);
        }
    }
}
