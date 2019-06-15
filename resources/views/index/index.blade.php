<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<input type="text" id="name">
<input type="text" id="pwd">
<input type="button" id="btn">
</body>
<script src="/js/jquery1.7.min.js"></script>
<script>
    $(function () {
        $("#btn").click(function () {
            var name=$("#name").val();
            var pwd=$("#pwd").val();
            $.ajax({
                url:'/test/curl3',
                data:{name:name,pwd:pwd},
                type:'post',
                dataType:'json',
                success:(function (d) {
                   alert(d.msg)
                })
            })
        })

    })
</script>
</html>