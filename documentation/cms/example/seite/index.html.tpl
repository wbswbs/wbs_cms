{extends "../template/{$template}/index.html.tpl"}
{*See Block Examples*}
{*https://smarty-php.github.io/smarty/4.x/designers/language-builtin-functions/language-function-block/#examples*}

{block name="title"}
{$projekt}
{/block}

{block name="content"}
    {include "../inhalt/index.html.tpl"}
{/block}