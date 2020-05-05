$(document).ready(function(){
    if($('#payement_form').length) {
<<<<<<< HEAD
        var form = document.getElementById('{{ form.vars.id }}');
        var errors = document.getElementById('card-errors');
       
        var stripe = Stripe('{{ stripe_public_key }}');
        var elements = stripe.elements();
        var card = elements.create('card');
       
        card.mount('#card-element');
        card.addEventListener('change', function(event) {
          if (event.error) {
            errors.textContent = event.error.message;
            form.classList.add('has-error');
          } else {
            errors.textContent = '';
            form.classList.remove('has-error');
          }
        });
       
        form.addEventListener('submit', function(event) {
          event.preventDefault();
       
          stripe.createToken(card).then(function(result) {
            if (result.error) {
              errors.textContent = result.error.message;
              form.classList.add('has-error');
            } else {
              document.getElementById('{{ form.children.token.vars.id }}').setAttribute('value', result.token.id);
              form.submit();
            }
          });
        });
=======
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
>>>>>>> productInterface
    }
})