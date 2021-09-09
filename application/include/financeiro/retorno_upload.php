<div class="alert alert-info">
    <div> - Somente arquivo do tipo (<strong> RET </strong>) é permitido.</div>
    <div> - Você pode <strong> arrastar e soltar </strong> arquivos do seu desktop nesta página com o Google Chrome, Mozilla Firefox e Apple Safari.</div>
</div>

<!-- The file upload form used as target for the file upload widget -->
<form id="fileupload" action="<?php echo $this->baseUrl(); ?>/content/upload/retorno.php" method="POST" enctype="multipart/form-data">
    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
    <div class="row fileupload-buttonbar">
        <div>
            <div class="progressbar loading-progressbar">
                <div style="width:100%; padding: 1px;">
                    <span style="color: #fff; font-size: 11px; line-height: 20px;">Por favor, aguarde ...</span>
                </div>
            </div>
            <!-- The fileinput-button span is used to style the file input field as button -->
            <span class="btn btn-success fileinput-button btn-small">
                <i class="icon-plus icon-white"></i>
                <span>Adicionar arquivo retorno...</span>
                <input type="file" name="files[]" multiple>
            </span>
            <button type="button" class="btn btn-danger delete btn-small">
                <i class="icon-trash icon-white"></i>
                <span>Delete</span>
            </button>
            <input type="checkbox" class="toggle">
        </div>

    </div>
    <!-- The loading indicator is shown during file processing -->
    <div class="fileupload-loading"></div>
    <br>
    <!-- The table listing the files available for upload/download -->
    <div style="height:350px; overflow-y:auto;">
        <table role="presentation" class="table table-striped table-condensed">
            <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody>
        </table>
    </div>
</form>

<!-- modal-gallery is the modal dialog used for the image gallery -->
<div id="modal-gallery" class="modal modal-gallery hide fade" data-filter=":odd">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3 class="modal-title"></h3>
    </div>
    <div class="modal-body"><div class="modal-image"></div></div>
    <div class="modal-footer">

    </div>
</div>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="name"><span>{%=file.name%}</span></td>
        {% if (file.error) { %}
        <td class="error" style="text-align: right;"><span class="label label-important">{%=locale.fileupload.errors[file.error] || file.error%}</span> </td>
        {% } else if (o.files.valid && !i) { %}
        
        <td class="start">{% if (!o.options.autoUpload) { %}
            <button class="btn btn-primary">
                <i class="icon-upload icon-white"></i>
                <span>{%=locale.fileupload.start%}</span>
            </button>
            {% } %}</td>
        {% } else { %}
        <td colspan="2"></td>
        {% } %}
        <td class="cancel" style="width: 100px; text-align: center;">{% if (!i) { %}
            <button class="btn btn-warning btn-mini">
                <i class="icon-ban-circle icon-white"></i>
                <span>{%=locale.fileupload.cancel%}</span>
            </button>
            {% } %}</td>
    </tr>
    {% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
        <td></td>
        <td class="name"><span>{%=file.name%}</span></td>
        <td></td>
        <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else { %}
        <td class="name" colspan="2">
            <a target="_blank" href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
        </td>
        {% } %}
        <td class="delete" style="width: 100px; text-align: center;">
            <button class="btn btn-danger btn-mini" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">
                <i class="icon-trash icon-white"></i>
                <span>{%=locale.fileupload.destroy%}</span>
            </button>
            <input type="checkbox" name="delete" value="1">
        </td>
    </tr>
    {% } %}
</script>

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo $this->baseUrl(); ?>/content/upload/jquery/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="<?php echo $this->baseUrl(); ?>/content/upload/jquery/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="<?php echo $this->baseUrl(); ?>/content/upload/jquery/js/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="<?php echo $this->baseUrl(); ?>/content/upload/jquery/js/canvas-to-blob.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo $this->baseUrl(); ?>/content/upload/jquery/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo $this->baseUrl(); ?>/content/upload/jquery/js/jquery.fileupload.js"></script>
<!-- The File Upload file processing plugin -->
<script src="<?php echo $this->baseUrl(); ?>/content/upload/jquery/js/jquery.fileupload-fp.js"></script>
<!-- The File Upload user interface plugin -->
<script src="<?php echo $this->baseUrl(); ?>/content/upload/jquery/js/jquery.fileupload-ui.js"></script>
<!-- The localization script -->
<script src="<?php echo $this->baseUrl(); ?>/content/upload/jquery/js/locale.js"></script>
<!-- The main application script -->
<script src="<?php echo $this->baseUrl(); ?>/content/upload/jquery/js/main.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->
<!--[if gte IE 8]><script src="js/cors/jquery.xdr-transport.js"></script><![endif]-->


<script type="text/javascript">
    $(function () {
        $('#fileupload').fileupload('option', {
            acceptFileTypes:/(\.|\/)(ret)$/i,
            maxNumberOfFiles : 1000
        })
        .bind('fileuploadstart', function (e) {
            $('button[type=submit]').attr('disabled',true);
            $('#fileupload .progressbar').show();
        })
        .bind('fileuploadstop', function (e) {
            $('button[type=submit]').attr('disabled',false);
            $('#fileupload .progressbar').hide();
        });
        
        $('#fileupload .progressbar').hide();
        
    });
</script>


</form>

