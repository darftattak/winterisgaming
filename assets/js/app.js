/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

$("#orderNumberContainer").hide()

$("#contact_topic").change(function(){
    if($(this).val() == "Commande" && $("#orderNumberContainer").is(":hidden") == true) {
        $("#orderNumberContainer").show()
    } else if (($(this).val() != "Commande" && $("#orderNumberContainer").is(":hidden") != true)) {
        $("#orderNumberContainer").hide()
    }
})

//Force password update forms styles

var input= $('#plainPass').children().children().filter(":input")

input.each(function(){
    $(this).addClass('form-control')
})

var filterCat= $('#catFilterDiv').children().children().children().filter(":input")
filterCat.each(function(){
    $(this).addClass('form-check-input')
})

//fonction du caroussel nouveauté

  


//FONCTION FLECHE ANIMEE

$( '.cgvA' ).each(function() {
    $(this).append('<i class="fas fa-caret-right ml-1" id="CD"></i>')
    $(this).on('click', function(){

        if($(this).hasClass('collapsed')) {
            
            $(this).children('svg').remove()
            $(this).append('<svg class="svg-inline--fa fa-caret-down fa-w-10 ml-1" id="CB" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z"></path></svg>')

        } else {
            $(this).children('svg').remove()
            $(this).append('<svg class="svg-inline--fa fa-caret-right fa-w-6 ml-1" id="CD" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512" data-fa-i2svg=""><path fill="currentColor" d="M0 384.662V127.338c0-17.818 21.543-26.741 34.142-14.142l128.662 128.662c7.81 7.81 7.81 20.474 0 28.284L34.142 398.804C21.543 411.404 0 402.48 0 384.662z"></path></svg>')
        }

    })
   
});

//Ajax request

$(".cartQuantity").each(function(){
    $(this).change(function(){
        if (typeof(parseInt($(this).val(), 10)) !== "number"){
            $(this).val(1);
        }
        if(!Number.isInteger(parseInt($(this).val(), 10))){
            Math.ceil(parseInt($(this).val(), 10));
        }
        if (parseInt($(this).val() < 1)){
            $(this).val(1);
        }

        var unit = $(this).attr('unit')


        $.ajax('/ajax/cart/quantity', {
            method: 'get',
            data: 'id=' + $(this).attr('id') + '&quantity=' + $(this).val(),
        }).done(updateRow($(this)))

    })
})

function updateRow (elem){
    var newPrice = elem.val() * elem.attr('unit')
    elem.parent().siblings("[totalItem]").html(newPrice + "€");
    
    $("#cartTotalValue").html('')
    $("#cartTotalValue").html(cartTotalUpdate)
}

function cartTotalUpdate(){
    var total = 0
    var allItem = $('.cartQuantity')
    
    allItem.each(function() {
        var quantity = parseInt($(this).val())
        var unit = parseInt($(this).attr('unit'))
        total += unit * quantity
    });
    return total
}

// Test of Security function for all forms

$(document).ready(function(){

    var originalArray = [];
    $('input').each(function(){
        originalArray.push($(this).attr('type'))
    })
    $('select').each(function(){
        originalArray.push($(this).attr('id'))
    })

    $('form').each(function(){
        var form = $(this)
        $(this).on('submit', function(event){
            event.preventDefault()
    
            var fraud = redirectFormFraud(originalArray)
            
            if (fraud === true) {
                $.ajax('/ajax/fraud', {
                    method: 'get',
                }).done(function(response) {
                    document.location.replace(response.results)
                })
            } else {
                $(this).off().submit()
            }
        })
    })
})

function redirectFormFraud(originalArray){
    var currentArray = [];

    $('input').each(function(){
        currentArray.push($(this).attr('type'))
    })
    $('select').each(function(){
        currentArray.push($(this).attr('id'))
    })

    if (JSON.stringify(originalArray) == JSON.stringify(currentArray)) {
        var difference = false
    } else {
        var difference = true
    }

    return difference
}

