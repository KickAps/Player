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
    // Gestion des catégories
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

    // Formulaire de modification
    $('a[id^=video_]').each(function (i) {
        $(this).on('click', function () {
            var id = $(this).attr('id').replace('video_', '');
            $('input#video_id').val(id);

            $.get(window.location.origin + '/video_info?id=' + id, function (response) {
                console.log(response);
                $('input#video_title').val(response['title']);
                $('input#video_year').val(response['year']);
                $('input#video_onedrive_id').val(response['onedrive_id']);
                $('input#video_onedrive_authkey').val(response['onedrive_authkey']);

                $('div#video_flag :input').prop('checked', false);
                if (response['flag']) {
                    response['flag'].forEach(i => {
                        $('input#video_flag_' + i).prop('checked', true);
                    });
                }
            });
        });

    });

    $('button#new_video').on('click', function () {
        $('input#video_id').val("");
        $('input#video_title').val("");
        $('input#video_year').val("");
        $('input#video_onedrive_id').val("");
        $('input#video_onedrive_authkey').val("");
        $('div#video_flag :input').prop('checked', false);
    });
});
