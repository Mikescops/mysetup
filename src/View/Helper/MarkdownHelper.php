<?php

namespace App\View\Helper;

use Cake\View\Helper;

class MarkdownHelper extends Helper
{
    protected $_defaultConfig = [
        'parser' => 'GithubMarkdown'
    ];
}
