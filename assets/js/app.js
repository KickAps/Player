/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../styles/app.scss';

import 'bootstrap';
import $ from 'jquery';

$(function () {
    $('a[id^=flag_]').each(function (i) {
        $(this).on('click', function () {
            $('a[id^=flag_]').removeClass('btn-primary');
            $('a[id^=flag_]').addClass('btn-secondary');

            $(this).removeClass('btn-secondary');
            $(this).addClass('btn-primary');

            $('div[id^=video_list_]').each(function (j) {
                $(this).prop('hidden', i !== j);
            });
        });
    });
});
