<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="http://jieba-php.fukuball.com, jieba-php 中文斷詞線上展示網站。I am fukuball, CTO of iNDIEVOX 獨立音樂網, the largest indie music web site in Taiwan. / I'm also a happy guitar player." />
    <meta name="author" content="Fukuball" />
    <meta name="keywords" content="fukuball, Fukuball Lin, 林志傑" />
    <link rel="icon" href="http://www.fukuball.com/favicon.ico?v1" />
    <title>jieba-php 中文斷詞線上展示網站</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 70px;
        }
    </style>
</head>
<body>
<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Jieba PHP</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="/">Home</a></li>
                <li><a href="http://www.fukuball.com">Fukuball</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">

    <!-- Main component for a primary marketing message or call to action -->
    <section class="jumbotron">
        <h1>Jieba PHP 線上中文斷詞服務</h1>
        <p>
            自然语言处理系统最基本需要让电脑能够分辨文本中字词的意义，才能够更进一步发展出自然语言处理系统的相关演算法，其中断词处理便是一个重要的前置技术， Jieba PHP 线上中文断词服务网站使用了 jieba-php 的中文断词程式，让有中文断词需求的研究者或程式人员可以专注于开发自己的核心演算法。
        </p>
        <p>
            <a class="btn btn-lg btn-primary" href="https://github.com/fukuball/jieba-php" role="button">
                Fork Jieba-PHP &raquo;
            </a>
        </p>
    </section>
    <section>
        <h2>
            請輸入要斷詞的短文：
        </h2>
        <div class="well row">
            <form id="jieba-process-form" name="jieba_process_form" accept-charset="utf-8" action="/jieba-process" method="post">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <textarea style="font-size: 30px;line-height: 50px;" class="form-control col-xs-12 col-sm-12 col-md-12 col-lg-12" id="paragraph" name="paragraph" placeholder="請輸入要斷詞的短文，限140字短文" rows="5">請問本週領唱是誰！</textarea>
                    <div class="help-block hide">
                        請輸入 1~140 字的短文
                    </div>
                </div>
                <div>
                    <h4 class="pull-right" style="margin-top: 20px;">
                        <span id="paragraph-char-counter">140</span> characters remaining
                    </h4>
                    <button id="jieba-process-submit-btn" class="btn btn-primary btn-lg" type="submit" style="margin-top: 20px;" >
                        取得斷詞結果
                    </button>
                </div>
            </form>
        </div>
        <h2 id="jieba-result-h" class="hide">
            斷詞結果：
        </h2>
        <div id="jieba-result-block" class="well hide" style="font-size: 30px;line-height: 50px;">
        </div>
    </section>

    <div style="margin-top:20px; margin-bottom: 20px;">
        <script type="text/javascript" src="//js1.bloggerads.net/ShowBanner.aspx?blogid=20161207000006"></script>
        <style>
            #BA_AD_1 {
                margin-left:auto;
                margin-right:auto;
            }
        </style>
    </div>

</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
<script src="/js/jquery.form.js"></script>
<script src="/js/jquery.simplyCountable.js"></script>
<script src="https://pulipulichen.github.io/jieba-js/require-jieba-js.js"></script>

<script>

    function call_jieba(){
        call_jieba_cut($('#paragraph').val(), function (_result) {
            console.log(_result);
        });
    }
</script>
</body>
</html>