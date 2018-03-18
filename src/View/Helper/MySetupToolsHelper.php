<?php

namespace App\View\Helper;

use Cake\View\Helper;

class MySetupToolsHelper extends Helper
{
    public function getPlainTextIntroFromHTML($html)
    {
        // Remove the HTML tags
        $html = strip_tags($html);

        // Convert HTML entities to single characters
        $html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');

        // Gets rid of line feed characters
        $html = str_replace("\n", " ", $html);

        return $html;
    }

    function urlPrettifying($url)
    {
        // Gets rid of protocol and possible 'www' prefix !
        return preg_replace('/https?:\/\/(www\.)?/', '', $url);
    }
}
