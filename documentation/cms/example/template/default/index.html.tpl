<!DOCTYPE html>
<html lang="de">
<head>
    <!-- Start Block meta -->
    {block name="meta"}
        <meta charset="UTF-8">
        <title>{block "title"}{$title}{/block}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="author" content="{block "meta_author"}WBS https://www.blessen.de{/block}">
        <meta name="generator" content="{block "meta_generator"}WBS CMS{/block}">
        <meta
                name="description"
                property="description"
                content="{block "meta_description"}Powered by WBS CMS{/block}"
                id="meta_description"/>
    {/block}
    <!-- End Block meta -->

    <!-- Start Block css -->
    {block name="css"}
{*        https://getbootstrap.com/docs/4.6/examples/*}
        <link rel="stylesheet" href="/package/bootstrap-4.6.2-dist/css/bootstrap.css">
        <link rel="stylesheet" href="/template/default/default.css">
    {/block}
    <!-- End Block css -->

    <!-- Start Block top_javascript -->
    {block name="top_javascript"}
    {/block}
    <!-- End Block top_javascript -->

</head>

<body>
<!-- Start Block menu -->
{block name="menu"}
    {include "../../inhalt/menu.html.tpl"}
{/block}
<!-- End Block menu -->

<main role="main" class="container">

    <div class="starter-template">
        <!-- Start Block content -->
        {block "content"}
            <h1>Template Default</h1>
            <p class="lead">Diesen Text sollte nie jemand zu sehen bekommen.</p>
            <p class="alert-info">Falls es so ist, bitte ignorieren,
                die Zurück Taste im Browser betätigen,
                oder Offline und an die frische Luft gehen ...</p>


        {/block}
        <!-- End Block content -->
    </div>

</main>
</body>
<!-- Start Block bottom_javascript -->
{block name="bottom_javascript"}
{*    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>*}
    <script src="/package/jquery-3.5.1/jquery.slim.min.js"></script>
    <script src="/package/bootstrap-4.6.2-dist/js/bootstrap.js"></script>
{/block}
<!-- End Block bottom_javascript -->

</html>