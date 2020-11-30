/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.scss in this case)
require('../css/app.scss');

require('lightslider');

require('material-scrolltop');

require('jquery.scrollto');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

var $widthElement;

function getWidth(element) {
    $widthElement = $(element).width();
    $(element).css("height",$widthElement);
}
getWidth('.container-image');
$(window).resize(function () {
    getWidth(".container-image");
});

var $collectionImg;
var $collectionVideo;

var $addNewImg = $('<button type="button" class="add_tag_link btn btn-info">Ajouter une image</button>');
var $addVideoButton = $('<button type="button" class="add_tag_link btn btn-info">Ajouter une video</button>');

//get the collectionImg
$collectionImg = $('#img_list');
//get the collectionVideo
$collectionVideo = $('#video_list');
//append the add new img to the collectionImg
$collectionImg.append($addNewImg);
$collectionVideo.append($addVideoButton);
// number of index
$collectionImg.data('index', $collectionImg.find('.card').length);
$collectionVideo.data('index', $collectionImg.find('.card').length);

function addRemoveButton ($panel) {
    // create remove button
    var $removeButton = $('<a href="#" class="btn btn-danger">Supprimer</a>');
    // card footer
    var $cardFooter = $('<div class="card-footer"></div>').append($removeButton);

    // handle the click event of the remove button

    $removeButton.click(function (e) {
        e.preventDefault();
        $(e.target).parents('.card').slideUp(1000, function () {
            $(this).remove();
        });
    });

    //append the footer to the card
    $panel.append($cardFooter);
}

function addNewFormVideo() {
    //getting the prototype
    var prototype = $collectionVideo.data('prototype');
    // get index
    var index = $collectionVideo.data('index');
    // create the form
    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    $collectionVideo.data('index', index + 1);

    // create the card
    var $card = $('<div class="card mb-3"></div>');
    //create heading card
    // var $cardheading = $('<div class="card-heading"><img id="blah" src="#" alt="your image" class="img-fluid mb-3" style="max-height: 300px"/></div>');
    // append cardheading in card
    // $card.append($cardheading);
    //create the panel-body and append the form to it
    var $cardBody = $('<div class="card-body"></div>').append(newForm);
    // append the body to the card
    $card.append($cardBody);
    // append the removebutton to the new panel
    addRemoveButton($card);

    //append the panel to the addnewitem
    $addVideoButton.before($card);
}

function addNewFormImg() {
    //getting the prototype
    var prototype = $collectionImg.data('prototype');
    // get index
    var index = $collectionImg.data('index');
    // create the form
    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    $collectionImg.data('index', index + 1);

    // create the card
    var $card = $('<div class="card mb-3"></div>');
    //create heading card
    // var $cardheading = $('<div class="card-heading"><img id="blah" src="#" alt="your image" class="img-fluid mb-3" style="max-height: 300px"/></div>');
    // append cardheading in card
    // $card.append($cardheading);
    //create the panel-body and append the form to it
    var $cardBody = $('<div class="card-body"><div class="card-heading"><img id="blah" src="#" alt="your image" class="img-fluid mb-3" style="max-height: 300px"/></div></div>').append(newForm);
    // append the body to the card
    $card.append($cardBody);
    // append the removebutton to the new panel
    addRemoveButton($card);

    //append the panel to the addnewitem
    $addNewImg.before($card);
}

// add remove button to existing items
$collectionImg.find('.card').each(function () {
    addRemoveButton($(this));
});

$collectionVideo.find('.card').each(function () {
    addRemoveButton($(this));
});

// handle the click event for addnewImg
$addNewImg.click(function (e) {
    e.preventDefault();
    // create a new form and append it to the collectionImg
    addNewFormImg();
});

// handle the click event for addnewVideo
$addVideoButton.click(function(e){
    e.preventDefault();
    // create a new form and append it to the collectionImg
    addNewFormVideo();
});

function readURL(input) {
    var img = $(input).closest('.card-body').children('.card-heading').children('img');
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            img.attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

$(document).on('change', ":input[type=\"file\"]", function() {
    readURL(this);
});

$(document).ready(function() {
    $('#imageGallery').lightSlider({
        gallery:true,
        item:1,
        loop:true,
        thumbItem:9,
        slideMargin:0,
        enableDrag: false,
        currentPagerPosition:'left',
        onSliderLoad: function(el) {
            el.lightGallery({
                selector: '#imageGallery .lslide'
            });
        }
    });
});

$('body').materialScrollTop();

$("#clickme").click(function() {
    $('html, body').animate({
        scrollTop: $("#last-trick").offset().top
    }, 1000);
    return false;
});
