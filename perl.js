<script>
    var editors = [];
    function initEditor(map) {
        var options = {
            lineNumbers: true,
        };
        if (map != 'empty') options['keyMap'] = map;
        $('textarea[name=code]').each(function() {
            var editor = CodeMirror.fromTextArea(this, options);
            editors.push(editor);
        });

        $('#editors').children('a').each(function() {
            $(this).removeClass('active');
        });

        $('#editor-' + map).addClass('active');
    }

    $(document).ready(function() {
        initEditor('empty');

        $('[id^=editor-]').click(function() {
            var name = $(this).attr('id');
            name = name.replace('editor-', '');
            editors[0].toTextArea(); editors = [];
            initEditor(name);
            return false;
        });

        $('form').submit(function() {
            var form = this;
            var textarea = $(this).find('div textarea[name=code]').first();
            var code = $(textarea).val();

            var loader = $(this).children('img[name=ajax-loader]').first();
            $(loader).removeClass('hidden');

            $.ajax({
                type: 'POST',
                url: '/eval',
                data: {code: code},
                success: function(data) {
                    $(loader).addClass('hidden');
                    var stdout = $(form).children('.stdout');
                    $(stdout).addClass('hidden').html('');
                    var stderr = $(form).children('.stderr');
                    $(stderr).addClass('hidden').html('');

                    if (data.stdout) {
                        $(stdout).html('<h4>Output:</h4><pre dir="ltr">' + data.stdout + '</pre>');
                        $(stdout).removeClass('hidden');
                    }

                    var errors = data.error || data.stderr;

                    if (errors) {
                        $(stderr).html('<h4>Errors &amp; Warnings:</h4><pre dir="ltr">' + errors + '</pre>');
                        $(stderr).removeClass('hidden');
                    }
                },
                error: function(data) {
                    $(loader).addClass('hidden');

                    var stderr = $(form).children('.stderr');
                    $(stderr).addClass('hidden').html('');

                    $(stderr).html('<h4>Errors:</h4><pre dir="ltr">Can\'t connect to perltuts</pre>');
                    $(stderr).removeClass('hidden');
                },
                dataType: 'JSON'
            });

            return false;
        });
    });
</script>