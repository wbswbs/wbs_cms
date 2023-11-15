{extends "../template/{$template}/index.html.tpl"}
{*See Block Examples*}
{*https://smarty-php.github.io/smarty/4.x/designers/language-builtin-functions/language-function-block/#examples*}

{block name="title"}
    Die Band
{/block}

{block name="content"}
    {include "../inhalt/band.html.tpl"}
{/block}