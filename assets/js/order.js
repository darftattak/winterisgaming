$(document).ready(function(){
    if($('#payement_form').length) {
        var stripe = Stripe('pk_test_i0owDQKVS941OjJBhfTXttY200C9cMzpZY');
        var form = $('#payement_form');
        form.submit(function(e)
        {
            e.preventDefault();
            form.find('.button').attr('disabled',true);
            stripe.card.createToken(form , function(status, response){
            if(response.error){
                form.find(message).remove();
                form.prepend('<div class="alert alert-warning"><p>' + response.error.message + '</p></div>');
            }else {
                var token = response.id
                console.log(token)
                form.append($('<input type="hidden" name="stripeToken">')).val(token)
                form.get(0).submit()
                
            }})

        })
    }
})