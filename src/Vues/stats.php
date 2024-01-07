
<div class="iframe">
    <iframe id ="iframe" src="http://192.168.1.34:3838/sae3/"></iframe>
</div>

<script>
    window.onload = init;

    function init()
    {
        var header = document.getElementsByTagName('header');
        var headerHeight = header[0].clientHeight;
        var iframe = document.getElementById("iframe");
        iframe.setAttribute("width", window.innerWidth);
        iframe.setAttribute("height", window.innerHeight + headerHeight);
    }
</script>