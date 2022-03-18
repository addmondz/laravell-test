<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="{{ url(mix('css/app.css')) }}" rel="stylesheet">

    <title>Laravel-test</title>
</head>

<body>
    <h2 class="instruction-text">Please choose a function to continue.</h2>

    <div class="row select-function-container">
        <div class="col-auto">
            <select class="form-select select-function" onchange="nextStep()">
                <option value="">Please choose</option>
                <option value="changeLocation">Change Location</option>
                <option value="changeColor">Change Color</option>
                <option value="changeStyle">Change Style</option>
            </select>
        </div>
        <p class="detailed-instructions"></p>
    </div>

    <div class="row select-color-container">
        <div class="col-auto">
            <select class="form-select select-color">
                <option value="">Choose Color</option>
                <?php
                $colors = array('primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'body', 'white', 'transparent');
                foreach ($colors as $value) {
                    echo '<option class="text-capitalize text-bold text-' . $value . '" value="' . $value . '">' . $value . '</option>';
                }
                ?>
            </select>
        </div>
        <p class="detailed-color-instructions"></p>
    </div>

    <div class="row select-style-container">
        <div class="col-auto pl-0">
            <select class="form-select select-style">
                <option value="">Choose Style</option>
                <?php
                $style = array('underline', 'italic', 'oblique');
                foreach ($style as $styleValue) {
                    echo '<option class="text-capitalize text-bold font-' . $styleValue . '" value="' . $styleValue . '">' . $styleValue . '</option>';
                }
                ?>
            </select>
        </div>
        <p class="detailed-color-instructions"></p>
    </div>

    <div class="table-container">
        <table>
            <?php for ($y = 0; $y <= 3; $y++) { ?>
                <tr>
                    <?php for ($x = 0; $x <= 3; $x++) { ?>
                        <td class="col hover <?= $x; ?>-<?= $y; ?>" onclick="actionClicked('<?= $x; ?>-<?= $y; ?>')">&nbsp;</td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ url(mix('js/app.js')) }}"></script>

    <script>
        var selectedCol = '';
        $('.select-color-container').hide();
        $('.select-style-container').hide();
        $('.1-0').text('Volservers.com');
        $('.3-0').text('Build Trust');
        $('.0-2').text('B2B Marketplace');
        $('.2-2').text('SaaS enabled Marketplace');
        $('.3-3').text('Provide Transparency');

        function selectCol(name) {
            $('td').removeClass('hover');
            $('td:not(.' + name + ')').addClass('choosing');
            $('.' + name).addClass('selected');
            this.selectedCol = name;
        }

        function unSelectCol() {
            $('td')
                .addClass('hover')
                .removeClass('choosing selected');
            this.selectedCol = '';
        }

        function resetColumnClassAndAttr(name, type = '') {
            if (type == '') {
                $('.' + name)
                    .removeClass()
                    .addClass('hover ' + name)
                    .attr('data-style', undefined)
                    .attr('data-color', undefined);
            }

            var dataValue = $('.' + name).attr('data-' + type);
            $('.' + name)
                .removeClass(dataValue)
                .attr('data-' + type, undefined);
        }

        function addClassAndAttr(onClass, type, className) {
            $('.' + onClass)
                .attr('data-' + type + '', className)
                .addClass(className);
        }

        function moveColumn(from, to) {
            $('.' + to).html($('.' + from).html());
            $('.' + from).html('&nbsp');

            // move color
            var previosSelectedColor = $('.' + this.selectedCol).attr('data-color');
            if (previosSelectedColor != undefined) {
                resetColumnClassAndAttr(to, 'color');
                addClassAndAttr(to, 'color', previosSelectedColor)
            }

            // move style
            var previosSelectedStyle = $('.' + this.selectedCol).attr('data-style');
            if (previosSelectedStyle != undefined) {
                resetColumnClassAndAttr(to, 'style');
                addClassAndAttr(to, 'style', previosSelectedStyle)
            }
            // unset the color and style on previous column
            resetColumnClassAndAttr(from);
        }

        function actionClicked(current) {
            // change text position
            if ($('.select-function :selected').val() == 'changeLocation') {
                if (this.selectedCol == '') {
                    // if empty column is clicked, then do nothing
                    if ($('.' + current).html() == '&nbsp;') {
                        return;
                    }
                    selectCol(current);
                    return;
                }

                if (this.selectedCol != current) {
                    // if the choosen column is taken, alert error msg and exit
                    if ($('.' + current).html() != '&nbsp;') {
                        alert('This column is taken, please choose other column');
                        return;
                    }
                    moveColumn(this.selectedCol, current);
                }

                unSelectCol();
            }

            // change text color
            if ($('.select-function :selected').val() == 'changeColor') {
                var selectedColor = $('.select-color :selected').val();
                if (selectedColor != '') {
                    resetColumnClassAndAttr(current, 'color');
                    addClassAndAttr(current, 'color', 'text-' + selectedColor)
                }
            }

            // change text style
            if ($('.select-function :selected').val() == 'changeStyle') {
                var selectedStyle = $('.select-style :selected').val();
                if (selectedStyle != '') {
                    resetColumnClassAndAttr(current, 'style');
                    addClassAndAttr(current, 'style', 'text-' + selectedStyle)
                }
            }
        }

        function nextStep() {
            unSelectCol();
            $('.select-color-container').hide();
            $('.select-color').val('');
            $('.select-style-container').hide();
            $('.select-style').val('');
            $('.detailed-instructions').show();

            if ($('.select-function :selected').val() == '') {
                $('.detailed-instructions').hide();
                return;
            }

            $('.instruction-text').html('Funtion: ' + $('.select-function :selected').text());
            $('.select-function :selected').val() == 'changeLocation' ? $('.detailed-instructions').html('Choose a text to change its position') : '';
            $('.select-function :selected').val() == 'changeColor' ? $('.detailed-instructions').html('Choose a color and apply it on the text') : '';
            $('.select-function :selected').val() == 'changeStyle' ? $('.detailed-instructions').html('Choose a style and apply it on the text') : '';

            if ($('.select-function :selected').val() == 'changeColor') {
                $('.select-color-container').show();
            }

            if ($('.select-function :selected').val() == 'changeStyle') {
                $('.select-style-container').show();
            }
        }
    </script>
</body>

</html>