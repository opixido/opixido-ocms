@@message@@

@@form@@

<script type="text/javascript">

    function setupLabel() {

        if ($('.label_check input').length) {
            $('.label_check').each(function () {
                $(this).removeClass('c_on');
            });
            $('.label_check input:checked').each(function () {
                $(this).parent('label').addClass('c_on');
            });
        }
        ;
    }
    ;

    $(document).ready(function () {

        // Checkboxes
        $('body').addClass('has-js');
        $('.div_checkbox label input').click(function () {
            setupLabel();
        });
        setupLabel();

        $('#newsletter input.submit').mouseover(function () {
            $(this).css('background', '#484b54');
        });

        $('#newsletter input.submit').mouseout(function () {
            $(this).css('background', '#2c3139');
        });
    });

</script>