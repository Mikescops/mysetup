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
    }
}
