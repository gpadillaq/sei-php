<!DOCTYPE html>
<html>
    <head>
        <title>SEI Universidad Tecnologica Lasalle</title>
        <script src="js/jquery-1.9.1.js"></script>
        <script src="js/jquery-ui.js"></script>
        <script src="ckeditor/ckeditor.js"></script>        
    </head>
    <body>

        <?php
            
        ?>
        <form>
            <textarea name="editor" id="editor" rows="10" cols="80"></textarea>
            <script>
                //Leer las cookis
                id = "";
                estructura = "";
                $.each(document.cookie.split(';'), function(index, elem) {
                        var cookie = $.trim(elem);
                        if (cookie.indexOf('idStructura') == 0) {
                        id = cookie.slice(name.length + 1);};
                        if (cookie.indexOf('codigoStructura') == 0) {
                        estructura = cookie.slice(name.length + 1);};
                    });

                //Eliminar la etiqueta dela cooke
                //estructura = estructura.split("=");
                estructura = <?php echo "'".htmlentities($_REQUEST['codigo'])."'"; ?>;
                alert(estructura);
                editor = "<input type=hidden name=id value"+ id +">";
                $("#editor").val(estructura);
                $("#editor").before(editor);
                
                CKEDITOR.replace( 'editor' );
            </script>            
        </form>
    </body>
</html>