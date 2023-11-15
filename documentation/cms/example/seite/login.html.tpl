{extends "../template/{$template}/index.html.tpl"}
{*See Block Examples*}
{*https://smarty-php.github.io/smarty/4.x/designers/language-builtin-functions/language-function-block/#examples*}

{block name="title"}
    Login
{/block}

{block name="content"}
    Loading Content
    {include "../inhalt/login.html.tpl"}
{/block}